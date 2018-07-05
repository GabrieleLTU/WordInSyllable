<?php
require 'PrintFunctions.php';
require 'IntoSyllableFunctions.php';
date_default_timezone_set('Europe/Vilnius');//date_default_timezone_get();

 echo "Input: ";
 $input = fopen ("php://stdin","r");
 $word = trim(fgets($input));

 /*while (strlen ($input)<1) {
   $read = fopen ("php://stdin","r");
   $input = fgets($read);
 }*/

$position = array_fill (0,strlen ($word), 0);//int $start_index , int $num , mixed $value
//print_r($position); //print all array

$file = new SplFileObject("https://gist.githubusercontent.com/cosmologicon/1e7291714094d71a0e25678316141586/raw/006f7e9093dc7ad72b12ff9f1da649822e56d39d/tex-hyphenation-patterns.txt");

//$file = new SplFileObject("syllable_example.txt");

//$dtStart = date('Y-m-d H:i:s.u');
$start = microtime(true);
$dtStart = new DateTime();
while (!$file->eof()) {
    $syllable = $file->current();
    $position = CheckWord($word, trim($syllable), $position);
    $file->next();
}
//$date = new DateTime($dtStart);
$dtEnd = new DateTime();
$time_elapsed_secs = microtime(true) - $start;
echo $dtStart->diff($dtEnd)->format('%i:%f');
echo "\nResult: ";
//print_r($position); //print all array
printWordBySillable($word, $position);// print words into syllables

echo "\nExecute time(sec): ".$time_elapsed_secs;

//echo "\n".$date->diff($now)->format("%h:%i:%s");
//echo "\n".$date->diff($now)->format("%h:%i:%s");

?>
