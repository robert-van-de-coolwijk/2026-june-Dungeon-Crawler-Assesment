<?php

namespace App\Models\WorldEntities;

use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\MsgWrap;
use App\Core\Tools;
use App\Models\SingletonPattern;

class World extends SingletonPattern
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

    /**
     * Makes an array counting all the entities
     * If you want the total number of entities, do an array sum
     *
     * @return array
     */
    public function getNumberOfEntities() : array
    {
        $countArr = [];

        foreach($this as $entity){
            $className = get_class($entity);

            isset($countArr[$className]) ? $countArr[$className]++ : $countArr[$className] = 1;
        }

        return $countArr;
    }

    public function getStateOfTheWorld() : MsgWrap
    {
        $msgs = [];
        $entitiesCounts = $this->getNumberOfEntities();

        foreach($this->entities as $entityName => $entity){
            $entitiesCounts[] =  sprintf('%s: %i', $entityName, $entity);
        }

        $entitiesCounts[] = '---';
        $entitiesCounts[] =  sprintf('%s: %i', 'total', $entity);

        $msg = implode(PHP_EOL, $msgs);

        return Tools::MsgWrap($msg, ContType::P);
    }

}