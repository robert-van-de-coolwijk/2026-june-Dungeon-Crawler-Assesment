<?php

namespace App\Models\WorldEntities;

use App\Models\GameDataTypes\ShortText;

class Room extends Container
{
    public ShortText $_biome;


    public function __construct()
    {
        parent::__construct();

        $this->_biome = new ShortText();

    }

}