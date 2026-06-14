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

    /**
     * Returns the names of portals, if a portal (entity) could not be found, it returns the id
     *
     * @return array Array of strings
     * @throws \Exception
     */
    public function getContentNames() : array
    {
        return $this->getCollectionEntitiesAssoc(EntityRelationManager::Collection_Container_Entity);
    }
}