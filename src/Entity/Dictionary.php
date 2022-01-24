<?php

namespace App\Entity;

use App\Repository\DictionaryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DictionaryRepository::class)
 */
class Dictionary
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
    private $word;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $translate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $elquals;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $translated;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lang;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $langDest;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWord(): ?string
    {
        return $this->word;
    }

    public function setWord(string $word): self
    {
        $this->word = $word;

        return $this;
    }

    public function getTranslate(): ?string
    {
        return $this->translate;
    }

    public function setTranslate(string $translate): self
    {
        $this->translate = $translate;

        return $this;
    }

    public function getElquals(): ?string
    {
        return $this->elquals;
    }

    public function setElquals(string $elquals): self
    {
        $this->elquals = $elquals;

        return $this;
    }

    public function getTranslated(): ?string
    {
        return $this->translated;
    }

    public function setTranslated(?string $translated): self
    {
        $this->translated = $translated;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(?string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getLangDest(): ?string
    {
        return $this->langDest;
    }

    public function setLangDest(?string $langDest): self
    {
        $this->langDest = $langDest;

        return $this;
    }
}
