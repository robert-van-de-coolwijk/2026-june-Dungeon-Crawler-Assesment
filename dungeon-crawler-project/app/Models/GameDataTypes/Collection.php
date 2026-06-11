<?php

namespace App\Models\GameDataTypes;

class Collection extends GameDataType
{

    public function __construct() // @todo RC consider introducing a $allowType(s) to check if a certain entity is allowed inside this collection
    {
    }

    public function get(): string
    {
        // TODO: Implement get() method.
    }

    public function set(string $input): bool
    {
        // TODO: Implement set() method.
    }
}