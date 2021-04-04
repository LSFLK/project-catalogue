<?php

namespace App\Repository;

use App\Entity\MoreInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MoreInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoreInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoreInfo[]    findAll()
 * @method MoreInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoreInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoreInfo::class);
    }

    // /**
    //  * @return MoreInfo[] Returns an array of MoreInfo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MoreInfo
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
