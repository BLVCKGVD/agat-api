<?php

namespace App\Repository;

use App\Entity\EmailSubsription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmailSubsription|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmailSubsription|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmailSubsription[]    findAll()
 * @method EmailSubsription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailSubsriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmailSubsription::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(EmailSubsription $entity, bool $flush = true): void
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
    public function remove(EmailSubsription $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return EmailSubsription[] Returns an array of EmailSubsription objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmailSubsription
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
