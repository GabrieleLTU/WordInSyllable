<?php //namespace main;

//use ExecutionTimer as ;

require 'PrintFunctions.php';
require 'IntoSyllable\IntoSyllable.php';
require 'IntoSyllableFunctions.php';
include 'ExecutionTimer\ExecutionTimer.php';
require 'IO_Classes\IOinterface.php';
require 'IO_Classes\WorkWithFile.php';
require 'IO_Classes\WorkWithConsole.php';
require 'Execution.php';

$execute = new Execution;
$execute->execute();

?>
