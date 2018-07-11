<?php
    require_once 'Autoloader.php';

    use WordInSyllable\Autoloader;
    use WordInSyllable\Execution\Execution;


    // instantiate the loader
    $loader = new Autoloader();
    // register the autoloader
    $loader->register();

    $loader->addNamespace('WordInSyllable\Execution', __DIR__ . '/Execution');
    $loader->addNamespace('WordInSyllable\ExecutionTimer', __DIR__ . '/ExecutionTimer');
    $loader->addNamespace('WordInSyllable\IntoSyllable', __DIR__ . '/IntoSyllable');
    $loader->addNamespace('WordInSyllable\IO_Classes', __DIR__ . '/IO_Classes');
    $loader->addNamespace('WordInSyllable\Logger', __DIR__ . '/Logger');

    $execute = new Execution();
    $execute->execute();
