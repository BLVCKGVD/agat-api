<?php

namespace App\Entity;

use App\Repository\UserLogsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserLogsRepository::class)
 */
class UserLogs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="relatedUser")
     * @ORM\JoinColumn(nullable=false)
     */
    private $employee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $action;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Aircraft::class, inversedBy="userLogs")
     */
    private $aircraft;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmployee(): ?Users
    {
        return $this->employee;
    }

    public function setEmployee(?Users $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
}
