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
        $portalNames = [];
        $world = Game::getInstance()->getWorld();

        $portalIds = EntityRelationManager::getInstance()->getIdsInsideCollection(EntityRelationManager::Collection_Room_Portal, $this);

        foreach ($portalIds as $portalId) {
            $entity = $world->getEntityById($portalId);

            $portalNames[$portalId] = !is_null($entity) ? $entity->name : $portalId;
        }

        return $portalNames;
    }

}