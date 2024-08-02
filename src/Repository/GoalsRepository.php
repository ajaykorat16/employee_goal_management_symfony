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

    public function getTotalCountsGoals()
    {
        return $this->createQueryBuilder('g')
            ->select('count(g.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
