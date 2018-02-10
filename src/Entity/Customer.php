<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="customer")
 * @ORM\Entity
 */
class Customer
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
     * @ORM\Column(length=100, nullable=true)
     *
     * @Assert\Length(max=100, maxMessage="Cette chaine est trop longue. Elle doit avoir au maximum 100 caractères.")
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(length=100)
     *
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     * @Assert\Length(max=100, maxMessage="Cette chaine est trop longue. Elle doit avoir au maximum 100 caractères.")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(length=100)
     *
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     * @Assert\Length(max=100, maxMessage="Cette chaine est trop longue. Elle doit avoir au maximum 100 caractères.")
     */
    private $lastName;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Estimate", mappedBy="customer")
     */
    private $estimates;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Bill", mappedBy="customer")
     */
    private $bills;

    public function __construct()
    {
        $this->estimates = new ArrayCollection();
        $this->bills = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setCompany(string $company): void
    {
        $this->company = $company;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getDisplayName(): string
    {
        return sprintf(
            '%s %s %s',
            $this->firstName,
            $this->lastName,
            $this->company ? sprintf('(%s)', $this->company) : ''
        );
    }

    public function getEstimates(): Collection
    {
        return $this->estimates;
    }

    public function setEstimates(Collection $estimates): void
    {
        $this->estimates = $estimates;
    }

    public function getBills(): Collection
    {
        return $this->bills;
    }

    public function setBills(Collection $bills): void
    {
        $this->bills = $bills;
    }
}
