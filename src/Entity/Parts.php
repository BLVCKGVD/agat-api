<?php

namespace App\Entity;

use App\Repository\PartsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartsRepository::class)
 */
class Parts
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marking;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $factory_num;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $document;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $repair_place;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $repair_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $assigned_res;

    /**
     * @ORM\Column(type="date")
     */
    private $assigned_exp_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $overhaul_res;

    /**
     * @ORM\ManyToOne(targetEntity=Aircraft::class, inversedBy="parts")
     */
    private $aircraft;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=PartsOperating::class, mappedBy="part", orphanRemoval=true,cascade={"persist"})
     */
    private $partsOperatings;

    /**
     * @ORM\Column(type="date")
     */
    private $release_date;

    /**
     * @ORM\Column(type="date")
     */
    private $overhaul_exp_date;

    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $overhaul_years;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }


    public function __construct()
    {
        $this->partsOperatings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMarking(): ?string
    {
        return $this->marking;
    }

    public function setMarking(string $marking): self
    {
        $this->marking = $marking;

        return $this;
    }

    public function getFactoryNum(): ?string
    {
        return $this->factory_num;
    }

    public function setFactoryNum(string $factory_num): self
    {
        $this->factory_num = $factory_num;

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(string $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getRepairPlace(): ?string
    {
        return $this->repair_place;
    }

    public function setRepairPlace(?string $repair_place): self
    {
        $this->repair_place = $repair_place;

        return $this;
    }

    public function getRepairDate(): ?\DateTimeInterface
    {
        return $this->repair_date;
    }

    public function setRepairDate(?\DateTimeInterface $repair_date): self
    {
        $this->repair_date = $repair_date;

        return $this;
    }

    public function getAssignedRes(): ?int
    {
        return $this->assigned_res;
    }

    public function setAssignedRes(int $assigned_res): self
    {
        $this->assigned_res = $assigned_res;

        return $this;
    }

    public function getAssignedExpDate(): ?\DateTimeInterface
    {
        return $this->assigned_exp_date;
    }

    public function setAssignedExpDate(\DateTimeInterface $assigned_exp_date): self
    {
        $this->assigned_exp_date = $assigned_exp_date;

        return $this;
    }

    public function getOverhaulRes(): ?int
    {
        return $this->overhaul_res;
    }

    public function setOverhaulRes(int $overhaul_res): self
    {
        $this->overhaul_res = $overhaul_res;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, PartsOperating>
     */
    public function getPartsOperatings(): Collection
    {
        return $this->partsOperatings;
    }

    public function addPartsOperating(PartsOperating $partsOperating): self
    {
        if (!$this->partsOperatings->contains($partsOperating)) {
            $this->partsOperatings[] = $partsOperating;
            $partsOperating->setPart($this);
        }

        return $this;
    }

    public function removePartsOperating(PartsOperating $partsOperating): self
    {
        if ($this->partsOperatings->removeElement($partsOperating)) {
            // set the owning side to null (unless already changed)
            if ($partsOperating->getPart() === $this) {
                $partsOperating->setPart(null);
            }
        }

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->release_date;
    }

    public function setReleaseDate(\DateTimeInterface $release_date): self
    {
        $this->release_date = $release_date;

        return $this;
    }

    public function getOverhaulExpDate(): ?\DateTimeInterface
    {
        return $this->overhaul_exp_date;
    }

    public function setOverhaulExpDate(\DateTimeInterface $overhaul_exp_date): self
    {
        $this->overhaul_exp_date = $overhaul_exp_date;

        return $this;
    }

    public function getOverhaulYears(): ?int
    {
        return $this->overhaul_years;
    }

    public function setOverhaulYears(?int $overhaul_years): self
    {
        $this->overhaul_years = $overhaul_years;

        return $this;
    }

}
