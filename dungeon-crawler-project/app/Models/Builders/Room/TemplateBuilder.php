<?php

namespace App\Models\Builders\Room;

use App\Core\Tools;
use App\Models\SingletonPattern;
use App\Models\WorldEntities\Room;
use stdClass;

class TemplateBuilder extends SingletonPattern
{
    public array $templateCache = [];


    public function createRoomFromBiome(string $biomeName) : room
    {
        $roomStruct = $this->getRandomRoomStruct($biomeName);

        $roomStruct->biome = $biomeName;

        return $this->createRoomFromStruct($roomStruct);
    }

    public function createRoomFromStruct(stdClass $roomStruct) : Room
    {
        $room = new Room();

        $room->biome = $roomStruct->biome;
        $room->name = $roomStruct->name ?? "unset";
        $room->description = $roomStruct->description ?? "unset";

        return $room;
    }

    private function getRandomRoomStruct($biomeName) : stdClass
    {
        $this->fillCache($biomeName);

        $randKey = array_rand($this->templateCache[$biomeName], 1);

        //Tools::debug($randKey);

        return (object)$this->templateCache[$biomeName][$randKey];
    }

    private function fillCache($biomeName) : void
    {
        if(isset($this->templateCache[$biomeName])){
            return;
        }

        // attempt to get file
        $initWorldTemplateDirectory = __DIR__ . '/../../../../data/init/world/template';

        $filePath = $initWorldTemplateDirectory . '/' . $biomeName . '.json';

        $json = sprintf('[{
                "name": "%s",
                "description": "An uninteresting %s"
            }]', $biomeName, $biomeName);


        $roomCache = json_decode($json);

        if(file_exists($filePath)) {
            $jsonArray = json_decode(file_get_contents($filePath), true);

            $roomCache = array_merge($roomCache, $jsonArray);
        }

        $this->templateCache[$biomeName] = $roomCache;
    }


    public static function getInstance() : TemplateBuilder {
        return new static();
    }
}