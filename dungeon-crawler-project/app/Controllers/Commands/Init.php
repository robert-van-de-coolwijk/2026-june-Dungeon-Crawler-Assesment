<?php

namespace App\Controllers\Commands;

use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\Sentiment;
use App\Core\Tools;
use App\Models\Builders\Room\TemplateBuilder;
use App\Models\Game;
use App\Models\WorldEntities\Room;
use App\Models\WorldEntities\World;

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
     * @param Game $game
     * @param array $params
     * @return void
     * @throws \Exception
     */
    public static function world(array $params) : array
    {
        $game = Game::getInstance();

        $world = new World();
        $game->setWorld($world);

        $msgs = [];


        // $params[0] == world
        $fileName = $params[1];

        $inputFilePath = realpath(__DIR__ . "/../../../data/init/world/$fileName");

        if (!file_exists($inputFilePath)) {
            throw new \Exception(sprintf('File "%s" not found for world creation', $fileName));
        }

        // ingest file
        $fileContents = file_get_contents($inputFilePath);
        $fileContents = str_replace("\r", '', $fileContents);



        // put all letters into a 2-dimensional array
        $grid = explode("\n", $fileContents);

        foreach ($grid as $key => $line) {
            $grid[$key] = Tools::stringToArray($line);
        }

        Tools::debug($grid);

        $roomGrid = [];
        $builder = TemplateBuilder::getInstance();

        // walk through all letters and make rooms
        foreach ($grid as $x => $row) {
            foreach($row as $y => $cell) {
                $key = "$x#$y";

                $biomeString = self::getBiomeFromLetter($cell);

                $room = self::createRoom($builder, $biomeString);

                $roomGrid[$key] = $room;
                Tools::debug($key);
                Tools::debug($room->id);

                $world->addEntity($room);
            }
        }

        // walk through all the rooms again and make portals to rooms adjacent to rooms

        foreach ($grid as $x => $row) {
            foreach($row as $y => $cell) {
                // WARNING this loop uses a hybrid system,
                // where the outer loop can be used to easily determine that grid dimensions + traversal
                // as the $roomGrid has a quick key lookup and is a FLAT single dimensional array
                $key = "$x#$y";
                $room = $roomGrid[$key];

            }
        }

        $msgs[] = Tools::MsgWrap('World created', ContType::H1, Sentiment::Important);

        $msgs[] = $world->getStateOfTheWorld();


        return $msgs;
    }

    private static function getBiomeFromLetter(string $cell) : string
    {
        return self::$roomBiomeLetterToName[$cell] ?? Tools::arrayFirst(self::$roomBiomeLetterToName);

    }

    private static function createRoom(TemplateBuilder $builder, string $biomeString) : Room
    {
        return $builder->createRoomFromBiome($biomeString);
    }


}