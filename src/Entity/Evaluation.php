<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvaluationRepository::class)
 */
class Evaluation
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
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $regles;



    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $hasUser;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRegles(): ?string
    {
        return $this->regles;
    }

    public function setRegles(string $regles): self
    {
        $this->regles = $regles;

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
}
