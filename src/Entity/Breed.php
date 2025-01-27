<?php

namespace App\Entity;

use App\Repository\BreedRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BreedRepository::class)]
class Breed
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[Groups(['breed:read'])]
    private string $name;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: PetType::class, inversedBy: 'breeds')]
    #[ORM\JoinColumn(name: 'pet_type_name', referencedColumnName: 'name', nullable: false)]
    private PetType $petType;

    #[ORM\OneToMany(targetEntity: Pet::class, mappedBy: 'breed')]
    private Collection $pets;

    public function __construct()
    {
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

    public function getPetType(): PetType
    {
        return $this->petType;
    }

    public function setPetType(PetType $petType): static
    {
        $this->petType = $petType;

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
            $pet->setBreed($this);
        }

        return $this;
    }

    public function removePet(Pet $pet): static
    {
        if ($this->pets->removeElement($pet)) {
            if ($pet->getBreed() === $this) {
                $pet->setBreed(null);
            }
        }

        return $this;
    }
}
