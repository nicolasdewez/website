<?php

namespace App\Service;

use App\Entity\Bill;
use App\Repository\BillRepository;
use Doctrine\ORM\EntityManagerInterface;

class BalanceStatistics
{
    /** @var EntityManagerInterface */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function execute(int $year): array
    {
        /** @var BillRepository $balanceRepository */
        $balanceRepository = $this->manager->getRepository(Bill::class);
        $data = $balanceRepository->getBalance($this->getStartDate($year), $this->getEndDate($year));

        $results = $this->initializeData();

        foreach ($data as $element) {
            $quarter = $this->getQuarter($element['month']);
            $value = $element['total'];

            $results['total'] += $value;
            $results['quarter'][$quarter]['total'] += $value;
            $results['quarter'][$quarter]['month'][$element['month']] = $value;
        }

        return $results;
    }

    /**
     * No code for automatize initialization for show structure of data.
     */
    private function initializeData(): array
    {
        return [
            'total' => 0,
            'quarter' => [
                1 => [
                    'total' => 0,
                    'month' => [
                        1 => 0,
                        2 => 0,
                        3 => 0,
                    ],
                ],
                2 => [
                    'total' => 0,
                    'month' => [
                        4 => 0,
                        5 => 0,
                        6 => 0,
                    ],
                ],
                3 => [
                    'total' => 0,
                    'month' => [
                        7 => 0,
                        8 => 0,
                        9 => 0,
                    ],
                ],
                4 => [
                    'total' => 0,
                    'month' => [
                        10 => 0,
                        11 => 0,
                        12 => 0,
                    ],
                ],
            ],
        ];
    }

    private function getQuarter(int $month): int
    {
        return (int) ceil($month / 3);
    }

    private function getStartDate(int $year)
    {
        return new \DateTime(sprintf('%d-01-01', $year));
    }

    private function getEndDate(int $year)
    {
        return new \DateTime(sprintf('%d-12-31', $year));
    }
}
