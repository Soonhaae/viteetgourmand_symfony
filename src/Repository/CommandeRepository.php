<?php

namespace App\Repository;

use App\Entity\Commande;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * @return Commande[]
     */
    public function findForUser(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->addSelect('m', 'h')
            ->join('c.menu', 'm')
            ->leftJoin('c.statusHistories', 'h')
            ->andWhere('c.user = :user')
            ->andWhere('c.hiddenFromCustomer = false')
            ->setParameter('user', $user)
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Commande[]
     */
    public function findForManagement(): array
    {
        return $this->createQueryBuilder('c')
            ->addSelect('m', 'u', 'h')
            ->join('c.menu', 'm')
            ->join('c.user', 'u')
            ->leftJoin('c.statusHistories', 'h')
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    //    public function findOneBySomeField($value): ?Commande
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
