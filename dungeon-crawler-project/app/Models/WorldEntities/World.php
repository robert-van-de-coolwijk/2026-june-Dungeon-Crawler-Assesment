<?php

namespace App\Models\WorldEntities;

use App\Config\MemoryCacherConfig;
use App\Core\Data\FileCacher;
use App\Core\Data\MemoryCacher\MemoryCacher;
use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\MsgWrap;
use App\Core\Tools;
use App\Models\CollectionPosition;
use App\Models\Game;
use App\Models\GameDataTypes\EntityRelationManager;
use Exception;
use stdClass;

class World
{
    public const StartIdentifier = 0;

    /**
     * @var int Stores the highest id assigned to an entity
     * Consider 0 UNSET
     */
    private int $highestIdentifier = self::StartIdentifier;

    protected array $entities = array();

    public function addEntity(Entity $entity)
    {
        $entityKey = (string)$entity->id;

        if(isset($this->entities[$entityKey]))
        {
            throw new \Exception("Already have a entity with key $entityKey");
        }

        // @todo RC key storage see if this is a sensible option if not: remove
        $this->entities[$entityKey] = $entity;

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
        $messages[] =  sprintf('%s: %d', 'total', array_sum($entitiesCounts));

        $msg = implode(PHP_EOL, $messages);

        return Tools::MsgWrap($msg, ContType::P);
    }

    public function createPortal(Room $sourceRoom, Room $targetRoom, string $name, string $description = '')
    {

        $portal = new Portal();

        $portal->name = $name;
        $portal->description = $description;
        $portal->source = $sourceRoom->id;
        $portal->target = $targetRoom->id;

        $this->addEntity($portal);

        // validation checks

//        Tools::debug(
//            'Portal ', $portal->name,
//            'Target ', $targetRoom->name,
//            'Source ', $sourceRoom->name,
//            'Source, portals: ', var_export($sourceRoom->getPortalNames(), true),
//        );
    }


    /// SAVE AND RESTORE LOGIC \\\

    private static function getFileCacherContext(string $worldId) : string {
        return MemoryCacherConfig::WorldContext . $worldId;
    }

    public function save(bool $toCache = false) : bool
    {
        $fileCacher = $toCache ? FileCacher::getInstance() : MemoryCacher::getMemoryCacherObject();

        $contextString = $this->getFileCacherContext(1); // in anticipation of being able to support multiple worlds

        $worldObject = new stdClass();
        $worldObject->highestIdentifier = $this->highestIdentifier;

        $success = $fileCacher->put($contextString, $worldObject);

        foreach($this->entities as $entity){
            $success = $success && $entity->save();
        }

        return $success;
    }

    /**
     * World restore relies on the highestIdentifier being stored in an object saved to disk
     * The
     * @throws Exception
     */
    public function restore(bool $fromCache = false) : bool
    {
        $fileCacher = $fromCache ? FileCacher::getInstance() : MemoryCacher::getMemoryCacherObject();

        // @todo Make more robust

        $contextString = self::getFileCacherContext(1); // in anticipation of being able to support multiple worlds

        if(!$fileCacher->exists($contextString)){
            throw new \Exception("Can't restore World. No data found.");
        }

        $worldObject = $fileCacher->get($contextString);

        //Tools::debug($worldObject);


        // continue restoring where left
        for($i = $this->highestIdentifier; $i <= $worldObject->highestIdentifier; $i++){

            Entity::restore($this, $i);

            // defense against infinite loops and
            // out of memory problems due though a world being restored bigger than what the current server can support
            if($i >  MemoryCacherConfig::EntityRestoreLimit){
                throw new Exception(sprintf("Entity restore limit of %s reached", MemoryCacherConfig::EntityRestoreLimit));
                break;
            }
        }

        // rebuild EntityRelationManager
        foreach($this->entities as $entity){


            switch(Tools::getClassName($entity)){
                case Tools::getClassName(Portal::class):

                    $entity->source = (string)$entity->source;

                    break;
            }

        }



        return true;
    }

    public function deleteSave() : bool
    {
        throw new Exception("Not implemented yet");
    }

    public function destroy(): bool
    {
        $entityRelationManager = EntityRelationManager::getInstance();

        if(!$entityRelationManager->clear()){
            return false;
        }

        $this->entities = array();

        $this->highestIdentifier = self::StartIdentifier;

        return true;
    }

    public function getEntityById(string $id) : ?Entity
    {
        return isset($this->entities[$id]) ? $this->entities[$id] : null;
    }

    public function get(?string $class = null, string $pos = CollectionPosition::First) : ?Entity
    {
        $className = is_string($class) ? Tools::getClassName($class) : null;

//        Tools::debug($className);
//        Tools::debug($pos);

        switch($pos){
            case CollectionPosition::First:
                return $this->first($className);

            case CollectionPosition::Last:
                return $this->last($className);

            case CollectionPosition::Random:
                return $this->getRandom($className);

            default:
                throw new Exception("Get failed, given position \"$pos\" does not exist");
        }

        return null;
    }

    protected function first(?string $class = null) : ?Entity
    {
        if(is_null($class)){
            return Tools::arrayFirst($this->entities);
        }

        //Tools::debug($class);

        foreach($this->entities as $entity){
            //Tools::debug($entity);

            if(strcmp(Tools::getClassName($entity), $class) == 0){
                return $entity;
            }
        }


        //Tools::debug('Failed to return something');

        return null;
    }

    protected function last(?string $class = null) : ?Entity
    {
        if(is_null($class)){
            return Tools::arrayFirst($this->entities);
        }

        for ($currentElement = end($this->entities); ($currentKey = key($this->entities)) !== null; $currentElement = prev($this->entities)) {

            $entity = $this->entities[key($this->entities)];

            if(strcmp(Tools::getClassName($entity), $class) == 0){
                return $entity;
            }
        }

        return null;
    }

    protected function getRandom(?string $class = null) : ?Entity
    {
        $entityArr = [];

        if(is_string($class)){

            foreach($this->entities as $entity){
                if(strcmp(Tools::getClassName($entity), $class) == 0){
                    $entityArr[] = $entity;
                }
            }

        } else {
            $entityArr = $this->entities;
        }


        if(count($entityArr) == 0)
        {
            return null;
        }

        return $entityArr[array_rand($entityArr, 1)];
    }




}