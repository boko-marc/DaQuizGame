<?php

namespace App\Tests\Unit\Service;

use App\Service\TMDBService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TMDBTest extends WebTestCase
{

    public function testGetMovieActors()
    {
        // Fake movie ID
        $fakeMovieId = 123;

        // Fake API response
        $fakeApiResponse = [
            'cast' => [
                [
                    'known_for_department' => 'Sound', 'name' => 'Actor 1', 'id' => 1, 'job' => "Director of Photography", "department" => "Sound", "adult" => false, "gender" => 2, "original_name" => "Actor 1", "popularity" => 1.4, "credit_id" => "52fe4250c3a36847f8014a23", "profile_path" => "/93Mn7WPDJjAEVvDv2J39RhzlnEE.jpg"
                ],
                [
                    'known_for_department' => 'Acting', 'name' => 'Actor 2', 'id' => 2, 'job' => "Director of Photography", "department" => "Sound", "adult" => false, "gender" => 2, "original_name" => "Actor 1", "popularity" => 1.4, "credit_id" => "52fe4250c3a36847f8014a23", "profile_path" => "/93Mn7WPDJjAEVvDv2J39RhzlnEE.jpg"
                ],
                [
                    'known_for_department' => 'Camera', 'name' => 'Actor 3', 'id' => 3, 'job' => "Director of Photography", "department" => "Sound", "adult" => false, "gender" => 2, "original_name" => "Actor 1", "popularity" => 1.4, "credit_id" => "52fe4250c3a36847f8014a23", "profile_path" => "/93Mn7WPDJjAEVvDv2J39RhzlnEE.jpg"
                ]

            ]

        ];
        $mockResponseJson = json_encode($fakeApiResponse, JSON_THROW_ON_ERROR);
        // Mock response
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
            'response_headers' => ['Content-Type: application/json']
        ]);
        // Mock HTTP client
        $httpClientMock =  new MockHttpClient($mockResponse, "https://api.themoviedb.org/3/movie/{$fakeMovieId}/credits");




        // Create TMDB service with mocked HTTP client
        $tmdbService = new TMDBService($httpClientMock);


        // Call getMovieActors() to get movie actors
        $actors = $tmdbService->getMovieActors($fakeMovieId);

        // Assert that getMovieActors() returns an array of actors
        $this->assertIsArray($actors);
    }

    public function testIsActorInMovie()
    {
        // Set up the expected behavior for the HTTP client mock
        $fakeMovieId = 1; // Fictitious movie ID
        $fakeActorId = 1;   // Fictitious actor ID
        // Fake API response
        $fakeApiResponse = [
            'cast' => [
                [
                    'known_for_department' => 'Sound', 'name' => 'Actor 1', 'id' => 1, 'job' => "Director of Photography", "department" => "Sound", "adult" => false, "gender" => 2, "original_name" => "Actor 1", "popularity" => 1.4, "credit_id" => "52fe4250c3a36847f8014a23", "profile_path" => "/93Mn7WPDJjAEVvDv2J39RhzlnEE.jpg"
                ],
                [
                    'known_for_department' => 'Acting', 'name' => 'Actor 2', 'id' => 1, 'job' => "Director of Photography", "department" => "Sound", "adult" => false, "gender" => 2, "original_name" => "Actor 1", "popularity" => 1.4, "credit_id" => "52fe4250c3a36847f8014a23", "profile_path" => "/93Mn7WPDJjAEVvDv2J39RhzlnEE.jpg"
                ],
                [
                    'known_for_department' => 'Camera', 'name' => 'Actor 3', 'id' => 3, 'job' => "Director of Photography", "department" => "Sound", "adult" => false, "gender" => 2, "original_name" => "Actor 1", "popularity" => 1.4, "credit_id" => "52fe4250c3a36847f8014a23", "profile_path" => "/93Mn7WPDJjAEVvDv2J39RhzlnEE.jpg"
                ]

            ]

        ];
        $mockResponseJson = json_encode($fakeApiResponse, JSON_THROW_ON_ERROR);
        // Mock response
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 200,
            'response_headers' => ['Content-Type: application/json']
        ]);
        $httpClientMock =  new MockHttpClient($mockResponse);

        $tmdbService = new TMDBService($httpClientMock);

        // Call the method to check if the fictitious actor is in the fictitious movie
        $isActorInMovie = $tmdbService->isActorInMovie($fakeMovieId, $fakeActorId);

        // Assert that the method correctly identifies the actor in the movie
        $this->assertTrue($isActorInMovie);

    }
}
