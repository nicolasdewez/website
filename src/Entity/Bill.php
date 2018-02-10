<?php

namespace App\Entity;

use App\Workflow\BillDefinitionWorflow;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="bill")
 * @ORM\Entity(repositoryClass="App\Repository\BillRepository")
 */
class Bill
{
    const VALIDITY = 30;

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
     */
    private $code;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private $timestamp;

    /**
     * @var Customer
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="bills")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotNull(message="Cette valeur ne doit pas être vide.")
     */
    private $customer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     * @Assert\Date(message="Cette valeur doit être une date.")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     *
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     * @Assert\Date(message="Cette valeur doit être une date.")
     */
    private $deadline;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date", nullable=true)
     */
    private $acquitDate;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $state;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\BillLine", mappedBy="bill", cascade={"persist", "remove"}, orphanRemoval=true))
     */
    private $lines;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $totalPrice;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    public function __construct()
    {
        $this->date = (new \DateTime())->setTime(0, 0);
        $this->deadline = (new \DateTime())
            ->add(new \DateInterval(sprintf('P%dD', self::VALIDITY)))
            ->setTime(0, 0)
        ;

        $this->lines = new ArrayCollection();
        $this->totalPrice = 0;
        $this->refreshBill();
        $this->state = BillDefinitionWorflow::PLACE_IN_PROGRESS;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    public function getDeadline(): \DateTime
    {
        return $this->deadline;
    }

    public function setDeadline(\DateTime $deadline): void
    {
        $this->deadline = $deadline;
    }

    public function getAcquitDate(): ?\DateTime
    {
        return $this->acquitDate;
    }

    public function acquit(): void
    {
        $this->acquitDate = new \DateTime();
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getTitleState(): string
    {
        return BillDefinitionWorflow::getTitleByPlace($this->state);
    }

    public function getLines(): Collection
    {
        return $this->lines;
    }

    public function setLines(Collection $lines): void
    {
        $this->lines = $lines;
    }

    public function addLine(BillLine $line): void
    {
        if ($this->lines->contains($line)) {
            return;
        }

        $line->setBill($this);
        $this->lines->add($line);
        $this->refreshBill();
    }

    public function removeLine(BillLine $line): void
    {
        $this->lines->removeElement($line);
        $this->refreshBill();
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function calculateTotalPrice(): void
    {
        $this->totalPrice = 0;
        /** @var BillLine $line */
        foreach ($this->lines as $line) {
            $this->totalPrice += $line->getTotalPrice();
        }
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function refreshBill(): void
    {
        $this->timestamp = (new \DateTime())->getTimestamp();
    }
}
