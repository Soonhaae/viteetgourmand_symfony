<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avis>
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

    /**
     * @return Avis[]
     */
    public function findForManagement(): array
    {
        return $this->createQueryBuilder('a')
            ->addSelect('u', 'c', 'm')
            ->join('a.user', 'u')
            ->join('a.commande', 'c')
            ->join('c.menu', 'm')
            ->orderBy('a.validated', 'ASC')
            ->addOrderBy('a.refused', 'ASC')
            ->addOrderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Avis[]
     */
    public function findPublishedForHomepage(int $limit = 4): array
    {
        return $this->createQueryBuilder('a')
            ->addSelect('c', 'm')
            ->join('a.commande', 'c')
            ->join('c.menu', 'm')
            ->andWhere('a.validated = true')
            ->andWhere('a.refused = false')
            ->andWhere('a.published = true')
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
