<?php

namespace App\Repository;

use App\Entity\PartsOperating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PartsOperating|null find($id, $lockMode = null, $lockVersion = null)
 * @method PartsOperating|null findOneBy(array $criteria, array $orderBy = null)
 * @method PartsOperating[]    findAll()
 * @method PartsOperating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartsOperatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PartsOperating::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PartsOperating $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(PartsOperating $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getLastPartsOperating($part)
    {
        return $this->findOneBy([
            'part' => $part
        ],[
            'id'=>'DESC'
        ]);
    }

    // /**
    //  * @return PartsOperating[] Returns an array of PartsOperating objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PartsOperating
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
