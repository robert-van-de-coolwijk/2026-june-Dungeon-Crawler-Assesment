<?php


namespace App\Core\Data\MemoryCacher;

/**
 * Implements the PHP APCu interface
 * https://www.php.net/manual/en/book.apcu.php
 */
class MemoryCacherMode_apcu extends MemoryCacher implements MemoryCacherInterface
{

    protected function __construct()
    {
        parent::__construct();

        if(function_exists('apcu_enabled') && apcu_enabled()){
            // everything is good (do good flow)
        } else {
            throw new \Exception("APCu is not available");
        }
    }


    public function put($fContextString, $fData, $fDebug = true) : void
    {
        $key = self::getContextPath($fContextString);

        $serializedData = self::serialize($fData);

        apcu_store($key, $serializedData);
    }

    public function exists($fContextString) : bool
    {
        $key = self::getContextPath($fContextString);

        return apcu_exists($key);
    }

    public function get($fContextString) : mixed
    {
        $key = self::getContextPath($fContextString);

        $serializedData = apcu_fetch($key);


        $serializedDataTemp = trim($serializedData);


        $data = self::unserialize($serializedDataTemp);

        if (self::DebugMode == true) {
            echo '<pre style="border: 1px solid red;">';
            echo var_dump($serializedData);
            echo '</pre>';

            echo '<pre style="border: 1px solid blue;">';
            echo var_dump($serializedDataTemp);
            echo '</pre>';

            echo '<pre style="border: 1px solid green;">';
            echo var_dump($data);
            echo '</pre>';
        }

        return $data;
    }

}
