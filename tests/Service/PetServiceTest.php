<?php

namespace App\Tests\Service;

use App\Entity\Breed;
use App\Entity\PetType;
use App\Factory\PetFactory;
use App\Service\PetService;
use DateMalformedStringException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use PHPUnit\Framework\TestCase;

class PetServiceTest extends TestCase
{
    /**
     * @throws DateMalformedStringException
     */
    public function testPetCreation(): void
    {
        $mockEntityManager = $this->createMock(EntityManagerInterface::class);
        $mockPetTypeRepository = $this->createMock(ObjectRepository::class);
        $mockBreedRepository = $this->createMock(ObjectRepository::class);

        // Mock the EntityManager's getRepository method
        $mockEntityManager
            ->method('getRepository')
            ->willReturnMap([
                [PetType::class, $mockPetTypeRepository],
                [Breed::class, $mockBreedRepository],
            ]);

        // Create mock PetType and Breed entities
        $mockPetType = new PetType();
        $mockPetType->setName('Dog');

        $mockBreed = new Breed();
        $mockBreed->setName('Labrador');
        $mockBreed->setPetType($mockPetType);

        // Mock repository methods
        $mockPetTypeRepository
            ->method('find')
            ->with('Dog')
            ->willReturn($mockPetType);

        $mockBreedRepository
            ->method('find')
            ->with('Labrador')
            ->willReturn($mockBreed);

        // Create the service and factory
        $factory = new PetFactory();
        $service = new PetService($factory, $mockEntityManager);

        // Act
        $pet = $service->savePet([
            'name' => 'Buddy',
            'type' => 'Dog',
            'breed' => 'Labrador',
            'birthDate' => '2020-01-01',
            'gender' => 'Male',
            'isDangerous' => false,
        ], $mockPetType, $mockBreed);

        // Assert
        $this->assertSame('Buddy', $pet->getName());
        $this->assertSame('Dog', $pet->getType()->getName());
        $this->assertSame('Labrador', $pet->getBreed()->getName());
        $this->assertFalse($pet->isDangerous());
    }
}
