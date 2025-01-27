<?php

namespace App\Entity;

use App\Repository\PetTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PetTypeRepository::class)]
class PetType
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Groups(['pet_type:read'])]
    private string $name;

    #[ORM\OneToMany(targetEntity: Breed::class, mappedBy: 'petType')]
    private Collection $breeds;

    #[ORM\OneToMany(targetEntity: Pet::class, mappedBy: 'type')]
    private Collection $pets;

    public function __construct()
    {
        $this->breeds = new ArrayCollection();
        $this->pets = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBreeds(): Collection
    {
        return $this->breeds;
    }

    public function addBreed(Breed $breed): static
    {
        if (!$this->breeds->contains($breed)) {
            $this->breeds->add($breed);
            $breed->setPetType($this);
        }

        return $this;
    }

    public function removeBreed(Breed $breed): static
    {
        if ($this->breeds->removeElement($breed)) {
            if ($breed->getPetType() === $this) {
                $breed->setPetType(null);
            }
        }

        return $this;
    }

    public function getPets(): Collection
    {
        return $this->pets;
    }

    public function addPet(Pet $pet): static
    {
        if (!$this->pets->contains($pet)) {
            $this->pets->add($pet);
            $pet->setType($this);
        }

        return $this;
    }

    public function removePet(Pet $pet): static
    {
        if ($this->pets->removeElement($pet)) {
            if ($pet->getType() === $this) {
                $pet->setType(null);
            }
        }

        return $this;
    }
}
