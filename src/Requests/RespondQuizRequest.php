<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class RespondQuizRequest extends BaseRequest
{


    #[NotBlank([], "La clé du quiz est obligatoire pour répondre au quiz")]
    #[Type('string', "La clé du quiz est un string")]

    protected $quiz_key;


    #[NotBlank([], "L'id de l'acteur choisit est obligatoire pour répondre au quiz")]
    #[Type('integer', "La clé du quiz est un  entier")]

    protected $actor_id;
   

    public function getQuizKey()
    {
        return $this->quiz_key;
    }

    public function getActorId()
    {
        return $this->actor_id;
    }

    protected function autoValidateRequest(): bool
    {
        return true;
    }
}
