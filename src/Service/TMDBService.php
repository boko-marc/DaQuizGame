<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TMDBService
{
    private $client;
    public function __construct(HttpClientInterface $tmdbClient)
    {
        $this->client = $tmdbClient;
    }
    public function getMovieActors(int $movieId)
    {
        $response = $this->client->request('GET', "movie/{$movieId}/credits");
        $data = $response->toArray();
        $actorsData = $data['cast'];

        $actors = [];
        foreach ($actorsData as $actorData) {
            $actor = [
                'department' => $actorData['known_for_department'],
                'name' => $actorData['name'],
                'id' => $actorData['id'],
            ];
            $actors[] = $actor;
        }

        return $actors;
    }


    public function isActorInMovie(int $movieId, int $actorId)
    {
        $actors = $this->getMovieActors($movieId);

        // Recherchez l'acteur avec l'ID donné
        foreach ($actors as $actor) {
            if ($actor['department'] === 'Acting' && $actor['id'] === $actorId) {
                return true;
            }
        }

        return false;
    }


    public function getRandomActorFromDifferentMovie(int $excludeMovieId)
    {
        do {
            // get latest film id
            $response = $this->client->request('GET', "movie/latest");

            $data = $response->toArray();
            $latestMovieId = $data['id'];

            // verify if latest film id is different of  $excludeMovieId
            if ($latestMovieId != $excludeMovieId) {
                $actors = $this->getMovieActors($latestMovieId);

                $randomActor = $actors[array_rand($actors)];

                return  ['id' => $randomActor['id'], 'name' => $randomActor['name']];
            }
        } while ($latestMovieId == $excludeMovieId);

        throw new \Exception('Aucun film différent trouvé.');
    }


    public function isValidMovie(int $movieId)
    {
        $response = $this->client->request('GET', "movie/{$movieId}");
        $statusCode = $response->getStatusCode();
        if ($statusCode === 200) {
            return true;
        }
        if ($statusCode === 404) {
            return false;
        }
        if ($statusCode === 401) {
            throw new \Exception('Problème d\'authentification avec TMDB (token expiré ?)');
        }
        return false;
    }
}
