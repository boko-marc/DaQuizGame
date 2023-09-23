<?php

namespace App\Repository;

use App\Entity\QuizEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizEntity>
 *
 * @method QuizEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizEntity[]    findAll()
 * @method QuizEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizEntity::class);
    }

//    /**
//     * @return QuizEntity[] Returns an array of QuizEntity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QuizEntity
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
