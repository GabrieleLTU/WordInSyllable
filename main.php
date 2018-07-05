<?php //namespace main;

//use ExecutionTimer as ;

require 'PrintFunctions.php';
require 'IntoSyllable\IntoSyllable.php';
require 'IntoSyllableFunctions.php';
include 'ExecutionTimer\ExecutionTimer.php';
require 'IO_Classes\IOinterface.php';
require 'IO_Classes\WorkWithFile.php';

/*$pathFile = new WorkWithFile;
$pathFile->setFile("https://gist.githubusercontent.com/cosmologicon/1e7291714094d71a0e25678316141586/raw/006f7e9093dc7ad72b12ff9f1da649822e56d39d/tex-hyphenation-patterns.txt");
$pathFile->readContent();//Data\syllable_example.txt");//
$pathFile->outputContent();
die();*/
 echo "Input: ";
 $input = fopen ("php://stdin","r");
 $word = trim(fgets($input));
//new:
  $inputWord = new WordInSyllable ($word);
  //var_dump($inputWord);
 /*while (strlen ($input)<1) {
   $read = fopen ("php://stdin","r");
   $input = fgets($read);
 }*/

$position = array_fill (0,strlen ($word), 0);//int $start_index , int $num , mixed $value
//print_r($position); //print all array

$file = new SplFileObject("https://gist.githubusercontent.com/cosmologicon/1e7291714094d71a0e25678316141586/raw/006f7e9093dc7ad72b12ff9f1da649822e56d39d/tex-hyphenation-patterns.txt");
//$file = new SplFileObject("Data\syllable_example.txt");
$ExecTime = new ExecutionTimer;
//$ExecTime = new ExecutionTimer();
$ExecTime->startTime();

while (!$file->eof()) {
    $syllable = $file->current();
    $position = CheckWord($word, trim($syllable), $position);
    //new:
    $inputWord->checkWord(trim($syllable));
    $file->next();
}

$ExecTime->endTime();

echo "\nResult: ";
print_r($position); //print all array
printWordBySillable($word, $position);// print words into syllables
var_dump($inputWord);
$ExecTime->getExecutionTime();

?>
