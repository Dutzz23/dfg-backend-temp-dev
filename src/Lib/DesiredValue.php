<?php

namespace App\Lib;

class DesiredValue
{

    public function __construct(
        private readonly bool  $isWeight,
        private readonly mixed $value)
    {
    }

    public function isWeight(): bool
    {
        return $this->isWeight;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}