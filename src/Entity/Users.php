<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class Users
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
    private $FIO;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $login;

    /**
     * @ORM\OneToMany(targetEntity=UserLogs::class, mappedBy="employee", orphanRemoval=true)
     */
    private $relatedUser;

    public function __construct()
    {
        $this->relatedUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFIO(): ?string
    {
        return $this->FIO;
    }

    public function setFIO(string $FIO): self
    {
        $this->FIO = $FIO;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return Collection<int, UserLogs>
     */
    public function getRelatedUser(): Collection
    {
        return $this->relatedUser;
    }

    public function addRelatedUser(UserLogs $relatedUser): self
    {
        if (!$this->relatedUser->contains($relatedUser)) {
            $this->relatedUser[] = $relatedUser;
            $relatedUser->setEmployee($this);
        }

        return $this;
    }

    public function removeRelatedUser(UserLogs $relatedUser): self
    {
        if ($this->relatedUser->removeElement($relatedUser)) {
            // set the owning side to null (unless already changed)
            if ($relatedUser->getEmployee() === $this) {
                $relatedUser->setEmployee(null);
            }
        }

        return $this;
    }
}
