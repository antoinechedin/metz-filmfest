<?php

namespace App\Repository;

use App\Entity\MovieComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MovieComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method MovieComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method MovieComment[]    findAll()
 * @method MovieComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieCommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MovieComment::class);
    }

//    /**
//     * @return MovieComment[] Returns an array of MovieComment objects
//     */
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
    public function findOneBySomeField($value): ?MovieComment
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
