<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="bill_line")
 * @ORM\Entity
 */
class BillLine
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
     * @var Bill
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Bill", inversedBy="lines")
     */
    private $bill;

    /**
     * @var string
     *
     * @ORM\Column(length=300)
     *
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     */
    private $title;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     *
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     * @Assert\Type(type="float", message="Cette valeur doit être un nombre.")
     * @Assert\GreaterThan(value=0, message="Cette valeur doit être supérieure à 0.")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     *
     * @Assert\NotBlank(message="Cette valeur ne doit pas être vide.")
     * @Assert\Type(type="float", message="Cette valeur doit être un nombre.")
     */
    private $unitPrice;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $totalPrice = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function getBill(): Bill
    {
        return $this->bill;
    }

    public function setBill(Bill $bill): void
    {
        $this->bill = $bill;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): void
    {
        $this->unitPrice = $unitPrice;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function calculateTotalPrice(): void
    {
        $this->totalPrice = $this->quantity * $this->unitPrice;
    }
}
