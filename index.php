<?php
require_once 'Autoloader.php';
// all in one file
use WordInSyllable\Autoloader;
use WordInSyllable\Database\WorkWithDB;
use WordInSyllable\Execution\Execution;
use WordInSyllable\IO_Classes\WorkWithAPI;


$loader = new Autoloader();
$loader->register();

$loader->addNamespace('WordInSyllable\Database', __DIR__ . '/Database');
$loader->addNamespace('WordInSyllable\Execution', __DIR__ . '/Execution');
$loader->addNamespace(
    'WordInSyllable\ExecutionTimer',
    __DIR__ . '/ExecutionTimer'
);
$loader->addNamespace('WordInSyllable\IntoSyllable', __DIR__ . '/IntoSyllable');
$loader->addNamespace('WordInSyllable\IO_Classes', __DIR__ . '/IO_Classes');
$loader->addNamespace('WordInSyllable\Controllers', __DIR__ . '/Controllers');
$loader->addNamespace('WordInSyllable\Models', __DIR__ . '/Models');
$loader->addNamespace('WordInSyllable\Logger', __DIR__ . '/Logger');

if (php_sapi_name() === 'cli') {
    $execute = new Execution();
} else {
    $execute = new WorkWithAPI();
}
$execute->execute();
