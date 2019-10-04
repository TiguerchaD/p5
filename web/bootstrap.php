<?php
const ROOT = __DIR__;


require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

const DEFAULT_APP = 'Frontend';

if(!isset($_GET['app']) || !file_exists(__DIR__.'/../src/App/'.$_GET['app'])) {
    $_GET['app'] = DEFAULT_APP;
}

$appClass = 'App\\' . $_GET['app'] . '\\' . $_GET['app'] . 'Application';

$app = new $appClass();

$app->run();
