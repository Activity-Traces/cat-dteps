<?php

namespace App\Entity;

use App\Repository\CorpusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CorpusRepository::class)
 */
class Corpus
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $hasUser;

    /**
     * @ORM\Column(type="boolean")
     */
    private $verou;

    /**
     * @ORM\ManyToMany(targetEntity=Resource::class, mappedBy="hasCorpus")
     */
    private $hasResource;

   

    public function __construct()
    {
        $this->hasResource = new ArrayCollection();
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

    
     public function __toString()
    {
        return $this->nom;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    /**
     * @return Collection|Resource[]
     */
    public function getHasResource(): ?Collection
    {
        return $this->hasResource;
    }

    public function addHasResource(Resource $hasResource): self
    {
        if (!$this->hasResource->contains($hasResource)) {
            $this->hasResource[] = $hasResource;
            $hasResource->addHasCorpus($this);
        }

        return $this;
    }

    public function removeHasResource(Resource $hasResource): self
    {
        if ($this->hasResource->removeElement($hasResource)) {
            $hasResource->removeHasCorpus($this);
        }

        return $this;
    }

    
}
