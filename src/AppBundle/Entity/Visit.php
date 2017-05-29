<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Visit
 *
 * @ORM\Table(name="visit", indexes={@ORM\Index(name="visit_date_path", columns={"date", "path"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VisitRepository")
 */
class Visit
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
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $path;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"unsigned"=true})
     */
    private $count;

    public function __construct()
    {
        $this->count = 0;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param \DateTime $date
     *
     * @return Visit
     */
    public function setDate(\DateTime $date): Visit
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param string $path
     *
     * @return Visit
     */
    public function setPath(string $path): Visit
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return Visit
     */
    public function addCount(): Visit
    {
        $this->count++;

        return $this;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }
}
