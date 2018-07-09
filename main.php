<?php //namespace main;

//use ExecutionTimer as ;

require 'IntoSyllable\IntoSyllable.php';
include 'ExecutionTimer\ExecutionTimer.php';
require 'IO_Classes\IOinterface.php';
require 'IO_Classes\WorkWithFile.php';
require 'IO_Classes\WorkWithConsole.php';
require 'Execution.php';

$execute = new Execution;
$execute->execute();
?>
