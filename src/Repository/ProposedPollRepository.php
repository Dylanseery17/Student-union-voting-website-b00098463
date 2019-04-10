<?php

namespace App\Repository;

use App\Entity\ProposedPoll;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProposedPoll|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProposedPoll|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProposedPoll[]    findAll()
 * @method ProposedPoll[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProposedPollRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProposedPoll::class);
    }

    // /**
    //  * @return ProposedPoll[] Returns an array of ProposedPoll objects
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
    public function findOneBySomeField($value): ?ProposedPoll
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
