
# DaQuizGame API 

>  This project is an API which test  the player's knowledge of cinema through quizzes. We use TMDB API to fetch movies datas. 


## Prerequisites

For this project we installed:

	* Symfony 6.2
	* Apache 2.4.57
	* PHP >=8.1
	* PostgreSQL  
	* Composer

## Getting started

``` bash
#clone project
git clone https://github.com/boko-marc/DaQuizGame.git
# install dependencies
cd DaQuizGame
composer install

# create .env file and generate the application key
cp .env.example .env
symfony console secrets:generate-keys

NB: You need to generate TMDB API Bearer Token to use this API  [TMDB](https://www.themoviedb.org) 

Then launch the server:

```  bash
symfony server:start -d  

```


The  project is now up and running! Access it at  https://127.0.0.1:8000.
# Setup database
After add DATABASE_URL in .env file , run these command

```bash
php bin/console doctrine:migrations:migrate

```

## Documentation
[Documentation](https://documenter.getpostman.com/view/18525738/2s9YJW4R1p)


## Example

* POST (POST /game)

You can use POST request to create game  with film_id entrie. Order the  following _curl_ command from a terminal (POST  body will be sent in json format):

        
    curl -i -H "Content-Type: application/json" -X POST -d '{"film_id": 111111}' https://127.0.0.1:8000/api/game

    If the film doesn't exist you will have the response code 400(seen in your terminal).You should also see a  " L'ID du film n'est pas valide."

    If everything worked properly you should have received a response code 200 (seen in your terminal). You should also see a "Le jeu a été créé avec succès". 


* GET (GET /game/<hash>/play)

Play game: Retrieve a movie and the list of actors among which the player must find the correct answer 

     curl -X GET https://127.0.0.1:8000/api/game/{<hash>}/play
 If everything worked properly you should have received a response code 200 (seen in your terminal). You should also see a "Film récupéré avec deux auteurs avec succès". with two actors and quiz_key. 
 NB: After quiz is created , we generate a unique quiz key , which used to response to a quiz.

* POST (POST /game/<hash>/play)

You can use POST request to play game  with quiz_key and actor_id entries. Order the  following _curl_ command from a terminal (POST  body will be sent in json format):
 -

        
    curl -i -H "Content-Type: application/json" -X POST -d '{"qui_key": "1AZ5", "actor_id" : 1234566}' https://127.0.0.1:8000/api/game/<hash>/play

    If any quiz don't match with quiz_key  you will have the response code 400(seen in your terminal).You should also see a  " Quiz non trouvé." message

    If everything worked properly you should have received a response code 200 (seen in your terminal). You should also see a "Réponse traitée avec succès" message and score , statut informations

* GET (GET /game/<hash>)

 Game response: Retrieve a game score and statut 

     curl -X GET https://127.0.0.1:8000/api/game/{<hash>}
 If everything worked properly you should have received a response code 200 (seen in your terminal). You should also see a "Game score récupéré avec succès". with score and statut informations 


## Licence

This software is licensed under the Apache 2 license, quoted below.

Copyright 2023 Barter my book.

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this project except in compliance with the License. You may obtain a copy of the License at http://www.apache.org/licenses/LICENSE-2.0.

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.


<!-- CONTACT -->
## Contact

BOKO Marc - [linkedin-url](https://www.linkedin.com/in/marc-uriel-zinsou-boko/) - email@marcboko.uriel@gmail.com

Project Link: [https://github.com/boko-marc/barter-my-book](https://github.com/boko-marc/DaQuizGame) 