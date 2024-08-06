<?php

namespace App\Repository;

use App\Entity\Goals;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Goals>
 */
class GoalsRepository extends ServiceEntityRepository
{
    use BaseRepository;

    public const PAGE_SIZE = 20;
    public const OFFSET = 0;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Goals::class);
    }
    
    public function getAllGoals($user) :QueryBuilder
    {
        $qb = $this->createQueryBuilder('g');

        if($user){
            $qb->join('g.user','user')
                ->Where('user.id LIKE :id')
                ->setParameter('id','%' .$user->getId(). '%');
        }

        return $qb;
    }

    public function getGoals($user, ?int $count = null, int $offset = 0): ArrayCollection
    {
        $qb = $this->getAllGoals($user);

        $qb->setFirstResult($offset);

        if ($count !== null) {
            $qb->setMaxResults($count);
        }

        return $this->paginateResults($qb, true, false);
    }

    public function getTotalCountsGoals($user)
    {
        return $this->createQueryBuilder('g')
            ->select('count(g.id)')
            ->join('g.user','user')
            ->Where('user.id LIKE :id')
            ->setParameter('id','%' .$user->getId(). '%')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
