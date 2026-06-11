<?php

namespace App\Core;

use App\Core\MsgWrap\MsgWrap;
use Exception;


class Tools {

    public static $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');

    function __construct()
    {
        throw new Exception('This class should not be initiated through NEW. It is filled with static functions');
    }

    public static function getTimeStamp() : string
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Creates an instance of Message Wrapper,
     * see class for details
     *
     * @param string $msg
     * @param string|null $contentType
     * @param string|null $sentiment
     * @return MsgWrap
     */
    public static function MsgWrap(string $msg, string $contentType = '', ?string $sentiment = '') : MsgWrap
    {
        return new MsgWrap($msg, $contentType, $sentiment);
    }

    public static function getMsgWrapFn() : callable
    {
        return function(string $msg, string $contentType = '', string $sentiment = '') : MsgWrap
        {
            return new MsgWrap($msg, $contentType, $sentiment);
        };
    }

    public static function debug() : void
    {
        $bt = debug_backtrace();
        $caller = array_shift($bt);

        $argsArr = func_get_args();
        $argOut = '';

        foreach($argsArr as $arg){
            $argOut .= sprintf('<div style="border: 1px solid #333333;">%s</div>', var_export($arg, true));
        }

        echo sprintf(
            '<p>
                %s [Ln %s]<br />
                <pre>%s</pre>
            </p>',
            $caller['file'],
            $caller['line'],
            $argOut
        );

    }

    public static function debugError() : void
    {
        $bt = debug_backtrace();
        $caller = array_shift($bt);

        $argsArr = func_get_args();
        $argOut = '';

        foreach($argsArr as $arg){
            $argOut .= sprintf('%s\n', var_export($arg, true));
        }

        echo sprintf(
            '<div style="border: 1px solid #FF2222; color: #BB0000">
                %s [Ln %s]<br />
                <pre>%s</pre>
            </div>',
            $caller['file'],
            $caller['line'],
            $argOut
        );
    }

    public static function debugFilePath($fPath) : void
    {
        $out = sprintf(
            '
                <div>
                    Path: %s<br />
                    Real path: %s<br />
                    <b>Exists</b>: %s, <b>read</b>: %s, <b>write</b>: %s
                </div>
            ',
            $fPath,
            realpath($fPath),
            var_export(file_exists($fPath), true),
            var_export(is_readable($fPath), true),
            var_export(is_writable($fPath), true)
        );

        echo $out;
    }

    public static function echoNow($fInput) : void
    {
        echo $fInput;

        flush();
        ob_flush();
    }


    public static function getClassName(string|object $class) : string
    {
        $className = is_object($class) ? get_class($class) : $class;

        if ($pos = strrpos($className, '\\'))
        {
            return substr($className, $pos + 1);
        }

        return $pos;
    }

    public static function startsWith($haystack, $needle) : bool
    {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }

    public static function endsWith($haystack, $needle) : bool
    {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }

    public static function formatBytes($bytes, $precision = 2) : string
    {
        if(!is_numeric($bytes) || $bytes == 0){
            return $bytes;
        }

        $base = log($bytes, 1024);

        return round(pow(1024, $base - floor($base)), $precision) . self::$units[floor($base)];
    }

    public static function stringToArray(string $input) : array {
        return str_split($input);
    }

    public static function arrayFirst(array $arr)
    {
        return reset($arr);
    }

    public static function arrayLast(array $arr)
    {
        return $arr[array_key_last($arr)];
    }

    public static function cleanString($fInput){
        $fInput = trim($fInput);

        $fInput = str_replace(
            array('\n',     '\r'),
            array(' ',      ''),
            $fInput
        );


        return $fInput;
    }

    public static function buildHtmlTable($array){
        if(!is_array($array) || count($array) == 0) {
            return '';
        }

        // start table
        $html = '<table>';
        // header row
        $html .= '<tr>';
        foreach($array[0] as $key=>$value){
            $html .= '<th>' . htmlspecialchars($key) . '</th>';
        }
        $html .= '</tr>';

        // data rows
        foreach( $array as $key=>$value){
            if(is_object($value)){
                $value = (array)$value;
            }

            $html .= '<tr>';
            foreach($value as $key2=>$value2){
                $html .= '<td>' . htmlspecialchars($value2) . '</td>';
            }
            $html .= '</tr>';
        }

        // finish table and return it

        $html .= '</table>';
        return $html;
    }


}