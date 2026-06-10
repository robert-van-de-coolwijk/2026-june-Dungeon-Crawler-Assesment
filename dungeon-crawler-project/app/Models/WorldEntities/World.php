<?php

namespace App\Models\WorldEntities;

use App\Models\Singleton;

class World extends Singleton
{
    /**
     * @var int Stores the highest id assigned to an entity
     * Consider 0 UNSET
     */
    private int $highestIdentifier = 0;

    protected array $entities = array();

    public function addEntity(Entity $entity){
        $this->entities[$entity->id->get()] = $entity;

        $id = $entity->id->getAsNumber();

        if($id > $this->highestIdentifier){
            $this->highestIdentifier = $id;
        }
    }

    public function numberOfEntities(){
        return count($this->entities);
    }

    public function getHighestIdentifier() : int
    {
        return $this->highestIdentifier;
    }

    /**
     * Just to fix singleton pattern return type
     * @return World
     */
    public static function getInstance(): World {
        return parent::getInstance();
    }

}