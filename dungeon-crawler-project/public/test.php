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

HtmlRender::printMsgWrap($main->command('restore', []));


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

HtmlRender::printMsgWrap($main->command('look', []));


HtmlRender::printMsgWrap($main->command('south', []));

HtmlRender::printMsgWrap($main->command('south', []));

HtmlRender::printMsgWrap($main->command('east', []));

HtmlRender::printMsgWrap($main->command('east', []));

HtmlRender::printMsgWrap($main->command('north', []));


HtmlRender::printMsgWrap($main->command('trouble', ['room']));

//$room = new Room();
//
//$room->biome = "forest";
//$room->name = "unset";
//$room->description = "unset";