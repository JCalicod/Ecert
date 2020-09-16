<?php

namespace App\Entity;

use App\Repository\KitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=KitRepository::class)
 */
class Kit implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $serial_number;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $random_key;

    /**
     * @ORM\Column(type="date")
     */
    private $creation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cli;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $state;

    /**
     * @ORM\ManyToOne(targetEntity=Pack::class, inversedBy="kits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pack;

    /**
     * @ORM\OneToMany(targetEntity=KitValue::class, mappedBy="kit")
     */
    private $kitValues;

    public function __construct()
    {
        $this->kitValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serial_number;
    }

    public function setSerialNumber(string $serial_number): self
    {
        $this->serial_number = $serial_number;

        return $this;
    }

    public function getRandomKey(): ?string
    {
        return $this->random_key;
    }

    public function setRandomKey(string $random_key): self
    {
        $this->random_key = $random_key;

        return $this;
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    public function setCreation(\DateTimeInterface $creation): self
    {
        $this->creation = $creation;

        return $this;
    }

    public function getCli(): ?string
    {
        return $this->cli;
    }

    public function setCli(string $cli): self
    {
        $this->cli = $cli;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): self
    {
        $this->pack = $pack;

        return $this;
    }

    /**
     * @return Collection|KitValue[]
     */
    public function getKitValues(): Collection
    {
        return $this->kitValues;
    }

    public function addKitValue(KitValue $kitValue): self
    {
        if (!$this->kitValues->contains($kitValue)) {
            $this->kitValues[] = $kitValue;
            $kitValue->setKit($this);
        }

        return $this;
    }

    public function removeKitValue(KitValue $kitValue): self
    {
        if ($this->kitValues->contains($kitValue)) {
            $this->kitValues->removeElement($kitValue);
            // set the owning side to null (unless already changed)
            if ($kitValue->getKit() === $this) {
                $kitValue->setKit(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        $roles[] = 'ROLE_ECERT';

        return array_unique($roles);
    }

    public function getPassword()
    {
        return (string) $this->random_key;
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
        return (string) $this->serial_number;
    }

    public function eraseCredentials()
    {
    }
}
