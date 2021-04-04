<?php

namespace App\Repository;

use App\Entity\GitRepo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GitRepo|null find($id, $lockMode = null, $lockVersion = null)
 * @method GitRepo|null findOneBy(array $criteria, array $orderBy = null)
 * @method GitRepo[]    findAll()
 * @method GitRepo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GitRepoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GitRepo::class);
    }

    // /**
    //  * @return GitRepo[] Returns an array of GitRepo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GitRepo
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
