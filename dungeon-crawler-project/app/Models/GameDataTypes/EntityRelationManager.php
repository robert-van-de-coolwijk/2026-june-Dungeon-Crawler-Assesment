<?php

namespace App\Models\GameDataTypes;

use App\Core\Tools;
use App\Models\SingletonPattern;
use App\Models\WorldEntities\Entity;

class EntityRelationManager extends SingletonPattern
{
    public const string Collection_Room_Portal = 'Collection_Portal_Portal';
    public const string Collection_Container_Entity = 'Collection_Container_Entity';

    /**
     * To ensure 1:N collections, the 1 is the key
     *
     * $collection[PropName][Character] = [Room]
     *
     * @var array $collection[PropName][SingularEntity] = [CollectionEntity]
     */
    protected array $collections;

    protected function __construct() {
        parent::__construct();

        $this->collections = [
            self::Collection_Room_Portal => [],
            self::Collection_Container_Entity => [],
        ];
    }

    public static function getInstance() : EntityRelationManager
    {
        return parent::getInstance();
    }

    public function isValidCollectionName(string $collectionName) : bool {
        return isset($this->collections[$collectionName]);
    }

    public function setRelation(string $collectionName, Entity|string $singularEntity, Entity|string $collectionEntity) : void
    {
        if(!$this->isValidCollectionName($collectionName))
        {
            throw new \Exception("Invalid collection \"$collectionName\" ");
        }

        $singularEntityId = is_string($singularEntity) ? $singularEntity : $singularEntity->id;
        $collectionEntityId = is_string($collectionEntity) ? $collectionEntity : $collectionEntity->id;


        $this->collections[$collectionName][$singularEntityId] = $collectionEntityId;
    }

    /**
     * @todo RC function is slow and could use a lookup array or other mechanism
     *
     * @param string $collectionName
     * @param Entity|string $requestedCollectionEntity
     * @return array
     */
    public function getIdsInsideCollection(string $collectionName, Entity|string $requestedCollectionEntity) : array
    {
        if(!$this->isValidCollectionName($collectionName))
        {
            throw new \Exception("Invalid collection \"$collectionName\" ");
        }

        $requestedCollectionEntityId = is_string($requestedCollectionEntity) ? $requestedCollectionEntity : $requestedCollectionEntity->id;

        $returnArr = [];

        foreach($this->collections[$collectionName] as $singularEntityId => $collectionEntityId){
            if(strcmp($collectionEntityId, $requestedCollectionEntityId) === 0){
                $returnArr[] = $singularEntityId;
            }
        }

        return $returnArr;
    }


}