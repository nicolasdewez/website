<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\EntityListeners({"App\EventListener\PostInDatabaseListener"})
 */
class Post
{
    const NB_PER_PAGE = 5;

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
     * @ORM\Column
     *
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     * @Assert\Length(max=255, maxMessage="Cette chaine est trop longue. Elle doit avoir au maximum 255 caractères.")
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $slug;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     */
    private $body;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $writingDate;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $published;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $updatedDate;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="posts")
     */
    private $tags;

    public function __construct()
    {
        $this->writingDate = (new \DateTime())->setTime(0, 0);
        $this->published = false;
        $this->tags = new ArrayCollection();
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

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setWritingDate(\DateTime $writingDate): void
    {
        $this->writingDate = $writingDate;
    }

    public function getWritingDate(): \DateTime
    {
        return $this->writingDate;
    }

    public function setPublished(bool $published): void
    {
        $this->published = $published;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }

    public function setUpdateDate(): void
    {
        $this->updatedDate = new \DateTime();
    }

    public function getUpdatedDate(): \DateTime
    {
        return $this->updatedDate;
    }

    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function getTagsIntoString(): string
    {
        $tags = [];
        $disabledTags = [];
        foreach ($this->tags as $tag) {
            if ($tag->isActive()) {
                $tags[] = $tag->getTitle();
                continue;
            }

            $disabledTags[] = $tag->getTitle();
        }

        $othersTags = '';
        if (count($disabledTags)) {
            $othersTags = sprintf('(%s)', implode(', ', $disabledTags));
        }

        return sprintf('%s %s', implode(', ', $tags), $othersTags);
    }
}
