<?php

namespace App\Service;

use App\Entity\Bill;
use App\Entity\BillLine;
use App\Entity\Estimate;
use App\Entity\EstimateLine;
use Doctrine\ORM\EntityManagerInterface;

class EstimateToBill
{
    /** @var EntityManagerInterface */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function execute(Estimate $estimate): Bill
    {
        $bill = new Bill();
        $bill->setCustomer($estimate->getCustomer());
        $bill->setComment($estimate->getComment());

        /** @var EstimateLine $estimateLine */
        foreach ($estimate->getLines() as $estimateLine) {
            $billLine = new BillLine();
            $billLine->setTitle($estimateLine->getTitle());
            $billLine->setUnitPrice($estimateLine->getUnitPrice());
            $billLine->setQuantity($estimateLine->getQuantity());

            $bill->addLine($billLine);
        }

        $this->manager->persist($bill);
        $this->manager->flush();

        return $bill;
    }
}
