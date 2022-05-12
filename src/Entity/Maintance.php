<?php

namespace App\Entity;

use App\Repository\MaintanceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MaintanceRepository::class)
 */
class Maintance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Aircraft::class, inversedBy="maintances", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $aircraft;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $mt_form;

    /**
     * @ORM\Column(type="integer")
     */
    private $mt_nar;

    /**
     * @ORM\Column(type="date")
     */
    private $mt_exp_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $mt_res;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMtForm(): ?string
    {
        return $this->mt_form;
    }

    public function setMtForm(string $mt_form): self
    {
        $this->mt_form = $mt_form;

        return $this;
    }

    public function getMtNar(): ?int
    {
        return $this->mt_nar;
    }

    public function setMtNar(int $mt_nar): self
    {
        $this->mt_nar = $mt_nar;

        return $this;
    }

    public function getMtExpDate(): ?\DateTimeInterface
    {
        return $this->mt_exp_date;
    }

    public function setMtExpDate(\DateTimeInterface $mt_exp_date): self
    {
        $this->mt_exp_date = $mt_exp_date;

        return $this;
    }

    public function getMtRes(): ?int
    {
        return $this->mt_res;
    }

    public function setMtRes(int $mt_res): self
    {
        $this->mt_res = $mt_res;

        return $this;
    }
}
