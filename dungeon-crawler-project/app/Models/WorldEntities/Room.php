<?php

namespace App\Models\WorldEntities;

use App\Models\GameDataTypes\Collection;

class Room extends Container
{
    public Collection $data;

    public function __construct()
    {
        $this->data = new Collection();
    }

}