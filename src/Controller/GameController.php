<?php

namespace App\Controller;

use App\Service\TMDBService;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Game;
use App\Requests\CreateGameRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;



#[Route('/game', name: 'game_')]

class GameController extends AbstractController
{
    public function __construct(private TMDBService $tMDBService)
    {
    }
    #[Route('', name: 'create', methods: ['POST'])]

    public function create(CreateGameRequest $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->tMDBService->isValidMovie($request->getFilmId())) {
            return $this->json(['message' => 'L\'ID du film n\'est pas valide.'], Response::HTTP_BAD_REQUEST);
        }
        $game = new Game();
        $game->setFilmId($request->getFilmId());
        $entityManager->persist($game);
        $entityManager->flush();

        return $this->json(['message' => 'Le jeu a été créé avec succès.', 'data' => ['id' => $game->getId(), 'film_id' => $game->getFilmId()]], Response::HTTP_CREATED);
    }
}



