<?php

namespace App\Entity;

use App\Repository\IndicatorRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IndicatorRepository::class)
 */
class Indicator
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
    private $nom;

    
    /**
     * @ORM\Column(type="datetime")
     */
    private $timeBegin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timeEnd;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $hasUser;

    /**
     * @ORM\ManyToOne(targetEntity=Evaluation::class)
     */
    private $hasEvaluation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTimeBegin(): ?\DateTimeInterface
    {
        return $this->timeBegin;
    }

    public function setTimeBegin(\DateTimeInterface $timeBegin): self
    {
        $this->timeBegin = $timeBegin;

        return $this;
    }

    public function getTimeEnd(): ?\DateTimeInterface
    {
        return $this->timeEnd;
    }

    public function setTimeEnd(\DateTimeInterface $timeEnd): self
    {
        $this->timeEnd = $timeEnd;

        return $this;
    }

    public function getHasUser(): ?User
    {
        return $this->hasUser;
    }

    public function setHasUser(?User $hasUser): self
    {
        $this->hasUser = $hasUser;

        return $this;
    }

    public function getHasEvaluation(): ?Evaluation
    {
        return $this->hasEvaluation;
    }

    public function setHasEvaluation(?Evaluation $hasEvaluation): self
    {
        $this->hasEvaluation = $hasEvaluation;

        return $this;
    }

   
}
