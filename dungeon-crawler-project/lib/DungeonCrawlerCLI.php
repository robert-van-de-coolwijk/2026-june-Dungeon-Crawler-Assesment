<?php

if (php_sapi_name() !== 'cli') {
    exit;
}

require __DIR__ . '/../vendor/autoload.php';

use DungeonCrawlerCLI\App;

$app = new App();

// run command send form terminal
if(count($argv) > 1){
    $commandName = array_splice($argv, 1, 1)[0];
    $app->runCommand($commandName, $argv);
}

// if in continues mode, the line is held open to listen for more commands
if(true) { //@todo RC Turn into a config setting / param for program start
    $app->setContinuesMode();

    while($app->isContinueMode()){
        sleep(1);

        $app->runCommandFromInput();
    }

    echo  PHP_EOL,  PHP_EOL, "Closing Dungeon Crawler, thanks for playing", PHP_EOL;

    sleep(3);
}

exit();