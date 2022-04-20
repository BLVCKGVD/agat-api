<?php

namespace App\Entity;

use App\Repository\FavouritesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FavouritesRepository::class)
 */
class Favourites
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="favourites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUser;

    /**
     * @ORM\ManyToOne(targetEntity=Aircraft::class, inversedBy="favourites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idAircraft;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?Users
    {
        return $this->idUser;
    }

    public function setIdUser(?Users $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdAircraft(): ?Aircraft
    {
        return $this->idAircraft;
    }

    public function setIdAircraft(?Aircraft $idAircraft): self
    {
        $this->idAircraft = $idAircraft;

        return $this;
    }
}
