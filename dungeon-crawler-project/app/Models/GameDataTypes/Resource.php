<?php

namespace App\Models\GameDataTypes;

use App\Models\GameDataTypes\Text;

class Resource extends Text
{
    protected int $maxValue;

    public function __construct(int $maxValue)
    {
        $this->maxValue = $maxValue;
    }

    public function getMaxValue(): int {
        return $this->maxValue;
    }
}