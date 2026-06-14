<?php

namespace App\Models\WorldEntities;

use App\Models\Game;
use App\Models\GameDataTypes\Collection;
use App\Models\GameDataTypes\EntityRelationManager;
use App\Models\GameDataTypes\ShortText;

class Room extends Container
{
    public ShortText $_biome;

    public Collection $_portals;

    public Collection $_creatures;


    public function __construct()
    {
        parent::__construct();

        $this->_biome = new ShortText();

        $this->_portals = new Collection(EntityRelationManager::Collection_Room_Portal);
    }

    /**
     * Returns the names of portals, if a portal (entity) could not be found, it returns the id
     *
     * @return array Array of strings
     * @throws \Exception
     */
    public function getPortalNames() : array
    {
        return $this->getCollectionEntitiesAssoc(EntityRelationManager::Collection_Room_Portal);
    }

    public function getContentNames() : array
    {
        return $this->getCollectionEntitiesAssoc(EntityRelationManager::Collection_Container_Entity);
    }



}