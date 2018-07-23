<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FestivalRepository")
 */
class Festival
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Movie", mappedBy="festival")
     */
    private $moviesRegistered;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ScreeningDay", mappedBy="festival")
     */
    private $screeningDays;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FestivalComment", mappedBy="festival", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="date")
     */
    private $registrationStartDate;

    /**
     * @ORM\Column(type="date")
     */
    private $registrationEndDate;

    public function __construct()
    {
        $this->moviesRegistered = new ArrayCollection();
        $this->screeningDays = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMoviesRegistered(): Collection
    {
        return $this->moviesRegistered;
    }

    public function addMoviesRegistered(Movie $moviesRegistered): self
    {
        if (!$this->moviesRegistered->contains($moviesRegistered)) {
            $this->moviesRegistered[] = $moviesRegistered;
            $moviesRegistered->setFestival($this);
        }

        return $this;
    }

    public function removeMoviesRegistered(Movie $moviesRegistered): self
    {
        if ($this->moviesRegistered->contains($moviesRegistered)) {
            $this->moviesRegistered->removeElement($moviesRegistered);
            // set the owning side to null (unless already changed)
            if ($moviesRegistered->getFestival() === $this) {
                $moviesRegistered->setFestival(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ScreeningDay[]
     */
    public function getScreeningDays(): Collection
    {
        return $this->screeningDays;
    }

    public function addScreeningDay(ScreeningDay $screeningDay): self
    {
        if (!$this->screeningDays->contains($screeningDay)) {
            $this->screeningDays[] = $screeningDay;
            $screeningDay->setFestival($this);
        }

        return $this;
    }

    public function removeScreeningDay(ScreeningDay $screeningDay): self
    {
        if ($this->screeningDays->contains($screeningDay)) {
            $this->screeningDays->removeElement($screeningDay);
            // set the owning side to null (unless already changed)
            if ($screeningDay->getFestival() === $this) {
                $screeningDay->setFestival(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FestivalComment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(FestivalComment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setFestival($this);
        }

        return $this;
    }

    public function removeComment(FestivalComment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getFestival() === $this) {
                $comment->setFestival(null);
            }
        }

        return $this;
    }

    public function getRegistrationStartDate(): ?\DateTimeInterface
    {
        return $this->registrationStartDate;
    }

    public function setRegistrationStartDate(\DateTimeInterface $registrationStartDate): self
    {
        $this->registrationStartDate = $registrationStartDate;

        return $this;
    }

    public function getRegistrationEndDate(): ?\DateTimeInterface
    {
        return $this->registrationEndDate;
    }

    public function setRegistrationEndDate(\DateTimeInterface $registrationEndDate): self
    {
        $this->registrationEndDate = $registrationEndDate;

        return $this;
    }
}
