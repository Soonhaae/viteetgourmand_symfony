<?php

namespace App\Document;

use App\Repository\StatistiquesMenuRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * Agrégat mensuel par menu.
 *
 * Un document = un menu + un mois/année.
 */
#[ODM\Document(collection: 'statistiques_menu', repositoryClass: StatistiquesMenuRepository::class)]
#[ODM\Index(keys: ['menuId' => 'asc', 'annee' => 'desc', 'mois' => 'desc'])]
class StatistiquesMenu
{
    #[ODM\Id]
    private ?string $id = null;

    /** Identifiant de l'entité Menu (MySQL). */
    #[ODM\Field(type: 'int')]
    private int $menuId;

    /** Titre du menu (dénormalisé pour éviter une jointure MySQL). */
    #[ODM\Field(type: 'string')]
    private string $menuTitle;

    /** Mois de la commande (1–12). */
    #[ODM\Field(type: 'int')]
    private int $mois;

    /** Année de la commande. */
    #[ODM\Field(type: 'int')]
    private int $annee;

    /** Nombre de commandes pour ce menu sur ce mois. */
    #[ODM\Field(type: 'int')]
    private int $nbCommandes = 0;

    /** Chiffre d'affaires pour ce menu sur ce mois (en euros). */
    #[ODM\Field(type: 'float')]
    private float $chiffreAffaires = 0.0;

    public function __construct(int $menuId, string $menuTitle, \DateTimeImmutable $date)
    {
        $this->menuId    = $menuId;
        $this->menuTitle = $menuTitle;
        $this->mois      = (int) $date->format('n');
        $this->annee     = (int) $date->format('Y');
    }

    public function getId(): ?string { return $this->id; }
    public function getMenuId(): int { return $this->menuId; }
    public function getMenuTitle(): string { return $this->menuTitle; }
    public function getMois(): int { return $this->mois; }
    public function getAnnee(): int { return $this->annee; }
    public function getNbCommandes(): int { return $this->nbCommandes; }
    public function getChiffreAffaires(): float { return $this->chiffreAffaires; }

    public function setMenuTitle(string $menuTitle): static
    {
        $this->menuTitle = $menuTitle;
        return $this;
    }

    public function setNbCommandes(int $nbCommandes): static
    {
        $this->nbCommandes = $nbCommandes;
        return $this;
    }

    public function setChiffreAffaires(float $chiffreAffaires): static
    {
        $this->chiffreAffaires = $chiffreAffaires;
        return $this;
    }
}
