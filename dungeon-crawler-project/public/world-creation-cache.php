<?php

use App\Controllers\Main;
use App\Views\HtmlRenderer\HtmlRender;

require_once __DIR__ . '/../app/Autoload.php';


// game state monitor

$main = new Main();
$main->start();

echo '<h2>Get from disk and put into cache</h2>';

HtmlRender::printMsgWrap($main->command('stats', ['']));

HtmlRender::printMsgWrap($main->command('restore', []));

HtmlRender::printMsgWrap($main->command('stats', ['']));

HtmlRender::printMsgWrap($main->command('save', ['cache']));

HtmlRender::printMsgWrap($main->command('stats', ['']));


echo '<h2>Clear current (in memory) world</h2>';

HtmlRender::printMsgWrap($main->command('oblivion', ['']));

HtmlRender::printMsgWrap($main->command('oblivion', ['yes']));

HtmlRender::printMsgWrap($main->command('stats', ['']));


echo '<h2>Get from cache</h2>';

HtmlRender::printMsgWrap($main->command('restore', ['cache']));

HtmlRender::printMsgWrap($main->command('stats', ['']));



// RESULT, we do this after we do the testing stuff


