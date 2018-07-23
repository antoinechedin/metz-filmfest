<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     */
    private $year;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Director", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $director;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Actor", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $actor;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="movies")
     * @Assert\Count(
     *      min = 1,
     *      max = 3,
     *      minMessage = "You must specify at least {{ limit }} category",
     *      maxMessage = "You cannot specify more than {{ limit }} categories"
     * )
     */
    private $categories;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $synopsis;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    private $link;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Type("string")
     */
    private $additionalInformation;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Assert\Type("bool")
     */
    private $shortlisted;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="movie", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Festival", inversedBy="moviesRegistered")
     * @ORM\JoinColumn(nullable=false)
     */
    private $festival;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ScreeningDay", inversedBy="screenedMovies")
     */
    private $screeningDay;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $screeningDayIndex;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Type("string")
     */
    private $picture;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getDirector(): ?Director
    {
        return $this->director;
    }

    public function setDirector(Director $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getActor(): ?Actor
    {
        return $this->actor;
    }

    public function setActor(?Actor $actor): self
    {
        $this->actor = $actor;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getAdditionalInformation(): ?string
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(?string $additionalInformation): self
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }

    public function getShortlisted(): ?bool
    {
        return $this->shortlisted;
    }

    public function setShortlisted(?bool $shortlisted): self
    {
        $this->shortlisted = $shortlisted;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setMovie($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getMovie() === $this) {
                $comment->setMovie(null);
            }
        }

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

    public function getScreeningDay(): ?ScreeningDay
    {
        return $this->screeningDay;
    }

    public function setScreeningDay(?ScreeningDay $screeningDay): self
    {
        $this->screeningDay = $screeningDay;

        return $this;
    }

    public function getScreeningDayIndex(): ?int
    {
        return $this->screeningDayIndex;
    }

    public function setScreeningDayIndex(?int $screeningDayIndex): self
    {
        $this->screeningDayIndex = $screeningDayIndex;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string  $picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}
