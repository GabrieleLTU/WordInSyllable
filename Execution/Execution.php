<?php
namespace WordInSyllable\Execution;

use WordInSyllable\IntoSyllable\WordInSyllable;
use WordInSyllable\IO_Classes\IOinterface;
use WordInSyllable\IO_Classes\WorkWithFile;
use WordInSyllable\IO_Classes\WorkWithConsole;
use WordInSyllable\Logger\FileLogger;

  class Execution
  {
    public function execute()
    {
      $loggerObject = new FileLogger('Data\logger_execute.txt');
      try {
      $wordsList = $this->getWords();
      $syllablesList = $this->getSyllables();
      $syllabledWordsList = $this->wordsInSyllableAlgorithm(
        $wordsList, $syllablesList, "Data\logger_execute.txt");
      $this->outputContent($syllabledWordsList);

    } catch (\Exception $e) {
      //sent $e to logger;
      echo "\nMessage:  ". $e->getMessage()."\n";
    }

    }

    public function wordsInSyllableAlgorithm($words, $syllables, $loggerFile = NULL)
    {
      $syllabledWordsList = [];

      foreach ($words as $word) {
        $oneWord = new WordInSyllable($word, $loggerFile);
        $syllabledWordsList[] = $oneWord->checkWordWithAllSyllables($syllables);
      }

      return $syllabledWordsList;
    }

    private function getWords ()
    {
      $wordsList = [];
      echo "c - input word(s) in console;\nf - input word(s) from file;\n";
      $input = fopen ("php://stdin","r");
      $choice = trim(fgets($input));

      switch ($choice) {
          case 'c':
                $wordsList = $this->getDataFromConsole();
      			  	break;
          case 'f':
                $wordsList = $this->getDataFromFile();
        		  	break;
      		default:
                $error = "Your choice is not correct. \n";
                throw new \Exception($error);
      			    break;
      		}
      if (is_null($wordsList)) {
        $error = 'There is no words...';
          throw new \Exception($error);
      }
      return $wordsList;
    }

    private function getSyllables()
    {
      $syllablesList = [];
      echo "c - input syllable(s) in console;\nf - input syllable(s) from file;\n";
      $input = fopen ("php://stdin","r");
      $choice = trim(fgets($input));

      switch ($choice) {
          case 'c':
                $syllablesList = $this->getDataFromConsole();
      			  	break;
          case 'f':
                $syllablesList = $this->getDataFromFile();
        			  break;
      		default:
                $error = "Your choice is not correct. \n";
                throw new \Exception($error);
      			    break;
      		}
      return $syllablesList;
    }

    private function getDataFromFile()
    {
      $file = new WorkWithFile;
      $file->setFile("https://gist.githubusercontent.com/cosmologicon/1e7291714094d71a0e25678316141586/raw/006f7e9093dc7ad72b12ff9f1da649822e56d39d/tex-hyphenation-patterns.txt");
      $file->inputContent();//Data\syllable_example.txt");//
      //$file->setFile('Data\filename.txt');
      return $file->getContent();
    }

    private function getDataFromConsole()
    {
      $fromConsole = new WorkWithConsole;
      $fromConsole->inputContent();
      $word = ($fromConsole->getContent())[0];
      return $fromConsole->getContent();
    }

    private function outputContent ($outputData)
    {
      echo "Output data to:\nc - n console;\nf -  file;\n";
      $input = fopen ("php://stdin","r");
      $choice = trim(fgets($input));

      switch ($choice) {
          case 'c':
                $output = new WorkWithConsole();
      			  	break;
          case 'f':
               $output = new WorkWithFile();
               $output->setFile('Data\filename.txt');
         	 		 break;
          case 'e':
          		 break;
      		default:
               $error = "Your choice is not correct. \n";
               throw new \Exception($error);
               break;
      		}

      if (isset($output)) {
        $output->setContent($outputData);
        $output->outputContent();
      }
    }
  }
?>