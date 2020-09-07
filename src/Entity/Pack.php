<?php

namespace App\Entity;

use App\Repository\PackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PackRepository::class)
 */
class Pack
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $full_name;

    /**
     * @ORM\OneToMany(targetEntity=Model::class, mappedBy="pack")
     */
    private $models;

    /**
     * @ORM\OneToMany(targetEntity=Kit::class, mappedBy="pack")
     */
    private $kits;

    public function __construct()
    {
        $this->models = new ArrayCollection();
        $this->kits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): self
    {
        $this->full_name = $full_name;

        return $this;
    }

    /**
     * @return Collection|Model[]
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    public function addModel(Model $model): self
    {
        if (!$this->models->contains($model)) {
            $this->models[] = $model;
            $model->setPack($this);
        }

        return $this;
    }

    public function removeModel(Model $model): self
    {
        if ($this->models->contains($model)) {
            $this->models->removeElement($model);
            // set the owning side to null (unless already changed)
            if ($model->getPack() === $this) {
                $model->setPack(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Kit[]
     */
    public function getKits(): Collection
    {
        return $this->kits;
    }

    public function addKit(Kit $kit): self
    {
        if (!$this->kits->contains($kit)) {
            $this->kits[] = $kit;
            $kit->setPack($this);
        }

        return $this;
    }

    public function removeKit(Kit $kit): self
    {
        if ($this->kits->contains($kit)) {
            $this->kits->removeElement($kit);
            // set the owning side to null (unless already changed)
            if ($kit->getPack() === $this) {
                $kit->setPack(null);
            }
        }

        return $this;
    }
}
