<?php

namespace App\Models\GameDataTypes;

use App\Models\GameDataTypes\Text;

class Resource extends Text
{
    protected int $maxValue;

    public function __construct(int $value)
    {
        parent::__construct();

        $this->data = $value;
    }

    public function getAsNumber() : int {
        return $this->data;
    }
}