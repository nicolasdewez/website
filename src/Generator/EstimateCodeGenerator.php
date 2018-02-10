<?php

namespace App\Generator;

class EstimateCodeGenerator
{
    /** @var string */
    private $prefixCode;

    public function __construct(string $prefixCode)
    {
        $this->prefixCode = $prefixCode;
    }

    public function execute(): string
    {
        return uniqid($this->prefixCode);
    }
}
