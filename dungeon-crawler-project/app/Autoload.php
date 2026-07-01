<?php

// This file is DESIGNED to function as a standalone
// and will attempt to handle autoload gracefully

$persistentGlobalObject = $GLOBALS;
$initDone = isset($persistentGlobalObject['init']) && $persistentGlobalObject['init'] === true;

if (!$initDone)
{
    $persistentGlobalObject['init_object'] = new Autoload();
    $persistentGlobalObject['init'] = true;
}


class Autoload
{

    private string $autoLoadPath = __DIR__ . '/../vendor/autoload.php';

    public function __construct()
    {

        if (file_exists($this->autoLoadPath)) {
            try {
                require_once $this->autoLoadPath;
            } catch (\Exception $e){
                echo sprintf('<p>Problem while loading Autoload file! → <pre>%s</pre></p>', $e->getMessage());
                echo sprintf('<p>Click <a href="%s">here</a> to try and fix the issue.</p>', './index.php');
            }

        } else {
            echo sprintf('<p>Autoload file %s not found!</p>', $this->autoLoadPath);
            echo sprintf('<p>Click <a href="%s">here</a> to try and fix the issue.</p>', './index.php');
        }

    }


}
