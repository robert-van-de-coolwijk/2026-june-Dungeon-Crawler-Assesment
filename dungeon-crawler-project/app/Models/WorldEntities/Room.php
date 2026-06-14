<?php

namespace App\Models\WorldEntities;

use App\Models\GameDataTypes\Collection;
use App\Models\GameDataTypes\EntityRelationManager;
use App\Models\GameDataTypes\ShortText;

class Room extends Container
{
    public ShortText $_biome;

    public Collection $_portals;


    public function __construct()
    {
        parent::__construct();

        $this->_biome = new ShortText();

        $this->_portals = new Collection(EntityRelationManager::Collection_Room_Portal);
    }

}