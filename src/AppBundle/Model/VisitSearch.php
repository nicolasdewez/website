<?php

namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * VisitSearch
 */
class VisitSearch
{
    /**
     * @var \DateTime
     *
     * @Assert\NotBlank(message="La date de début est obligatoire")
     * @Assert\Date
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank(message="La date de fin est obligatoire")
     * @Assert\Date
     */
    private $end;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     *
     * @Assert\Choice(choices={"", "date", "path"}, message="Le champ grouper par doit avoir la valeur Date, Path ou être vide")
     */
    private $group;

    public function __construct()
    {
        $this->start = new \DateTime();
        $this->end = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getStart(): \DateTime
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     *
     * @return VisitSearch
     */
    public function setStart(\DateTime $start): VisitSearch
    {
        $this->start = $start;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEnd(): \DateTime
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     *
     * @return VisitSearch
     */
    public function setEnd(\DateTime $end): VisitSearch
    {
        $this->end = $end;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        if (null === $this->path) {
            $this->path = '';
        }

        return $this->path;
    }

    /**
     * @param string|null $path
     *
     * @return VisitSearch
     */
    public function setPath(string $path = null): VisitSearch
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        if (null === $this->group) {
            $this->group = '';
        }

        return $this->group;
    }

    /**
     * @param string|null $group
     *
     * @return VisitSearch
     */
    public function setGroup(string $group = null): VisitSearch
    {
        $this->group = $group;

        return $this;
    }
}
