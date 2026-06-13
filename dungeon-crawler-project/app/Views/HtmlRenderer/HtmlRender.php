<?php

namespace App\Views\HtmlRenderer;

use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\MsgWrap;

class HtmlRender
{

    public static function printMsgWrap(array|MsgWrap $msg)
    {
        $renderArr = self::renderMsgWrap($msg);

        echo implode(PHP_EOL, $renderArr);
    }

    public static function renderMsgWrap(array|MsgWrap $msg){
        if(!is_array($msg)){
            $style = '';

            if($msg->contentType == ContType::P) {
                $style .= 'white-space: pre-line;';
            }

            return sprintf(
                '<%s class=" %s" style="%s">%s</%s>',
                $msg->contentType,
                $msg->sentiment,
                $style,
                $msg->msg,
                $msg->contentType
            );
        }

        $returnArray = [];

        foreach ($msg as $line){
            $returnArray[] = self::renderMsgWrap($line);
        }

        return $returnArray;
    }
}