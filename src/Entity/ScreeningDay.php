<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScreeningDayRepository")
 */
class ScreeningDay
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Festival", inversedBy="screeningDays")
     * @ORM\JoinColumn(nullable=false)
     */
    private $festival;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Movie", mappedBy="screeningDay")
     */
    private $screenedMovies;

    public function __construct()
    {
        $this->screenedMovies = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
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

    /**
     * @return Collection|Movie[]
     */
    public function getScreenedMovies(): Collection
    {
        return $this->screenedMovies;
    }

    public function setScreenedMovies($screenedMovies): void
    {
        $this->screenedMovies = $screenedMovies;
    }


    public function addScreenedMovie(Movie $screenedMovie): self
    {
        if (!$this->screenedMovies->contains($screenedMovie)) {
            $this->screenedMovies[] = $screenedMovie;
            $screenedMovie->setScreeningDay($this);
        }

        return $this;
    }

    public function removeScreenedMovie(Movie $screenedMovie): self
    {
        if ($this->screenedMovies->contains($screenedMovie)) {
            $this->screenedMovies->removeElement($screenedMovie);
            // set the owning side to null (unless already changed)
            if ($screenedMovie->getScreeningDay() === $this) {
                $screenedMovie->setScreeningDay(null);
            }
        }

        return $this;
    }

    public function clearScreenedMovies(): self
    {
        $this->screenedMovies->clear();
        return $this;
    }
}
