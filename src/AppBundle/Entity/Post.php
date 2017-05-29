<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Post
 *
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks
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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param string $title
     *
     * @return Post
     */
    public function setTitle(string $title): Post
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        if (null === $this->title) {
            $this->title = '';
        }

        return $this->title;
    }

    /**
     * @param string $slug
     *
     * @return Post
     */
    public function setSlug(string $slug): Post
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $body
     *
     * @return Post
     */
    public function setBody(string $body): Post
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        if (null === $this->body) {
            $this->body = '';
        }

        return $this->body;
    }

    /**
     * @param \DateTime $writingDate
     *
     * @return Post
     */
    public function setWritingDate(\DateTime $writingDate): Post
    {
        $this->writingDate = $writingDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getWritingDate(): \DateTime
    {
        return $this->writingDate;
    }

    /**
     * @param bool $published
     *
     * @return Post
     */
    public function setPublished(bool $published): Post
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published;
    }


    /**
     * @return Post
     */
    public function setUpdateDate(): Post
    {
        $this->updatedDate = new \DateTime();

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedDate(): \DateTime
    {
        return $this->updatedDate;
    }

    /**
     * @param Collection $tags
     *
     * @return Post
     */
    public function setTags(Collection $tags): Post
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }
    /**
     * @return string
     */
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
