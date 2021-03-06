<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TestRepository::class)
 */
class Test
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Card::class, inversedBy="tests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $card;

    /**
     * @ORM\Column(type="boolean")
     */
    private $measure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $test_result;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $unit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $expected_result;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $field_name;

    /**
     * @ORM\OneToMany(targetEntity=KitValue::class, mappedBy="test")
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

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): self
    {
        $this->card = $card;

        return $this;
    }

    public function getMeasure(): ?bool
    {
        return $this->measure;
    }

    public function setMeasure(bool $measure): self
    {
        $this->measure = $measure;

        return $this;
    }

    public function getTestResult(): ?string
    {
        return $this->test_result;
    }

    public function setTestResult(string $test_result): self
    {
        $this->test_result = $test_result;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getExpectedResult(): ?string
    {
        return $this->expected_result;
    }

    public function setExpectedResult(string $expected_result): self
    {
        $this->expected_result = $expected_result;

        return $this;
    }

    public function getFieldName(): ?string
    {
        return $this->field_name;
    }

    public function setFieldName(string $field_name): self
    {
        $this->field_name = $field_name;

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
            $kitValue->setTest($this);
        }

        return $this;
    }

    public function removeKitValue(KitValue $kitValue): self
    {
        if ($this->kitValues->contains($kitValue)) {
            $this->kitValues->removeElement($kitValue);
            // set the owning side to null (unless already changed)
            if ($kitValue->getTest() === $this) {
                $kitValue->setTest(null);
            }
        }

        return $this;
    }
}
