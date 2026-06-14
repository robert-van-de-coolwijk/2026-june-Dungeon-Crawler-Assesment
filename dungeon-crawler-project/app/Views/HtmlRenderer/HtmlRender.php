<?php

namespace App\Views\HtmlRenderer;

use App\Core\MsgWrap\ContType;
use App\Core\MsgWrap\MsgWrap;
use App\Core\MsgWrap\Sentiment;

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

            //@todo RC move this to the style sheet

            if($msg->contentType == ContType::P) {
                $style .= 'white-space: pre-line;';
            }

            if($msg->sentiment == Sentiment::Important) {
                $style .= 'font-weight: bold;';
            }

            if($msg->sentiment == Sentiment::Vital) {
                $style .= 'font-weight: bold; color: orange;';
            }


            if($msg->sentiment == Sentiment::Error) {
                $style .= 'font-weight: bold; color: red; background-color: #F0F0F0;';
            }

            if($msg->sentiment == Sentiment::Command) {
                $style .= 'padding: 2px 0.5em; font-weight: bold; white-space: pre-line; border: 1px solid #333333; background-color: #F0F0F0;';
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