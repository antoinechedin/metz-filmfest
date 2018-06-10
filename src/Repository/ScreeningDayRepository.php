<?php

namespace App\Repository;

use App\Entity\ScreeningDay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ScreeningDay|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScreeningDay|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScreeningDay[]    findAll()
 * @method ScreeningDay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScreeningDayRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ScreeningDay::class);
    }

//    /**
//     * @return ScreeningDay[] Returns an array of ScreeningDay objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ScreeningDay
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
