<?php

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    public function findByPoll($value)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.Poll','p')
            ->where('p.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Comments[] Returns an array of Comments objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comments
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
