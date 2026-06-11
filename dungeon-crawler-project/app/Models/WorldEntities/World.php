<?php

namespace App\Models\WorldEntities;

use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\MsgWrap;
use App\Core\Tools;
use App\Models\SingletonPattern;

class World
{
    /**
     * @var int Stores the highest id assigned to an entity
     * Consider 0 UNSET
     */
    private int $highestIdentifier = 0;

    protected array $entities = array();

    public function addEntity(Entity $entity){
        $this->entities[] = $entity;

        // @todo RC see if this is a sensible option if not: remove
//        $this->entities[$entity->id] = $entity;

//        $id = $entity->getIdAsNumber();

//        if($id > $this->highestIdentifier){
//            $this->highestIdentifier = $id;
//        }
    }

    public function numberOfEntities(){
        return count($this->entities);
    }

    public function getHighestIdentifier() : int
    {
        return $this->highestIdentifier;
    }

    public function setHighestIdentifier(int $id)
    {
        $this->highestIdentifier = $id;
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

        foreach($this->entities as $entity){
            $className = Tools::getClassName($entity);

            isset($countArr[$className]) ? $countArr[$className]++ : $countArr[$className] = 1;
        }

        return $countArr;
    }



    public function getStateOfTheWorld() : MsgWrap
    {
        $messages = [];
        $messages[] =  sprintf('%s: %d', 'Highest ID', $this->highestIdentifier);

        $entitiesCounts = $this->getNumberOfEntities();

        foreach($entitiesCounts as $entityName => $entity){
            $messages[] =  sprintf('%s: %d', $entityName, $entity);
        }

        $messages[] = '---';
        $messages[] =  sprintf('%s: %d', 'total', $entity);

        Tools::debug($messages);

        $msg = implode(PHP_EOL, $messages);

        return Tools::MsgWrap($msg, ContType::P);
    }



}