<?php

if (php_sapi_name() !== 'cli') {
    exit;
}

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Tools;
use DungeonCrawlerCLI\App;

$app = new App();

// run command send form terminal
if(count($argv) > 1){

    $commandName = array_splice($argv, 1, 1)[0];
    $params = array_splice($argv, 1);

    $app->runCommand($commandName, $params);
}

// if continues mode is set
if(true) //@todo RC Turn into a config setting / param for program start
{
    //the program is held open to listen for more commands
    $app->setContinuesMode();

    while($app->isContinueMode())
    {
        sleep(1);

        $app->runCommandFromInput();
    }

    echo  PHP_EOL,  PHP_EOL, "Closing Dungeon Crawler, thanks for playing", PHP_EOL;

    sleep(3);
}

// for single command mode, this exit is silent and instantaneous

exit();