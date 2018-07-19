<?php
    require_once 'Autoloader.php';

    use WordInSyllable\Autoloader;
    use WordInSyllable\Database\SqlQueryBuilder;
use WordInSyllable\Database\WorkWithDB;
use WordInSyllable\Execution\Execution;
    use WordInSyllable\IO_Classes\WorkWithAPI;

// instantiate the loader
    $loader = new Autoloader();
    // register the autoloader
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


    $restApi = new WorkWithAPI();
    $restApi->execute();

//    $execute = new Execution();
//    $execute->execute();

//    $query = (new SqlQueryBuilder)
//        ->update("word")
//        ->set(["word", "sylableWord"],["word1", "wo-rd1"])
//        ->where("w_id=2");
//
//     var_dump((string)$query);

