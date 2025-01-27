<?php

namespace App\Controller;

use App\Entity\Breed;
use App\Entity\PetType;
use App\Service\PetService;
use DateMalformedStringException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    /**
     * @throws DateMalformedStringException
     */
    #[Route('/api/pet/creation', name: 'api_create_pet', methods: ['POST'])]
    public function create(
        Request    $request,
        PetService $petService,
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $type = $this->em->getRepository(PetType::class)->find($data['type']);
        $breed = $this->em->getRepository(Breed::class)->find([
            'name' => $data['breed'],
            'petType' => $type->getName(),
        ]);

        if (!$breed) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Invalid type or breed provided.',
            ], 400);
        }

        $pet = $petService->savePet($data, $type, $breed);

        return new JsonResponse([
            'status' => 'Pet saved',
            'id' => $pet->getId(),
        ], 201);
    }

    #[Route('/api/options/types', name: 'api_pet_types', methods: ['GET'])]
    public function getPetTypes(
        SerializerInterface $serializer
    ): JsonResponse
    {
        $types = $this->em->getRepository(PetType::class)->findAll();

        $data = $serializer->normalize($types, null, ['groups' => 'pet_type:read']);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/api/options/breeds', name: 'api_pet_breeds', methods: ['GET'])]
    public function getBreeds(
        Request             $request,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $typeName = $request->query->get('type');

        $petType = $this->em->getRepository(PetType::class)->findOneBy(['name' => $typeName]);

        if (!$petType) {
            return new JsonResponse([], 200);
        }

        $breeds = $this->em->getRepository(Breed::class)->findBy(['petType' => $petType]);

        $json = $serializer->serialize($breeds, 'json', ['groups' => 'breed:read']);

        return new JsonResponse($json, 200, [], true);
    }
}
