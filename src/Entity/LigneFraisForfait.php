<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LigneFraisForfaitRepository")
 */
class LigneFraisForfait
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mois;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Visiteur", inversedBy="ligneFraisForfaits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fkVisiteur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FraisForfait", inversedBy="ligneFraisForfaits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fkFraisForfait;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(string $mois): self
    {
        $this->mois = $mois;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getFkVisiteur(): ?Visiteur
    {
        return $this->fkVisiteur;
    }

    public function setFkVisiteur(?Visiteur $fkVisiteur): self
    {
        $this->fkVisiteur = $fkVisiteur;

        return $this;
    }

    public function getFkFraisForfait(): ?FraisForfait
    {
        return $this->fkFraisForfait;
    }

    public function setFkFraisForfait(?FraisForfait $fkFraisForfait): self
    {
        $this->fkFraisForfait = $fkFraisForfait;

        return $this;
    }
}
