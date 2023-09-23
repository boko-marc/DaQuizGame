<?php

namespace App\Entity;

use App\Repository\QuizEntityRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[ORM\Entity(repositoryClass: QuizEntityRepository::class)]
class QuizEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 4)]
    private ?string $random = null;

    #[ORM\Column]
    private ?int $true_actor = null;

    #[ORM\Column]
    private ?int $fake_actor = null;

    #[ORM\ManyToOne(inversedBy: 'quizEntities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRandom(): ?string
    {
        return $this->random;
    }

    public function setRandom(string $random): static
    {
        $this->random = $random;

        return $this;
    }

    public function getTrueActor(): ?int
    {
        return $this->true_actor;
    }

    public function setTrueActor(int $true_actor): static
    {
        $this->true_actor = $true_actor;

        return $this;
    }

    public function getFakeActor(): ?int
    {
        return $this->fake_actor;
    }

    public function setFakeActor(int $fake_actor): static
    {
        $this->fake_actor = $fake_actor;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function generateRandom(LifecycleEventArgs $eventArgs): void
    {
        // Générez une chaîne aléatoire de 4 caractères
              $this->setRandom($random);
    }
}
