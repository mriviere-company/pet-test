<?php

namespace App\Entity;

use App\Repository\PetRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PetRepository::class)]
class Pet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: PetType::class, inversedBy: 'pets')]
    #[ORM\JoinColumn(name: 'pet_type_name', referencedColumnName: 'name', nullable: false)]
    private PetType $type;

    #[ORM\ManyToOne(targetEntity: Breed::class, inversedBy: 'pets')]
    #[ORM\JoinColumn(name: 'pet_breed_name', referencedColumnName: 'name', nullable: false)]
    #[ORM\JoinColumn(name: 'pet_type_name', referencedColumnName: 'pet_type_name', nullable: false)]
    private Breed $breed;

    #[ORM\Column(nullable: true)]
    #[Assert\Type(DateTimeImmutable::class)]
    private ?DateTimeImmutable $birthDate = null;

    #[ORM\Column(length: 255)]
    private string $gender;

    #[ORM\Column]
    private bool $isDangerous;

    public function getId(): int
    {
        return $this->id;
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

    public function getType(): PetType
    {
        return $this->type;
    }

    public function setType(PetType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBreed(): Breed
    {
        return $this->breed;
    }

    public function setBreed(Breed $breed): static
    {
        $this->breed = $breed;

        return $this;
    }

    public function getBirthDate(): ?DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?DateTimeImmutable $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function isDangerous(): bool
    {
        return $this->isDangerous;
    }

    public function setIsDangerous(bool $isDangerous): static
    {
        $this->isDangerous = $isDangerous;

        return $this;
    }
}
