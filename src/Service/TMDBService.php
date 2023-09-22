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


    public function getFakeActorFromMovie(int $movieId)
    {
        do {
            // get latest film 
            $randomDifferentMovie = $this->getRandomPopularMovieWithDifferentId($movieId);
            $randomDifferentMovieActors = $this->getMovieActors($randomDifferentMovie);
            // get random latest film actor
            $randomActor = $randomDifferentMovieActors[array_rand($randomDifferentMovieActors)];
            if ($randomActor['department'] === 'Acting') {
                $excludeMovieActors = $this->getMovieActors($movieId);
                $actorName = $randomActor['name'];
                $isActorInExcludeMovie = false;
                foreach ($excludeMovieActors as $excludeMovieActor) {
                    if ($excludeMovieActor['name'] === $actorName) {
                        $isActorInExcludeMovie = true;
                        break;
                    }
                }
                if (!$isActorInExcludeMovie) {
                    return ['id' => $randomActor['id'], 'name' => $actorName];
                }
            }
        } while (true);

        throw new \Exception('Aucun acteur différent trouvé.');
    }

    public function getRandomPopularMovieWithDifferentId(int $excludeMovieId)
    {
        // Récupérez la liste des films populaires
        $popularMoviesResponse = $this->client->request('GET', 'movie/popular');
        $popularMovies = $popularMoviesResponse->toArray()['results'];

        // Créez un tableau pour stocker les IDs des films populaires avec des IDs différents
        $popularMoviesWithDifferentId = [];

        // Filtrez les films populaires pour ne conserver que ceux avec des IDs différents
        foreach ($popularMovies as $movie) {
            if ($movie['id'] != $excludeMovieId) {
                $popularMoviesWithDifferentId[] = $movie;
            }
        }

        // Choisissez un film populaire au hasard parmi ceux avec des IDs différents
        if (!empty($popularMoviesWithDifferentId)) {
            $randomMovieKey = array_rand($popularMoviesWithDifferentId);
            $randomMovie = $popularMoviesWithDifferentId[$randomMovieKey];
            return  $randomMovie['id'];
        }

        throw new \Exception('Aucun film populaire avec un ID différent trouvé.');
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
