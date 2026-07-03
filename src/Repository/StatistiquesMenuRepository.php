<?php

namespace App\Repository;

use App\Document\StatistiquesMenu;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

/**
 * @extends DocumentRepository<StatistiquesMenu>
 */
class StatistiquesMenuRepository extends DocumentRepository
{
    /**
     * Retourne le document mensuel d'un menu pour un mois et une année donnés.
     */
    public function findOneByMenuMois(int $menuId, int $mois, int $annee): ?StatistiquesMenu
    {
        return $this->findOneBy(['menuId' => $menuId, 'mois' => $mois, 'annee' => $annee]);
    }

    /**
     * Retourne tous les documents compris entre deux bornes (année/mois incluses).
     */
    public function findForRange(int $anneeMin, int $moisMin, int $anneeMax, int $moisMax): array
    {
        $docs = iterator_to_array(
            $this->createQueryBuilder()
                ->field('annee')->gte($anneeMin)->lte($anneeMax)
                ->sort('annee', 'asc')
                ->sort('mois', 'asc')
                ->getQuery()
                ->execute(),
            false,
        );

        // Le filtre Mongo couvre l'année; on ajuste ensuite les mois de début et de fin en PHP.
        return array_values(array_filter($docs, static function (StatistiquesMenu $doc) use ($anneeMin, $moisMin, $anneeMax, $moisMax): bool {
            $annee = $doc->getAnnee();
            $mois  = $doc->getMois();

            if ($annee === $anneeMin && $mois < $moisMin) {
                return false;
            }
            if ($annee === $anneeMax && $mois > $moisMax) {
                return false;
            }

            return true;
        }));
    }
}
