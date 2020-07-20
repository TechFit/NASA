<?php

namespace App\Service;

use App\DataObject\EarthObject;
use App\Entity\Asteroid;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Psr\Log\LoggerInterface;

/**
 * Class NeoImport
 */
class NeoImport
{
    private NasaNeoGrabber $nasaNeoGrabber;

    private ObjectRepository $asteroidRepository;

    private EntityManagerInterface $em;

    private LoggerInterface $logger;

    /**
     * NeoImport constructor.
     *
     * @param NasaNeoGrabber $nasaNeoGrabber
     * @param ManagerRegistry $managerRegistry
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     */
    public function __construct(
        NasaNeoGrabber $nasaNeoGrabber,
        ManagerRegistry $managerRegistry,
        EntityManagerInterface $em,
        LoggerInterface $logger
    ) {
        $this->nasaNeoGrabber = $nasaNeoGrabber;
        $this->asteroidRepository = $managerRegistry->getRepository(Asteroid::class);
        $this->em = $em;
        $this->logger = $logger;
    }

    public function importNeosForLastThreeDays()
    {
        try {
            $grabbeAsteroids = $this->nasaNeoGrabber->getNeoFeed();
            $this->importNeos($grabbeAsteroids);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @param EarthObject[] $grabbedAsteroids
     *
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function importNeos(iterable $grabbedAsteroids): void
    {
        /** @var Asteroid[] $dbAsteroids */
        $dbAsteroids = $this->asteroidRepository->findAll();
        $dbAsteroidsMap = [];
        foreach ($dbAsteroids as $dbAsteroid) {
            $dbAsteroidsMap[$dbAsteroid->getReference()] = $dbAsteroid;
        }

        $newAsteroids = [];

        foreach ($grabbedAsteroids as $grabbedAsteroid) {
            $reference = $grabbedAsteroid->getNeoReferenceId();
            if (empty($dbAsteroids) || !array_key_exists($reference, $dbAsteroidsMap)) {
                $newAsteroids[$reference] = (new Asteroid())
                    ->setName($grabbedAsteroid->getName())
                    ->setReference($grabbedAsteroid->getNeoReferenceId())
                    ->setSpeed($grabbedAsteroid->getKilometersPerHour())
                    ->setDate($grabbedAsteroid->getDate())
                    ->setIsHazardous($grabbedAsteroid->isHazardous());
            }
        }

        $batchSize = 20;
        $i = 0;

        foreach ($newAsteroids as $asteroid) {
            $this->em->persist($asteroid);
            $i++;
            if (($i % $batchSize) === 0) {
                $this->em->flush();
                $this->em->clear();
                $i = 0;
            }
        }

        $this->em->flush();
        $this->em->clear();
    }
}
