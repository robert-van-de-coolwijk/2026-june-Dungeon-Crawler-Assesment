<?php

namespace App\Models\GameDataTypes;

use App\Core\Tools;
use App\Models\Game;
use App\Models\WorldEntities\Entity;
use App\Models\WorldEntities\World;

/**
 * This value stores the ID of one Entity as a reference
 * it is not implicit connection and is optional
 * when set; the identifier reference must point to a valid entity which exists only once.
 */
class CollectionIdentifier extends Identifier
{

    /**
     * @var string Registers this identity reference relation to collection name
     */
    protected string $collectionName;

    public function __construct(string $collectionName) {
        parent::__construct();

        $this->data = self::Unset;

        $this->collectionName = $collectionName;
    }

    public function setCollectionRelation(Entity|string $singularEntity, Entity|string $collectionEntity) : void
    {
        EntityRelationManager::getInstance()->setRelation($this->collectionName, $singularEntity, $collectionEntity);
    }
}