<?php


namespace App\Core\Data\MemoryCacher;

/**
 * Memory cacher through (older) APC implmentation
 */
class MemoryCacherMode_apc extends MemoryCacher implements MemoryCacherInterface
{


    protected function __construct()
    {
        parent::__construct();
    }


    public function put($fContextString, $fData, $fDebug = true) : void
    {
        $key = self::getContextPath($fContextString);

        $serializedData = self::serialize($fData);

        apc_store($key, $serializedData);

        parent::put($fContextString, $fData, $fDebug);
    }

    public function exists($fContextString) : bool
    {
        $key = self::getContextPath($fContextString);

        return apc_exists($key);
    }

    public function get($fContextString) : mixed
    {
        $key = self::getContextPath($fContextString);

        $serializedData = apc_fetch($key);


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
