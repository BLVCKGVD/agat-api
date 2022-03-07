<?php

namespace App\Entity;

use App\Repository\AircraftRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AircraftRepository::class)
 */
class Aircraft
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $board_num;

    /**
     * @ORM\Column(type="string", length=12)
     */
    private $factory_num;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $serial_num;

    /**
     * @ORM\Column(type="date")
     */
    private $release_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $last_repair_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $repairs_count;

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
     * @ORM\Column(type="date")
     */
    private $overhaul_exp_date;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $res_renew_num;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $operator;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $rent_doc_num;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $rent_doc_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $rent_exp_date;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $vsu_num;

    /**
     * @ORM\Column(type="integer")
     */
    private $construction_weight;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $centering;

    /**
     * @ORM\Column(type="integer")
     */
    private $max_takeoff_weight;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fin_periodic_mt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mt_made_by;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $lg_sert;

    /**
     * @ORM\Column(type="date")
     */
    private $lg_date;

    /**
     * @ORM\Column(type="date")
     */
    private $lg_exp_date;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $reg_sert;

    /**
     * @ORM\Column(type="date")
     */
    private $reg_sert_date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ac_type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ac_category;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $extension_reason;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $last_arz;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $arz_appointment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $factory_made_by;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $noise_sert_num;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $noise_sert_date;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $noise_sert_exp_date;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $max_pv;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $max_gp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $special_marks;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lg_given;

    /**
     * @ORM\OneToMany(targetEntity=AircraftOperating::class, mappedBy="aircraft", orphanRemoval=true,cascade={"persist"}))
     */
    private $aircraftOperating;

    public function __construct()
    {
        $this->aircraftOperating = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoardNum(): ?string
    {
        return $this->board_num;
    }

    public function setBoardNum(string $board_num): self
    {
        $this->board_num = $board_num;

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

    public function getSerialNum(): ?string
    {
        return $this->serial_num;
    }

    public function setSerialNum(?string $serial_num): self
    {
        $this->serial_num = $serial_num;

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

    public function getLastRepairDate(): ?\DateTimeInterface
    {
        return $this->last_repair_date;
    }

    public function setLastRepairDate(?\DateTimeInterface $last_repair_date): self
    {
        $this->last_repair_date = $last_repair_date;

        return $this;
    }

    public function getRepairsCount(): ?int
    {
        return $this->repairs_count;
    }

    public function setRepairsCount(int $repairs_count): self
    {
        $this->repairs_count = $repairs_count;

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

    public function setAssignedExpDate(?\DateTimeInterface $assigned_exp_date): self
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

    public function getOverhaulExpDate(): ?\DateTimeInterface
    {
        return $this->overhaul_exp_date;
    }

    public function setOverhaulExpDate(?\DateTimeInterface $overhaul_exp_date): self
    {
        $this->overhaul_exp_date = $overhaul_exp_date;

        return $this;
    }

    public function getResRenewNum(): ?string
    {
        return $this->res_renew_num;
    }

    public function setResRenewNum(?string $res_renew_num): self
    {
        $this->res_renew_num = $res_renew_num;

        return $this;
    }

    public function getOperator(): ?string
    {
        return $this->operator;
    }

    public function setOperator(?string $operator): self
    {
        $this->operator = $operator;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getRentDocNum(): ?string
    {
        return $this->rent_doc_num;
    }

    public function setRentDocNum(?string $rent_doc_num): self
    {
        $this->rent_doc_num = $rent_doc_num;

        return $this;
    }

    public function getRentDocDate(): ?\DateTimeInterface
    {
        return $this->rent_doc_date;
    }

    public function setRentDocDate(?\DateTimeInterface $rent_doc_date): self
    {
        $this->rent_doc_date = $rent_doc_date;

        return $this;
    }

    public function getRentExpDate(): ?\DateTimeInterface
    {
        return $this->rent_exp_date;
    }

    public function setRentExpDate(?\DateTimeInterface $rent_exp_date): self
    {
        $this->rent_exp_date = $rent_exp_date;

        return $this;
    }

    public function getVsuNum(): ?string
    {
        return $this->vsu_num;
    }

    public function setVsuNum(?string $vsu_num): self
    {
        $this->vsu_num = $vsu_num;

        return $this;
    }

    public function getConstructionWeight(): ?int
    {
        return $this->construction_weight;
    }

    public function setConstructionWeight(int $construction_weight): self
    {
        $this->construction_weight = $construction_weight;

        return $this;
    }

    public function getCentering(): ?string
    {
        return $this->centering;
    }

    public function setCentering(string $centering): self
    {
        $this->centering = $centering;

        return $this;
    }

    public function getMaxTakeoffWeight(): ?int
    {
        return $this->max_takeoff_weight;
    }

    public function setMaxTakeoffWeight(int $max_takeoff_weight): self
    {
        $this->max_takeoff_weight = $max_takeoff_weight;

        return $this;
    }

    public function getFinPeriodicMt(): ?string
    {
        return $this->fin_periodic_mt;
    }

    public function setFinPeriodicMt(?string $fin_periodic_mt): self
    {
        $this->fin_periodic_mt = $fin_periodic_mt;

        return $this;
    }

    public function getMtMadeBy(): ?string
    {
        return $this->mt_made_by;
    }

    public function setMtMadeBy(?string $mt_made_by): self
    {
        $this->mt_made_by = $mt_made_by;

        return $this;
    }

    public function getLgSert(): ?string
    {
        return $this->lg_sert;
    }

    public function setLgSert(string $lg_sert): self
    {
        $this->lg_sert = $lg_sert;

        return $this;
    }

    public function getLgDate(): ?\DateTimeInterface
    {
        return $this->lg_date;
    }

    public function setLgDate(\DateTimeInterface $lg_date): self
    {
        $this->lg_date = $lg_date;

        return $this;
    }

    public function getLgExpDate(): ?\DateTimeInterface
    {
        return $this->lg_exp_date;
    }

    public function setLgExpDate(\DateTimeInterface $lg_exp_date): self
    {
        $this->lg_exp_date = $lg_exp_date;

        return $this;
    }

    public function getRegSert(): ?string
    {
        return $this->reg_sert;
    }

    public function setRegSert(string $reg_sert): self
    {
        $this->reg_sert = $reg_sert;

        return $this;
    }

    public function getRegSertDate(): ?\DateTimeInterface
    {
        return $this->reg_sert_date;
    }

    public function setRegSertDate(\DateTimeInterface $reg_sert_date): self
    {
        $this->reg_sert_date = $reg_sert_date;

        return $this;
    }

    public function getAcType(): ?string
    {
        return $this->ac_type;
    }

    public function setAcType(string $ac_type): self
    {
        $this->ac_type = $ac_type;

        return $this;
    }

    public function getAcCategory(): ?string
    {
        return $this->ac_category;
    }

    public function setAcCategory(string $ac_category): self
    {
        $this->ac_category = $ac_category;

        return $this;
    }

    public function getExtensionReason(): ?string
    {
        return $this->extension_reason;
    }

    public function setExtensionReason(?string $extension_reason): self
    {
        $this->extension_reason = $extension_reason;

        return $this;
    }

    public function getLastArz(): ?\DateTimeInterface
    {
        return $this->last_arz;
    }

    public function setLastArz(?\DateTimeInterface $last_arz): self
    {
        $this->last_arz = $last_arz;

        return $this;
    }

    public function getArzAppointment(): ?string
    {
        return $this->arz_appointment;
    }

    public function setArzAppointment(?string $arz_appointment): self
    {
        $this->arz_appointment = $arz_appointment;

        return $this;
    }

    public function getFactoryMadeBy(): ?string
    {
        return $this->factory_made_by;
    }

    public function setFactoryMadeBy(string $factory_made_by): self
    {
        $this->factory_made_by = $factory_made_by;

        return $this;
    }

    public function getNoiseSertNum(): ?string
    {
        return $this->noise_sert_num;
    }

    public function setNoiseSertNum(?string $noise_sert_num): self
    {
        $this->noise_sert_num = $noise_sert_num;

        return $this;
    }

    public function getNoiseSertDate(): ?\DateTimeInterface
    {
        return $this->noise_sert_date;
    }

    public function setNoiseSertDate(?\DateTimeInterface $noise_sert_date): self
    {
        $this->noise_sert_date = $noise_sert_date;

        return $this;
    }

    public function getNoiseSertExpDate(): ?\DateTimeInterface
    {
        return $this->noise_sert_exp_date;
    }

    public function setNoiseSertExpDate(?\DateTimeInterface $noise_sert_exp_date): self
    {
        $this->noise_sert_exp_date = $noise_sert_exp_date;

        return $this;
    }

    public function getMaxPv(): ?string
    {
        return $this->max_pv;
    }

    public function setMaxPv(?string $max_pv): self
    {
        $this->max_pv = $max_pv;

        return $this;
    }

    public function getMaxGp(): ?string
    {
        return $this->max_gp;
    }

    public function setMaxGp(?string $max_gp): self
    {
        $this->max_gp = $max_gp;

        return $this;
    }

    public function getSpecialMarks(): ?string
    {
        return $this->special_marks;
    }

    public function setSpecialMarks(?string $special_marks): self
    {
        $this->special_marks = $special_marks;

        return $this;
    }


    public function getLgGiven(): ?string
    {
        return $this->lg_given;
    }

    public function setLgGiven(string $lg_given): self
    {
        $this->lg_given = $lg_given;

        return $this;
    }

    /**
     * @return Collection|AircraftOperating[]
     */
    public function getAircraftOperating(): Collection
    {
        return $this->aircraftOperating;
    }

    public function addAircraftOperating(AircraftOperating $aircraftOperating): self
    {
        if (!$this->aircraftOperating->contains($aircraftOperating)) {
            $this->aircraftOperating[] = $aircraftOperating;
            $aircraftOperating->setAircraft($this);
        }

        return $this;
    }

    public function removeAircraftOperating(AircraftOperating $aircraftOperating): self
    {
        if ($this->aircraftOperating->removeElement($aircraftOperating)) {
            // set the owning side to null (unless already changed)
            if ($aircraftOperating->getAircraft() === $this) {
                $aircraftOperating->setAircraft(null);
            }
        }

        return $this;
    }
}
