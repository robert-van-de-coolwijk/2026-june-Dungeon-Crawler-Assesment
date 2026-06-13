<?php

namespace App\Models\WorldEntities;

use App\Config\FileCacherConfig;
use App\Core\Data\FileCacher;
use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\MsgWrap;
use App\Core\Tools;
use App\Models\Game;
use App\Models\GameDataTypes\Identifier;
use Exception;
use stdClass;

class World
{
    /**
     * @var int Stores the highest id assigned to an entity
     * Consider 0 UNSET
     */
    private int $highestIdentifier = 0;

    protected array $entities = array();

    public function addEntity(Entity $entity)
    {
        $entityKey = (string)$entity->id;

        if(isset($this->entities[$entityKey]))
        {
            throw new \Exception("Already have a entity with key $entityKey");
        }

        // @todo RC see if this is a sensible option if not: remove
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
        $messages[] =  sprintf('%s: %d', 'total', $entity);

        $msg = implode(PHP_EOL, $messages);

        return Tools::MsgWrap($msg, ContType::P);
    }

    public function createPortal(Room $sourceRoom, Room $targetRoom, string $name, string $description = '')
    {
        $portal = new Portal($sourceRoom, $targetRoom);

        $this->addEntity($portal);
    }

    public function getRandomRoom() : ?Room
    {
        $rooms = [];

        foreach($this->entities as $entity){
            if($entity instanceof Room){
                $rooms[] = $entity;
            }
        }

        if(count($rooms) == 0)
        {
            return null;
        }

        return $rooms[array_rand($rooms)];
    }


    /// SAVE AND RESTORE LOGIC \\\

    private static function getFileCacherContext(string $id) : string {
        return FileCacherConfig::WorldContext . '_' . $id;
    }

    private function save()
    {
        $fileCacher = FileCacher::getInstance();

        $contextString = self::getFileCacherContext($this->id);

        $worldObject = new stdClass();
        $worldObject->highestIdentifier = $this->highestIdentifier;

        $fileCacher->put($contextString, $worldObject);
    }

    /**
     * World restore relies on the highestIdentifier being stored in an object saved to disk
     * The
     * @throws Exception
     */
    public function restore() : bool
    {
        $fileCacher = FileCacher::getInstance();

        // @todo Make more robust

        $contextString = self::getFileCacherContext(1); // in anticipation of being able to support multiple worlds

        $worldObject = $fileCacher->get($contextString);

        $world = Game::getInstance()->getWorld();

        // continue restoring where left
        for($i = $world->highestIdentifier; $i <= $worldObject->highestIdentifier; $i++){

            $entity = Entity::restore($world, $i);

            // defense against infinite loops and
            // out of memory problems due though a world being restored bigger than what the current server can support
            if($i >  FileCacherConfig::EntityRestoreLimit){
                throw new Exception(sprintf("Entity restore limit of %s reached", FileCacherConfig::EntityRestoreLimit));
                break;
            }
        }


        return true;
    }

}