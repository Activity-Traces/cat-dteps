<?php

namespace App\Entity;

use App\Repository\ResourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResourceRepository::class)
 */
class Resource
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
     * @ORM\Column(type="string", length=255)
     */



    private $resourceFile;

    /**
     * @ORM\ManyToMany(targetEntity=Corpus::class, inversedBy="hasResource")
     * @ORM\JoinColumn(nullable=true)
     */
    private $hasCorpus;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="resources")
     * @ORM\JoinColumn(nullable=true)
     */
    private $hasUser;

    /**
     * @ORM\Column(type="boolean")
     */
    private $verou;

    public function __construct()
    {
        $this->hasCorpus = new ArrayCollection();
    }

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

    public function getResourceFile(): ?string
    {
        return $this->resourceFile;
    }

    public function setResourceFile(string $resourceFile): self
    {
        $this->resourceFile = $resourceFile;

        return $this;
    }

    /**
     * @return Collection|Corpus[]
     */
    public function getHasCorpus(): Collection
    {
        return $this->hasCorpus;
    }

    public function addHasCorpus(Corpus $hasCorpu): self
    {
        if (!$this->hasCorpus->contains($hasCorpu)) {
            $this->hasCorpus[] = $hasCorpu;
        }

        return $this;
    }

    public function removeHasCorpus(Corpus $hasCorpu): self
    {
        $this->hasCorpus->removeElement($hasCorpu);

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

    public function getVerou(): ?bool
    {
        return $this->verou;
    }

    public function setVerou(bool $verou): self
    {
        $this->verou = $verou;

        return $this;
    }
}
