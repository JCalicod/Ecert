<?php

namespace App\Entity;

use App\Repository\KitValueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=KitValueRepository::class)
 */
class KitValue
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Kit::class, inversedBy="kitValues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $kit;

    /**
     * @ORM\ManyToOne(targetEntity=Test::class, inversedBy="kitValues")
     * @ORM\JoinColumn(nullable=false)
     */
    private $test;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKit(): ?Kit
    {
        return $this->kit;
    }

    public function setKit(?Kit $kit): self
    {
        $this->kit = $kit;

        return $this;
    }

    public function getTest(): ?Test
    {
        return $this->test;
    }

    public function setTest(?Test $test): self
    {
        $this->test = $test;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
