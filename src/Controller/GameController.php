<?php

namespace App\Controller;

use App\Service\TMDBService;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Game;
use App\Entity\QuizEntity;
use App\Requests\CreateGameRequest;
use App\Requests\RespondQuizRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;



#[Route('/api/game', name: 'game_')]

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

    #[Route('/{id}/play', name: 'play', methods: 'GET')]
    public function play(Game $game, EntityManagerInterface $entityManager): Response
    {
        $movieActors = $this->tMDBService->getMovieActors($game->getFilmId());

        // choice a random movie actor
        $firstActor = $movieActors[array_rand($movieActors)];
        //choice fake random actor
        $secondActor = $this->tMDBService->getFakeActorFromMovie($game->getFilmId());

        // persist quiz data 
        $quiz = new QuizEntity();
        $quiz->setGame($game);
        $quiz->setTrueActor($firstActor['id']);
        $quiz->setFakeActor($secondActor['id']);
        $quiz->setRandom($this->generateRandomKey());
        $entityManager->persist($quiz);
        $entityManager->flush();

        $data = ['quiz_key' => $quiz->getRandom(), 'first_actor' => $firstActor, 'second_actor' => $secondActor];
        return $this->json(['message' => "Film récupéré avec deux auteurs avec succès", 'data' => $data], Response::HTTP_OK);
    }
    public function generateRandomKey()
    {
        $random = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
        return $random;
    }

    #[Route('/{id}/play', name: 'respondQuiz', methods: 'POST')]
    public function respondQuiz(Game $game, RespondQuizRequest $request, EntityManagerInterface $entityManager): Response
    {
        $quizRepository = $entityManager->getRepository(QuizEntity::class);

        $quizKey = $request->getQuizKey();

        $quiz = $quizRepository->findOneBy(['random' => $quizKey, 'game' => $game]);
        if (is_null($quiz)) {
            return $this->json(['message' => 'Quiz non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $chosenActorId = $request->getActorId();
        if ($chosenActorId == $quiz->getTrueActor()) {
            $game->setScore($game->getScore() + 1);
        } else {
            $game->setStatut(false);
        }


        $entityManager->persist($game);
        $entityManager->remove($quiz);
        $entityManager->flush();

        return $this->json(['message' => 'Réponse traitée avec succès', 'score' => $game->getScore(), 'statut' => $game->isStatut()]);
    }
    
    
}
