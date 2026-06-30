<?php

namespace App\Repository;

use App\Entity\Menu;
use App\Enum\MenuStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Menu>
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    /**
     * @param array<string, mixed> $filters
     * @return Menu[]
     */
    public function findByFilters(array $filters): array
    {
        $queryBuilder = $this->createQueryBuilder('m')
            ->leftJoin('m.regimes', 'r')
            ->leftJoin('m.images', 'i')
            ->addSelect('r')
            ->addSelect('i')
            ->orderBy('m.id', 'ASC')
        ;

        if (!empty($filters['publishedOnly'])) {
            $queryBuilder
                ->andWhere('m.status = :status')
                ->setParameter('status', MenuStatus::PUBLIE)
            ;
        }

        if (!empty($filters['theme'])) {
            $queryBuilder
                ->andWhere('m.theme = :theme')
                ->setParameter('theme', $filters['theme'])
            ;
        }

        if (!empty($filters['regime'])) {
            $queryBuilder
                ->andWhere('r.id = :regime')
                ->setParameter('regime', $filters['regime'])
            ;
        }

        if (!empty($filters['minPersons'])) {
            $queryBuilder
                ->andWhere('m.minPersons <= :minPersons')
                ->setParameter('minPersons', $filters['minPersons'])
            ;
        }

        if (!empty($filters['maxPrice'])) {
            $queryBuilder
                ->andWhere('m.price <= :maxPrice')
                ->setParameter('maxPrice', $filters['maxPrice'])
            ;
        }

        if (!empty($filters['minPrice'])) {
            $queryBuilder
                ->andWhere('m.price >= :minPrice')
                ->setParameter('minPrice', $filters['minPrice'])
            ;
        }

        if (!empty($filters['availableOnly'])) {
            $queryBuilder->andWhere('m.stockAvailable > 0');
        }

        return $queryBuilder
            ->getQuery()
            ->getResult()
        ;
    }

    //    public function findOneBySomeField($value): ?Menu
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
