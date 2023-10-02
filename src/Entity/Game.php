<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   

    #[ORM\Column(type: "integer", options: ["default" => 0])]
    private ?int $score = 0;

    // true: game is open false: game is closed
    #[ORM\Column(type: "boolean", options: ["default" => true])]
    private ?bool $statut = true;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: QuizEntity::class)]
    private Collection $quizEntities;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    public function __construct()
    {
        $this->quizEntities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    


    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return Collection<int, QuizEntity>
     */
    public function getQuizEntities(): Collection
    {
        return $this->quizEntities;
    }

    public function addQuizEntity(QuizEntity $quizEntity): static
    {
        if (!$this->quizEntities->contains($quizEntity)) {
            $this->quizEntities->add($quizEntity);
            $quizEntity->setGame($this);
        }

        return $this;
    }

    public function removeQuizEntity(QuizEntity $quizEntity): static
    {
        if ($this->quizEntities->removeElement($quizEntity)) {
            // set the owning side to null (unless already changed)
            if ($quizEntity->getGame() === $this) {
                $quizEntity->setGame(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
