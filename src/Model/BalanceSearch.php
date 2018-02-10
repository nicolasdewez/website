<?php

namespace App\Model;

class BalanceSearch
{
    /** @var int */
    private $year;

    public function __construct()
    {
        $this->year = (int) date('Y');
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }
}
