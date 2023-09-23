<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CreateGameRequest extends BaseRequest 
{
    

    #[NotBlank([],"L'id du film est obligatoire pour créer le jeu")]
    #[Type('integer', "L'id du film doit être un entier")]

    protected $film_id;

    protected function autoValidateRequest(): bool
    {
        return true;
    }

    public function getFilmId() {
        return $this->film_id;
    }
}
