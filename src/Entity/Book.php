<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ivesk TITLE")
     * @Assert\Length(
     * min=3,
     * max=255,
     * minMessage = "Title turi buti bent 3 simboliai",
     * maxMessage = "Gali buti max 255 simboliai ",
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(message="Ivesk ISBN")
     * @Assert\Length(
     * min=3,
     * max=255,
     * minMessage = "ISBN turi buti bent 3 simboliai",
     * maxMessage = "Gali buti max 255 simboliai ",
     * )
     */
    private $isbn;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Positive(message="Ivesk puslapius")
     */
    private $pages;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Aprasyk knyga")
     * 
     */
    private $about;

    /**
     * @ORM\Column(type="integer")
     */
    private $author_id;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="books")
     */
    private $author;

    public function getId(): ?int
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

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    public function setPages(int $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    public function getAbout(): ?string
    {
        return $this->about;
    }

    public function setAbout(string $about): self
    {
        $this->about = $about;

        return $this;
    }

    public function getAuthorId(): ?int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): self
    {
        $this->author_id = $author_id;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }
}
