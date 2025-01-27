<?php

namespace App\Factory;

use App\Entity\Pet;
use App\Entity\PetType;
use App\Entity\Breed;
use DateTimeImmutable;

class PetFactory
{
    public function create(
        string            $name,
        PetType           $type,
        Breed             $breed,
        DateTimeImmutable $birthDate,
        string            $gender,
        bool              $isDangerous
    ): Pet
    {
        $pet = new Pet();
        $pet->setName($name);
        $pet->setType($type);
        $pet->setBreed($breed);
        $pet->setBirthDate($birthDate);
        $pet->setGender($gender);
        $pet->setIsDangerous($isDangerous);

        return $pet;
    }
}
