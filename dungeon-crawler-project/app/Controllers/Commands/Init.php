<?php

namespace App\Controllers\Commands;

use App\Models\Game;

class Init
{

    /**
     * Init world
     *
     * - TXT Legend -
     * [ Walkable rooms ]
     * F: forest
     * M: meadow
     * W: waste
     * m: marsh
     * M: mountain
     * r: shallow river
     *
     * [ barriers ]
     * C: cliff
     * R: deep river
     * S: sea
     *
     *
     * @param Game $game
     * @param array $params
     * @return void
     */
    public static function world(Game $game, array $params) {
        // $params[0] == world
        $fileName = $params[1];

        $inputFilePath = realpath(__DIR__ . "/../../../data/init/world/$fileName");

        if (!file_exists($inputFilePath)) {
            throw new \Exception(sprintf('File "%s" not found for world creation', $fileName));
        }

        // ingest file
        // put all letters into a 2-dimensional array
        // walk through all letters and make rooms
        // walk through all the rooms again and make rooms to adjacent rooms that are not at the map.

    }

}