<?php

namespace App\Repository;

use App\Entity\TaskStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaskStatus>
 */
class TaskStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskStatus::class);
    }

    public function getByCode(string $code): TaskStatus
    {
        $taskStatus = $this->findOneBy(['code' => $code]);

        if ($taskStatus === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(TaskStatus::class, [$code]);
        }

        return $taskStatus;
    }

    //    /**
    //     * @return TaskStatus[] Returns an array of TaskStatus objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TaskStatus
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
