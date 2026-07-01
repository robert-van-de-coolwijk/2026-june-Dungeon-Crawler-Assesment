<?php

// Source - https://stackoverflow.com/a/17244866
// Posted by Danack, modified by community. See post 'Timeline' for change history
// Retrieved 2026-07-01, License - CC BY-SA 3.0

define('EXTRACT_DIRECTORY', "../var/extractedComposer");


if (file_exists(EXTRACT_DIRECTORY.'/vendor/autoload.php') == true) {
    echo "<p>Extracted autoload already exists. Skipping phar extraction as presumably it's already extracted.</p>\n";
}
else{
    $composerPhar = new Phar("Composer.phar");
    //php.ini setting phar.readonly must be set to 0
    $composerPhar->extractTo(EXTRACT_DIRECTORY);
}

//This requires the phar to have been extracted successfully.
require_once (EXTRACT_DIRECTORY.'/vendor/autoload.php');

//Use the Composer classes
use Composer\Console\Application;
use Composer\Command\UpdateCommand;
use Symfony\Component\Console\Input\ArrayInput;

// change out of the webroot so that the vendors file is not created in
// a place that will be visible to the intahwebz
chdir('../');

//Create the commands
$input = new ArrayInput(array('command' => 'update'));

//Create the application and run it with the commands
$application = new Application();
$application->run($input);


?>
