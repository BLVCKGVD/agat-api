<?php

namespace App\Entity;

use App\Repository\EmailSubsriptionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmailSubsriptionRepository::class)
 */
class EmailSubsription
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
    private $email;

    /**
     * @ORM\OneToOne(targetEntity=Users::class, inversedBy="emailSubsription", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $subUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSubUser(): ?Users
    {
        return $this->subUser;
    }

    public function setSubUser(Users $subUser): self
    {
        $this->subUser = $subUser;

        return $this;
    }

    public function __toString(): string
    {
        return $this->subUser->getFIO()." | ".$this->subUser->getlogin()." | ".$this->getEmail();
    }


}
