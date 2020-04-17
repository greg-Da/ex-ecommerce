<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PanierRepository")
 */
class Panier
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="paniers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $bought_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ContenuPanier", inversedBy="panier")
     */
    private $contenuPanier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBoughtAt(): ?\DateTimeInterface
    {
        return $this->bought_at;
    }

    public function setBoughtAt(\DateTimeInterface $bought_at): self
    {
        $this->bought_at = $bought_at;

        return $this;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getContenuPanier(): ?ContenuPanier
    {
        return $this->contenuPanier;
    }

    public function setContenuPanier(?ContenuPanier $contenuPanier): self
    {
        $this->contenuPanier = $contenuPanier;

        return $this;
    }
}
