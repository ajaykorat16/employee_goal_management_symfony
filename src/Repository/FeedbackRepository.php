<?php

namespace App\Repository;

use App\Entity\Feedback;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Feedback>
 */
class FeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feedback::class);
    }

    public function findByUserOrReporter(User $user): array
    {
        return $this->createQueryBuilder('f')
            ->where('f.user = :user')
            ->orWhere('f.reporter = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function getTotalCountsFeedbacks()
    {
        return $this->createQueryBuilder('f')
            ->select('count(f.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
