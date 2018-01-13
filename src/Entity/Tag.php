<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="tag")
 * @ORM\Entity
 * @UniqueEntity("title", message="Cette valeur existe déjà.")
 */
class Tag
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(length=100, unique=true)
     *
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     * @Assert\Regex(pattern="/^[a-zA-Z0-9 _-]+$/", message="Cette valeur n'est pas valide.")
     * @Assert\Length(max=100, maxMessage="Cette chaine est trop longue. Elle doit avoir au maximum 100 caractères.")
     */
    private $title;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Post", mappedBy="tags")
     */
    private $posts;

    public function __construct()
    {
        $this->active = true;
        $this->posts = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setPosts(Collection $posts): void
    {
        $this->posts = $posts;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }
}
