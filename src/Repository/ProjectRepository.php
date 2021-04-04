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

        if($programming_language = $requestQuery->get('language')) {
            $queryBuilder->innerJoin('p.programming_language', 'ppl')
                         ->andWhere('ppl.id = :pl')
                         ->setParameter('pl', $programming_language);
        }

        return $queryBuilder->orderBy('p.name', 'ASC')
                            ->getQuery()
                            ->getResult()
        ;
    }

    /**
    * @return Project[] Returns an array of Project objects
    */
    public function searchByProjectName($name)
    {
        return $this->createQueryBuilder('p')
                    ->where('p.name LIKE :n')
                    ->setParameter('n', '%'.$name.'%')
                    ->orderBy('p.name', 'ASC')
                    ->getQuery()
                    ->getResult()
        ;
    }

    public function getProjectsCount() {
        return $this->createQueryBuilder('p')
                    ->select('count(p.id)')
                    ->getQuery()
                    ->getSingleScalarResult();
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
