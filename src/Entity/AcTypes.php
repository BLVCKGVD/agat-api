<?php

namespace App\Entity;

use App\Repository\AcTypesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AcTypesRepository::class)
 */
class AcTypes
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
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $eng_count;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $category;

    /**
     * @ORM\Column(type="integer")
     */
    private $mr_res;

    /**
     * @ORM\Column(type="integer")
     */
    private $mr_month;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getEngCount(): ?int
    {
        return $this->eng_count;
    }

    public function setEngCount(int $eng_count): self
    {
        $this->eng_count = $eng_count;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getType();
    }

    public function getMrRes(): ?int
    {
        return $this->mr_res;
    }

    public function setMrRes(int $mr_res): self
    {
        $this->mr_res = $mr_res;

        return $this;
    }

    public function getMrMonth(): ?int
    {
        return $this->mr_month;
    }

    public function setMrMonth(int $mr_month): self
    {
        $this->mr_month = $mr_month;

        return $this;
    }
}
