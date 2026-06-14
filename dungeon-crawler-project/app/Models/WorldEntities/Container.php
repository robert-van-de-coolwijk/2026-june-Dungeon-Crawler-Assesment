<?php

namespace App\Models\WorldEntities;

use App\Models\GameDataTypes\Collection;
use App\Models\GameDataTypes\EntityRelationManager;

class Container extends Entity
{
    public Collection $_contents;

    public function __construct()
    {
        parent::__construct();

        $this->_contents = new Collection(EntityRelationManager::Collection_Container_Entity);

    }
}