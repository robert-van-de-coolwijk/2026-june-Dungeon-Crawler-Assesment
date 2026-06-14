<?php

namespace App\Models\WorldEntities;

use App\Config\Config;
use App\Config\FileCacherConfig;
use App\Core\Data\FileCacher;
use App\Core\Tools;
use App\Models\Game;
use App\Models\GameDataTypes\CollectionIdentifier;
use App\Models\GameDataTypes\EntityRelationManager;
use App\Models\GameDataTypes\GameDataType;
use App\Models\GameDataTypes\Identifier;
use App\Models\GameDataTypes\UniqueIdentifier;
use App\Models\GameDataTypes\ShortText;
use App\Models\GameDataTypes\Text;
use Exception;

class Entity {

    protected UniqueIdentifier $_id; // This number can become LARGE. A lot of entities like rooms will have a minimum of 4 entities associated with them.

    protected ShortText $_name;

    protected Text $_description;

    public CollectionIdentifier $_insideContainer;

    protected function __construct()
    {
        $this->_id = UniqueIdentifier::getNewIdentifier();

        $this->_name = new ShortText();
        $this->_description = new ShortText();

        $this->_insideContainer = new CollectionIdentifier(EntityRelationManager::Collection_Container_Entity);
    }

    /**
     * @throws Exception
     */
    protected function getSetSanityCheck(string $name, string $method) : bool
    {

        // The order of these checks is VERY important.
        // The next check will blindly expect things to exist so it RELIES on checks above it.
        switch ($name) {
            case !property_exists($this, $name):
                throw new Exception("Property '{$name}' does not exist");

            case is_null($this->{$name}):
                throw new Exception("Property '{$name}' is unset, please report this error for entity ID #{$this->id}");

//@todo RC rework this logic, it is flawed
//
//            case $method == 'get':
//                return method_exists($this, '__toString');
//
//            case $method == 'set':
//                return method_exists($this, 'set');

        }

        return true;

        throw new Exception("Something went horribly wrong. If you can read this message, report that property '{$name}' on entity ID #{$this->id} caused an ERROR the game was not designed for.");
    }

    protected static function getPropKey($name) : string {
        return "_$name";
    }

    /**
     * @throws Exception
     */
    public function __get(string $name)
    {
        $propName = self::getPropKey($name);

        if($this->getSetSanityCheck($propName, 'get')){
            return $this->{$propName}->__toString();
        }

        return null;
    }

    /**
     * @throws Exception
     */
    public function __set(string $name, string $value)
    {
        $propName = self::getPropKey($name);

        if($this->getSetSanityCheck($propName, 'set')){

            $success = $this->{$propName}->set($value);

            if($success && $this->{$propName} instanceof CollectionIdentifier) {
                $this->{$propName}->setCollectionRelation($this->_id, $value);
            }

            return $success;
        }

        return false;
    }

    public function __toString() : string
    {
        return implode(PHP_EOL, [
            $this->_name,
            $this->_description
        ]);
    }


    public function isInsideContainer()
    {
        return $this->_insideContainer->isUnset();
    }

    /**
     * Returns the names of portals, if a portal (entity) could not be found, it returns the id
     *
     * @return array Array of strings
     * @throws \Exception
     */
    protected function getCollectionEntitiesAssoc(string $collectionName) : array
    {
        $portalNames = [];
        $world = Game::getInstance()->getWorld();

        $portalIds = EntityRelationManager::getInstance()->getIdsInsideCollection($collectionName, $this);

        foreach ($portalIds as $portalId) {
            $entity = $world->getEntityById($portalId);

            $portalNames[$portalId] = !is_null($entity) ? $entity->name : $portalId;
        }

        return $portalNames;
    }

    /// SAVE AND RESTORE LOGIC \\\

    private static function getFileCacherContext(string $id) : string {
        return FileCacherConfig::EntityContext . '/' . $id;
    }

    public function save() : bool
    {
        $fileCacher = FileCacher::getInstance();

        $contextString = self::getFileCacherContext($this->id);
        $serializedEntity = serialize($this);

        return $fileCacher->put($contextString, $serializedEntity);
    }

    /**
     * @throws Exception
     */
    public static function restore(World $world, int $id) : ?Entity
    {
        $fileCacher = FileCacher::getInstance();

        $entityId = '#' . $id;

        // @todo Make more robust
        $contextString = self::getFileCacherContext($entityId);
        if(!$fileCacher->exists($contextString)){
            return null;
        }


        $serializedEntity = $fileCacher->get($contextString);

        $entity = unserialize($serializedEntity);

        $world->addEntity($entity);


        return $entity;
    }


}