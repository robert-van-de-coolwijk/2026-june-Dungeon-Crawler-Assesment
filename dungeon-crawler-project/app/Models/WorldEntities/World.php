<?php

namespace App\Models\WorldEntities;

class World
{

    protected array $entities = array();

    public function addEntity(Entity $entity){
        $this->entities[$entity->id] = $entity;
    }

}