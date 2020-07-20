<?php

namespace App\Controller;

use App\Entity\Asteroid;
use App\Repository\AsteroidRepository;
use App\Service\NeoImport;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class NeoController
 * @Rest\Route("/neo",name="neo_")
 */
class NeoController extends AbstractFOSRestController
{
    private NeoImport $neoImport;

    /**
     * NeoController constructor.
     *
     * @param NeoImport $neoImport
     */
    public function __construct(NeoImport $neoImport)
    {
        $this->neoImport = $neoImport;
    }

    /**
     * @param Request $request
     *
     * @Rest\Route("/hazardous/{limit}/{offset}", name="hazardous", defaults={"limit": 20, "offset": 0})
     *
     * @return Response
     */
    public function getHazardousAction(Request $request): Response
    {
        $this->neoImport->importNeosForLastThreeDays();

        $limit = $request->attributes->get('limit');
        $offset = $request->attributes->get('offset');

        /** @var AsteroidRepository $asteroidRepository */
        $asteroidRepository = $this->getDoctrine()->getRepository(Asteroid::class);

        $view = $this->view()->setData([
            'result' => $asteroidRepository->findAllAsteroidWithPagination($limit, $offset),
        ]);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     *
     * @Rest\Route("/fastest/{hazardous}", name="fastest", defaults={"hazardous": false})
     *
     * @return Response
     */
    public function getFastestAction(Request $request): Response
    {
        $isHazardous = $request->attributes->get('hazardous');
        /** @var AsteroidRepository $asteroidRepository */
        $asteroidRepository = $this->getDoctrine()->getRepository(Asteroid::class);

        $view = $this->view()->setData([
            'result' => $asteroidRepository->findBy(['is_hazardous' => $isHazardous])
        ]);

        return $this->handleView($view);
    }

    /**
     * @param Request $request
     *
     * @Rest\Route("/best-month/{hazardous}", name="best", defaults={"hazardous": false})
     *
     * @return Response
     */
    public function getBestMonthAction(Request $request): Response
    {
        $isHazardous = $request->attributes->get('hazardous');

        /** @var AsteroidRepository $asteroidRepository */
        $asteroidRepository = $this->getDoctrine()->getRepository(Asteroid::class);

        $view = $this->view()->setData([
            'result' => $asteroidRepository->findMonthWithMostAsteroids($isHazardous)
        ]);

        return $this->handleView($view);
    }
}
