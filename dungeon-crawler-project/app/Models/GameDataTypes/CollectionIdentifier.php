<?php

namespace App\Models\GameDataTypes;

use App\Core\Tools;
use App\Models\Game;
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

    public function set(string $collectionEntityId): bool
    {
        $succes = parent::set($collectionEntityId);

        if($succes){
            EntityRelationManager::getInstance()->setRelation($this->collectionName, $this->data, $collectionEntityId);
        }

        return $succes;
    }

    public function validate(string $input) : bool {
        // only valid for idRef
        if(strcmp($input, self::Unset)){
            return true;
        }

        if(strlen($input) <= 1){
            return false;
        }

        if(!str_starts_with($input, self::ID_PREFIX)){
            return false;
        }

        if(is_numeric(substr($input, 1))){
            return false;
        }

        // all checks passed
        return true;
    }

}