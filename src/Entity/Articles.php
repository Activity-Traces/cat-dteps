<?php

namespace App\Entity;

use App\Repository\ArticlesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticlesRepository::class)
 */
class Articles
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $auteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $docSubtitle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre_revue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numero_parent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $href_parent;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $identifier;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $resume_fr;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $resume_en;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenu;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $biblio;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(?string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getDocSubtitle(): ?string
    {
        return $this->docSubtitle;
    }

    public function setDocSubtitle(?string $docSubtitle): self
    {
        $this->docSubtitle = $docSubtitle;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getTitreRevue(): ?string
    {
        return $this->titre_revue;
    }

    public function setTitreRevue(?string $titre_revue): self
    {
        $this->titre_revue = $titre_revue;

        return $this;
    }

    public function getPeriode(): ?int
    {
        return $this->periode;
    }

    public function setPeriode(?int $periode): self
    {
        $this->periode = $periode;

        return $this;
    }

    public function getNumeroParent(): ?int
    {
        return $this->numero_parent;
    }

    public function setNumeroParent(?int $numero_parent): self
    {
        $this->numero_parent = $numero_parent;

        return $this;
    }

    public function getHrefParent(): ?int
    {
        return $this->href_parent;
    }

    public function setHrefParent(?int $href_parent): self
    {
        $this->href_parent = $href_parent;

        return $this;
    }

    public function getIdentifier(): ?int
    {
        return $this->identifier;
    }

    public function setIdentifier(?int $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getResumeFr(): ?string
    {
        return $this->resume_fr;
    }

    public function setResumeFr(?string $resume_fr): self
    {
        $this->resume_fr = $resume_fr;

        return $this;
    }

    public function getResumeEn(): ?string
    {
        return $this->resume_en;
    }

    public function setResumeEn(?string $resume_en): self
    {
        $this->resume_en = $resume_en;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getBiblio(): ?string
    {
        return $this->biblio;
    }

    public function setBiblio(?string $biblio): self
    {
        $this->biblio = $biblio;

        return $this;
    }
}
