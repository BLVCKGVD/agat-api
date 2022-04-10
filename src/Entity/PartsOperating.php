<?php

namespace App\Entity;

use App\Repository\PartsOperatingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PartsOperatingRepository::class)
 */
class PartsOperating
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
     * @ORM\Column(type="datetime")
     */
    private $createDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $addedBy;

    /**
     * @ORM\ManyToOne(targetEntity=Parts::class, inversedBy="partsOperatings", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $part;

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

    public function getPart(): ?Parts
    {
        return $this->part;
    }

    public function setPart(?Parts $part): self
    {
        $this->part = $part;

        return $this;
    }
}
