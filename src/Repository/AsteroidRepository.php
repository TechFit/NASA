<?php

namespace App\Repository;

use App\Entity\Asteroid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Asteroid|null find($id, $lockMode = null, $lockVersion = null)
 * @method Asteroid|null findOneBy(array $criteria, array $orderBy = null)
 * @method Asteroid[]    findAll()
 * @method Asteroid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AsteroidRepository extends ServiceEntityRepository
{
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
    // /**
    //  * @return Asteroid[] Returns an array of Asteroid objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Asteriod
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
