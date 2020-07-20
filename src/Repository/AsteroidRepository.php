<?php

namespace App\Repository;

use App\Entity\Asteroid;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * Class AsteroidRepository
 *
 * @method Asteroid|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asteroid|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asteroid[]    findAll()
 * @method Asteroid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AsteroidRepository extends ServiceEntityRepository
{
    /**
     * AsteroidRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asteroid::class);
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return Asteroid[]
     */
    public function findAllAsteroidWithPagination(int $limit, int $offset): iterable
    {
        return $this->findBy([], [], $limit, $offset);
    }

    /**
     * @param bool $isHazardous
     *
     * @return int
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findMonthWithMostAsteroids(bool $isHazardous): int
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "select MONTH(date) as month from asteroid
            where is_hazardous = ?
            GROUP BY MONTH(date)
            ORDER BY COUNT(*) DESC
            LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $isHazardous);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
}
