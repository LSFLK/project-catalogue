<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
    * @return Project[] Returns an array of Project objects
    */
    public function findByRequestQueryParams($requestQuery)
    {
        $queryBuilder = $this->createQueryBuilder('p');

        if($domain_expertise = $requestQuery->get('domain')) {
            $queryBuilder->andWhere('p.domain_expertise = :de')
                         ->setParameter('de', $domain_expertise);
        }

        if($technical_expertise = $requestQuery->get('technical')) {
            $queryBuilder->andWhere('p.technical_expertise = :te')
                         ->setParameter('te', $technical_expertise);
        }

        // if($programming_language = $requestQuery->get('language')) {
        //     $queryBuilder->andWhere('p.programming_language = :te')
        //                  ->setParameter('te', $programming_language);
        // }

        return $queryBuilder->orderBy('p.id', 'ASC')
                            ->setMaxResults(10)
                            ->getQuery()
                            ->getResult()
        ;
    }

    // /**
    //  * @return Project[] Returns an array of Project objects
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
    public function findOneBySomeField($value): ?Project
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
