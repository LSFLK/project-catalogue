<?php

namespace App\Repository;

use App\Entity\TechnicalExpertise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TechnicalExpertise|null find($id, $lockMode = null, $lockVersion = null)
 * @method TechnicalExpertise|null findOneBy(array $criteria, array $orderBy = null)
 * @method TechnicalExpertise[]    findAll()
 * @method TechnicalExpertise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TechnicalExpertiseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TechnicalExpertise::class);
    }

    /**
     * @return TechnicalExpertise[] Returns an array of TechnicalExpertise objects
     */
    public function findAllOrderByName()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getTechnicalExpertiseCount() {
        return $this->createQueryBuilder('t')
                    ->select('count(t.id)')
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    // /**
    //  * @return TechnicalExpertise[] Returns an array of TechnicalExpertise objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TechnicalExpertise
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
