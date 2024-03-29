<?php

namespace App\Repository;

use App\Entity\Poll;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Poll|null find($id, $lockMode = null, $lockVersion = null)
 * @method Poll|null findOneBy(array $criteria, array $orderBy = null)
 * @method Poll[]    findAll()
 * @method Poll[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PollRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Poll::class);
    }

    public function findByID($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByEndDate()
    {
        $date = new \DateTime();

        return $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.enddate', 'ASC')
            ->andWhere('p.enddate >= :date')
            ->setParameter('date', $date)
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByChoice($choice)
    {
        $date = new \DateTime();

        return $this->createQueryBuilder('p')
            ->select('p.Options')
            ->andWhere('p.id >= :id')
            ->setParameter('id', $choice)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByExpired()
    {
        $date = new \DateTime();

        return $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.enddate', 'ASC')
            ->andWhere('p.enddate <= :date')
            ->setParameter('date', $date)
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByExpiredID($value)
    {
        $date = new \DateTime();

        return $this->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.enddate', 'ASC')
            ->andWhere('p.enddate <= :date')
            ->andWhere('p.id = :val')
            ->setParameter('val', $value)
            ->setParameter('date', $date)
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Poll[] Returns an array of Poll objects
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
    public function findOneBySomeField($value): ?Poll
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
