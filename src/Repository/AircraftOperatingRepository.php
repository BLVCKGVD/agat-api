<?php

namespace App\Repository;

use App\Entity\AircraftOperating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AircraftOperating|null find($id, $lockMode = null, $lockVersion = null)
 * @method AircraftOperating|null findOneBy(array $criteria, array $orderBy = null)
 * @method AircraftOperating[]    findAll()
 * @method AircraftOperating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AircraftOperatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AircraftOperating::class);
    }

    public function getLastAircraftOperating($aircraft)
    {
        return $this->findOneBy([
            'aircraft' => $aircraft
        ],[
            'id'=>'DESC'
        ]);
    }

    // /**
    //  * @return AircraftOperating[] Returns an array of AircraftOperating objects
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
    public function findOneBySomeField($value): ?AircraftOperating
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
