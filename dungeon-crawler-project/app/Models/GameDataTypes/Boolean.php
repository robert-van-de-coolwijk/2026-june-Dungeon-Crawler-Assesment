<?php

namespace App\Models\GameDataTypes;

class Boolean extends Text
{
    public function validate(string $input) : bool {
        return $input == 'true' || $input == 'false';
    }

}