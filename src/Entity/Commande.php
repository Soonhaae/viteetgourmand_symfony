<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?int $nbPers = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $totalPrice = null;

    #[ORM\Column]
    private ?bool $conditionsAccepted = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $hiddenFromCustomer = false;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $prestationDate = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $prestationTime = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $deliveryAddress = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $deliveryPostalCode = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $deliveryCity = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $deliveryDetails = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Menu $menu = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getNbPers(): ?int
    {
        return $this->nbPers;
    }

    public function setNbPers(int $nbPers): static
    {
        $this->nbPers = $nbPers;

        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(string $totalPrice): static
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function isConditionsAccepted(): ?bool
    {
        return $this->conditionsAccepted;
    }

    public function setConditionsAccepted(bool $conditionsAccepted): static
    {
        $this->conditionsAccepted = $conditionsAccepted;

        return $this;
    }

    public function isHiddenFromCustomer(): ?bool
    {
        return $this->hiddenFromCustomer;
    }

    public function setHiddenFromCustomer(bool $hiddenFromCustomer): static
    {
        $this->hiddenFromCustomer = $hiddenFromCustomer;

        return $this;
    }

    public function getPrestationDate(): ?\DateTimeImmutable
    {
        return $this->prestationDate;
    }

    public function setPrestationDate(?\DateTimeImmutable $prestationDate): static
    {
        $this->prestationDate = $prestationDate;

        return $this;
    }

    public function getPrestationTime(): ?\DateTimeImmutable
    {
        return $this->prestationTime;
    }

    public function setPrestationTime(?\DateTimeImmutable $prestationTime): static
    {
        $this->prestationTime = $prestationTime;

        return $this;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(?string $deliveryAddress): static
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    public function getDeliveryPostalCode(): ?string
    {
        return $this->deliveryPostalCode;
    }

    public function setDeliveryPostalCode(?string $deliveryPostalCode): static
    {
        $this->deliveryPostalCode = $deliveryPostalCode;

        return $this;
    }

    public function getDeliveryCity(): ?string
    {
        return $this->deliveryCity;
    }

    public function setDeliveryCity(?string $deliveryCity): static
    {
        $this->deliveryCity = $deliveryCity;

        return $this;
    }

    public function getDeliveryDetails(): ?string
    {
        return $this->deliveryDetails;
    }

    public function setDeliveryDetails(?string $deliveryDetails): static
    {
        $this->deliveryDetails = $deliveryDetails;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): static
    {
        $this->menu = $menu;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
