<?php

namespace App\Controllers\Commands;

use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\Sentiment;
use App\Core\Tools;
use App\Models\Builders\Room\TemplateBuilder;
use App\Models\CollectionPosition;
use App\Models\Game;
use App\Models\WorldEntities\Room;
use App\Models\WorldEntities\Creature;

class Init
{

    public static array $roomBiomeLetterToName = [
        // [ Walkable rooms ],
        'W' => 'waste',
        'P' => 'plain',
        'F' => 'forest',
        'm' => 'marsh',
        'M' => 'mountain',
        'w' => 'wetlands',
        'r' => 'river_shallow',

        // [ barriers ],
        'C' => 'cliff',
        'R' => 'river_deep',
        'S' => 'sea'
    ];

    /**
     * Init world
     *
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public static function world(array $params) : array
    {
        $game = Game::getInstance();

        $world = $game->getWorld();

        $msgs = [];


        // $params[0] == world
        $fileName = $params[1]; //@todo RC requires some serious sanitation. Consider giving options instead and let user choose from a number

        $inputFilePath = realpath(__DIR__ . "/../../../data/init/world/$fileName");

        if (!file_exists($inputFilePath)) {
            throw new \Exception(sprintf('File "%s" not found for world creation', $fileName));
        }

        // ingest file
        $fileContents = file_get_contents($inputFilePath);
        $fileContents = str_replace("\r", '', $fileContents);



        // put all letters into a 2-dimensional array
        $grid = explode("\n", $fileContents);

        // replace the string on this array position with an array
        foreach ($grid as $key => $line) {
            $grid[$key] = Tools::stringToArray($line);
        }

        //Tools::debug($grid);

        $roomGrid = [];
        $builder = TemplateBuilder::getInstance();

        // walk through all letters and make rooms
        foreach ($grid as $y => $row) {
            foreach($row as $x => $cell) {
                $key = self::gridKey($x, $y);

                $biomeString = self::getBiomeFromLetter($cell);

                $room = self::createRoom($builder, $biomeString);

                $roomGrid[$key] = $room;
                //Tools::debug($key);
                //Tools::debug($room->id);

                $world->addEntity($room);
            }
        }

        // walk through all the rooms again and make portals to rooms adjacent to rooms

        $northLimit = 0;
        $westLimit = 0;
        $southLimit = count($grid) - 1;


        //Tools::debug($grid);
        //Tools::debug($southLimit);

        foreach ($grid as $y => $row) {
            $eastLimit = count($row) - 1;

            foreach($row as $x => $cell) {

                // WARNING this loop uses a hybrid system,
                // where the outer loop can be used to easily determine the grid dimensions + traversal,
                // the $roomGrid has a quick key lookup and is a FLAT single dimensional array
                $key = self::gridKey($x, $y);
                $room = $roomGrid[$key];

                if($y > $northLimit) {
                    $adjacentPortal = $roomGrid[self::gridKey($x, $y - 1)];
                    $world->createPortal($room, $adjacentPortal, 'north');
                }

                if($x > $westLimit) {
                    $adjacentPortal = $roomGrid[self::gridKey($x - 1, $y)];
                    $world->createPortal($room, $adjacentPortal, 'west');
                }

                if($y < $southLimit) {
                    $adjacentPortal = $roomGrid[self::gridKey($x, $y + 1)];
                    $world->createPortal($room, $adjacentPortal, 'south');
                }

                if($x < $eastLimit) {
                    $adjacentPortal = $roomGrid[self::gridKey($x + 1, $y)];
                    $world->createPortal($room, $adjacentPortal, 'east');
                }

            }
        }

        $msgs[] = Tools::MsgWrap('World created', ContType::H1, Sentiment::Important);

        $player = $game->getPlayerOne();

        if(!is_null($player)){
            $game->placePlayerInRandomRoom($player);
        }

        $msgs[] = $world->getStateOfTheWorld();


        return $msgs;
    }


    private static function gridKey(int|string $x, int|string $y) : string {
        //Tools::debug(sprintf('%s#%s', $x, $y));

        return sprintf('%s#%s', $x, $y);
    }

    private static function getBiomeFromLetter(string $cell) : string
    {
        return self::$roomBiomeLetterToName[$cell] ?? Tools::arrayFirst(self::$roomBiomeLetterToName);

    }

    private static function createRoom(TemplateBuilder $builder, string $biomeString) : Room
    {
        return $builder->createRoomFromBiome($biomeString);
    }

    public static function creatures(array $params) : array
    {
        $game = Game::getInstance();

        $world = $game->getWorld();

        $msgs = [];



        // $params[0]       == world
        $fileName           = $params[1]; //@todo RC requires some serious sanitation. Consider giving options instead and let user choose from a number
        $numberOfCreatures  = is_numeric($params[2] ?? null) ? (int) $params[2] : 10;

        Tools::debugFilePath(__DIR__ . "/../../../data/init/creatures/$fileName");

        $inputFilePath = realpath(__DIR__ . "/../../../data/init/creatures/$fileName");

        if (!file_exists($inputFilePath)) {
            throw new \Exception(sprintf('File "%s" not found for creature creation', $fileName));
        }

        // ingest file
        $fileContents = file_get_contents($inputFilePath);

        $creaturesTemplateArray = json_decode($fileContents);

        if(!is_array($creaturesTemplateArray)) {
            throw new \Exception(sprintf('File "%s" is damaged, could not read properly', $fileName));
        }

        $builder = TemplateBuilder::getInstance();

        $uniqueRoomCount = [];

        for($i = 0; $i < $numberOfCreatures; $i++) {
            $creature = self::createCreature($builder, $creaturesTemplateArray[array_rand($creaturesTemplateArray)]);

            $randomRoom = $world->get(Room::class, CollectionPosition::Random);
            $randRoomId = $randomRoom->id;

            $creature->insideContainer = $randRoomId;

            //Tools::debug($creature->id, $creature->name, $randomRoom->id, $randomRoom->name);

            isset($uniqueRoomCount[$randRoomId]) ? $uniqueRoomCount[$randRoomId]++ :  $uniqueRoomCount[$randRoomId] = 1;
        }


        $msgs[] = Tools::MsgWrap(
            sprintf('Created %s creatures in %s unique rooms', $numberOfCreatures, count($uniqueRoomCount)),
            ContType::P,
            Sentiment::Important
        );


        $msgs[] = $world->getStateOfTheWorld();


        return $msgs;
    }

    private static function createCreature(TemplateBuilder $builder, object $createTemplate) : Creature
    {
        return $builder->createCreatureFromTemplateObject($createTemplate);
    }

}