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

    public function create(EntityManagerInterface $entityManager): Response
    {

        $randomMovie = $this->tMDBService->getRandomMovie();
        $randomMovieActors = $this->tMDBService->getMovieActors($randomMovie['id']);
        $game = new Game();
        $entityManager->persist($game);
        $entityManager->flush();
        // choice a random movie actor
        $rightActor = $randomMovieActors[array_rand($randomMovieActors)];

        //choice fake random actor
        $fakeActor = $this->tMDBService->getFakeActorFromMovie($randomMovie['id']);

        // persist quiz data 
        $quiz = new QuizEntity();
        $quiz->setGame($game);
        $quiz->setTrueActor($rightActor['id']);
        $quiz->setFakeActor($fakeActor['id']);
        // generate random quiz key
        $quiz->setRandom($this->generateRandomKey());
        $entityManager->persist($quiz);
        $entityManager->flush();
        $quizData = ['quiz_key' => $quiz->getRandom(), 'first_actor' => $rightActor, 'second_actor' => $fakeActor];
        return $this->json(['message' => 'Le jeu a été créé avec succès.', 'data' => ['id' => $game->getId(), 'selected_movie' => $randomMovie, 'quiz' => $quizData]], Response::HTTP_CREATED);
    }

    #[Route('/{id}/play', name: 'play', methods: 'GET')]
    public function play(Game $game, EntityManagerInterface $entityManager): Response
    {
        $randomMovie = $this->tMDBService->getRandomMovie();

        $movieActors = $this->tMDBService->getMovieActors($randomMovie['id']);

        // choice a random movie actor
        $firstActor = $movieActors[array_rand($movieActors)];
        //choice fake random actor
        $secondActor = $this->tMDBService->getFakeActorFromMovie($randomMovie['id']);

        // persist quiz data 
        $quiz = new QuizEntity();
        $quiz->setGame($game);
        $quiz->setTrueActor($firstActor['id']);
        $quiz->setFakeActor($secondActor['id']);
        // generate random quiz key
        $quiz->setRandom($this->generateRandomKey());
        $entityManager->persist($quiz);
        $entityManager->flush();

        $data = ['quiz_key' => $quiz->getRandom(), 'first_actor' => $firstActor, 'second_actor' => $secondActor, "selected_movie" => $randomMovie];
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
        // delete quiz
        $entityManager->remove($quiz);
        $entityManager->flush();

        return $this->json(['message' => 'Réponse traitée avec succès', 'score' => $game->getScore(), 'statut' => $game->isStatut()]);
    }

    #[Route('/{id}', name: 'gameScore', methods: 'GET')]
    public function gameScore(Game $game): Response
    {
        $data = [
            "score" => $game->getScore(),
            "statut" => $game->isStatut()
        ];
        return $this->json(['message' => 'Game score récupéré avec succès', 'data' => $data]);
    }
}
