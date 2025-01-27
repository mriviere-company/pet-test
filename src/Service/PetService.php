<?php

namespace App\Service;

use App\Entity\Breed;
use App\Entity\Pet;
use App\Entity\PetType;
use App\Factory\PetFactory;
use DateMalformedStringException;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

readonly class PetService
{
    public function __construct(
        private PetFactory             $petFactory,
        private EntityManagerInterface $em
    )
    {
    }

    /**
     * @throws DateMalformedStringException
     */
    public function savePet(
        array   $data,
        PetType $type,
        Breed   $breed,
    ): Pet
    {
        $birthDate = isset($data['birthDate'])
            ? new DateTimeImmutable($data['birthDate'])
            : (new DateTimeImmutable())->modify("-{$data['approxAge']} years");

        $dangerousBreeds = ['Pitbull', 'Mastiff'];

        $isDangerous = in_array($breed->getName(), $dangerousBreeds, true);

        $pet = $this->petFactory->create(
            $data['name'],
            $type,
            $breed,
            $birthDate,
            $data['gender'],
            $isDangerous
        );

        $this->em->persist($pet);
        $this->em->flush();

        return $pet;
    }
}
