<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieCommentRepository")
 */
class MovieComment extends Comment
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie;

    public function getId()
    {
        return $this->id;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}
