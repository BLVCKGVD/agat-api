<?php

namespace App\Entity;

use App\Repository\AircraftOperatingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AircraftOperatingRepository::class)
 */
class AircraftOperating
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalRes;

    /**
     * @ORM\Column(type="integer")
     */
    private $overhaulRes;

    /**
     * @ORM\ManyToOne(targetEntity=Aircraft::class, inversedBy="aircraftOperating", cascade={"persist"}))
     * @ORM\JoinColumn(nullable=false)
     */
    private $aircraft;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $addedBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalRes(): ?int
    {
        return $this->totalRes;
    }

    public function setTotalRes(int $totalRes): self
    {
        $this->totalRes = $totalRes;

        return $this;
    }

    public function getOverhaulRes(): ?int
    {
        return $this->overhaulRes;
    }

    public function setOverhaulRes(int $overhaulRes): self
    {
        $this->overhaulRes = $overhaulRes;

        return $this;
    }

    public function getAircraft(): ?Aircraft
    {
        return $this->aircraft;
    }

    public function setAircraft(?Aircraft $aircraft): self
    {
        $this->aircraft = $aircraft;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getAddedBy(): ?string
    {
        return $this->addedBy;
    }

    public function setAddedBy(string $addedBy): self
    {
        $this->addedBy = $addedBy;

        return $this;
    }


}
