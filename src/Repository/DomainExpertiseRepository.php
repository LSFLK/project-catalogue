<?php

namespace App\Repository;

use App\Entity\DomainExpertise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DomainExpertise|null find($id, $lockMode = null, $lockVersion = null)
 * @method DomainExpertise|null findOneBy(array $criteria, array $orderBy = null)
 * @method DomainExpertise[]    findAll()
 * @method DomainExpertise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DomainExpertiseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DomainExpertise::class);
    }

    /**
     * @return DomainExpertise[] Returns an array of DomainExpertise objects
     */
    public function findAllOrderByName()
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getDomainExpertiseCount() {
        return $this->createQueryBuilder('d')
                    ->select('count(d.id)')
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    // /**
    //  * @return DomainExpertise[] Returns an array of DomainExpertise objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DomainExpertise
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
