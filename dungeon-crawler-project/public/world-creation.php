<?php


use App\Controllers\Main;
use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\MsgWrap;
use App\Core\Tools;
use App\Models\WorldEntities\Room;
use App\Views\HtmlRenderer\HtmlRender;

require_once __DIR__ . '/../vendor/autoload.php';

// game state monitor

$main = new Main();
$main->start();

HtmlRender::printMsgWrap($main->command('player', ['Mr Patchouli']));


HtmlRender::printMsgWrap($main->command('init', ['world', 'world_1.txt']));


HtmlRender::printMsgWrap($main->command('init', ['creatures', 'random_monsters.json', 100]));


HtmlRender::printMsgWrap($main->command('save', []));

// RESULT, we do this after we do the testing stuff
$stateOfTheGameLines = $main->getGameState();

$line = new MsgWrap('');

foreach ($stateOfTheGameLines as $line) {
    //echo sprintf('<pre class=" %s">%s</pre>', $line->sentiment, $line->msg);
    $style = '';

    if($line->contentType == ContType::P) {
        $style .= 'white-space: pre-line;';
    }

    echo sprintf('<%s class=" %s" style="%s">%s</%s>', $line->contentType, $line->sentiment, $style, $line->msg, $line->contentType);
}



//$room = new Room();
//
//$room->biome = "forest";
//$room->name = "unset";
//$room->description = "unset";