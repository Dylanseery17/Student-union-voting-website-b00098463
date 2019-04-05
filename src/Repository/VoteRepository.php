<?php

namespace App\Repository;

use App\Entity\Vote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Vote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vote[]    findAll()
 * @method Vote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Vote::class);
    }

    public function findByPoll($value)
    {
        return $this->createQueryBuilder('v')
            ->leftJoin('v.Poll','p')
            ->where('p.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByPollByUser($poll , $user)
    {
        return $this->createQueryBuilder('v')
            ->select('v.Choice','v.Time')
            ->leftJoin('v.Poll','p')
            ->where('p.id = :poll')
            ->setParameter('poll', $poll)
            ->leftJoin('v.Voter','u')
            ->andwhere('u.username = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByUser($poll , $user)
    {
        return $this->createQueryBuilder('v')
            ->select('v.Choice')
            ->leftJoin('v.Poll','p')
            ->where('p.id = :val')
            ->setParameter('val', $poll)
            ->leftJoin('v.Voter','u')
            ->where('u.username = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByAns($value)
    {

        return $this->createQueryBuilder('v')
            ->select('v.Choice')
            ->leftJoin('v.Poll','p')
            ->where('p.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    public function countByAns($value , $values)
    {

        return $this->createQueryBuilder('v')
            ->leftJoin('v.Poll','p')
            ->where('p.id = :val')
            ->setParameter('val', $value)
            ->andwhere('v.Choice = :vals')
            ->setParameter('vals', $values)
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Vote
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
