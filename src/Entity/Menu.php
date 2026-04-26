<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(length: 255)]
    private string $theme;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $regime = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column]
    private ?int $minPersons = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $conditions = null;

    /**
     * @var Collection<int, Plat>
     */
    #[ORM\ManyToMany(targetEntity: Plat::class, inversedBy: 'menus')]
    private Collection $plats;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'menus')]
    private Collection $images;

    /**
     * @var Collection<int, Regime>
     */
    #[ORM\ManyToMany(targetEntity: Regime::class, inversedBy: 'menus')]
    private Collection $regimes;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getRegime(): ?string
    {
        return $this->regime;
    }

    public function setRegime(?string $regime): static
    {
        $this->regime = $regime;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getMinPersons(): ?int
    {
        return $this->minPersons;
    }

    public function setMinPersons(int $minPersons): static
    {
        $this->minPersons = $minPersons;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getConditions(): ?string
    {
        return $this->conditions;
    }

    public function setConditions(string $conditions): static
    {
        $this->conditions = $conditions;

        return $this;
    }

    /**
     * @return Collection<int, Plat>
     */
    public function getPlats(): Collection
    {
        return $this->plats;
    }

    public function addPlat(Plat $plat): static
    {
        if (!$this->plats->contains($plat)) {
            $this->plats->add($plat);
        }

        return $this;
    }

    public function removePlat(Plat $plat): static
    {
        $this->plats->removeElement($plat);

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setMenus($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getMenus() === $this) {
                $image->setMenus(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Regime>
     */
    public function getRegimes(): Collection
    {
        return $this->regimes;
    }

    public function addRegime(Regime $regime): static
    {
        if (!$this->regimes->contains($regime)) {
            $this->regimes->add($regime);
        }

        return $this;
    }

    public function removeRegime(Regime $regime): static
    {
        $this->regimes->removeElement($regime);

        return $this;
    }

    
}
