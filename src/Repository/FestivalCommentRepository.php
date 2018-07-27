<?php

namespace App\Repository;

use App\Entity\FestivalComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FestivalComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method FestivalComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method FestivalComment[]    findAll()
 * @method FestivalComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FestivalCommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FestivalComment::class);
    }

//    /**
//     * @return FestivalComment[] Returns an array of FestivalComment objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FestivalComment
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
