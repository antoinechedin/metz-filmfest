<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FestivalCommentRepository")
 */
class FestivalComment extends Comment
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Festival", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $festival;

    public function getId()
    {
        return $this->id;
    }

    public function getFestival(): ?Festival
    {
        return $this->festival;
    }

    public function setFestival(?Festival $festival): self
    {
        $this->festival = $festival;

        return $this;
    }
}
