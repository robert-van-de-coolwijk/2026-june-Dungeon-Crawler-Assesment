<?php

use App\Controllers\Main;
use App\Core\MsgWrap\MsgWrap;
use App\Core\Tools;

require_once __DIR__ . '/../app/Autoload.php';

// game state monitor

$main = new Main();
$main->start();

$stateOfTheGameLines = $main->getGameState();

$line = new MsgWrap('');

foreach ($stateOfTheGameLines as $line) {
    echo sprintf('<%s class=" %s">%s</%s>', $line->contentType, $line->sentiment, $line->msg, $line->contentType);
}

