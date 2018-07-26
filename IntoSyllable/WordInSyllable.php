<?php
namespace WordInSyllable\IntoSyllable;

use WordInSyllable\IntoSyllable\Word;
use WordInSyllable\IO_Classes\IOinterface;
use SplFileObject;

class WordInSyllable extends Word
{
    private $position;
    private $syllableWord;
    private $loggerFile;

    public function __construct($word, $loggerFile)
    {
        $this->word = $word;
        $this->loggerFile = new SplFileObject($loggerFile);
        //parameter: int $start_index , int $num , mixed $value
        $this->position = array_fill(0, strlen($this->word), 0);
    }

    public function checkWordWithAllSyllables($syllables)
    {
        //parameter: int $start_index , int $num , mixed $value
        $this->position = array_fill(0, strlen($this->word), 0);
        //var_dump($this->word);
        //var_dump($this->position);
        foreach ($syllables as $syllable) {
            $this->checkWord($syllable);
        }
        return $this->syllableWord;
    }

    public function checkWord($syllable)
    {
        $syllableNoNumber = preg_replace('/[\.\d\n\r]+/', '', $syllable);
        if ($syllable[0] === '.') {//at the start of the word
            if (stripos($this->word, $syllableNoNumber, 0) === 0) {
                //echo "\n syl.: ".$syllable." -> ";
                //change $position array
                $this->changePosition($syllable, 0);
            }
        } elseif ($syllable[strlen($syllable) - 1] === '.') { //at the end of the word
            $sylStart = strlen($this->word) - strlen($syllableNoNumber);

              // strrpos - Find the position of the last occurrence of a substring in a string
            $temp = strpos($this->word, $syllableNoNumber, $sylStart);
            if ($temp == $sylStart) {// stripos ($word, $syllableNoNumber, $sylStart)!=false
                $this->changePosition($syllable, $sylStart);
            }
        } else {//somewere in the word
            $sylStart = strpos($this->word, $syllableNoNumber, 0);
            while ($sylStart !== false) {
                //change $position array
                $this->changePosition($syllable, $sylStart);
            //echo "\n else syl.: ".$syllable." -> "; //print_r($position);
          //  var_dump($sylStart);
                $sylStart = stripos(
                    $this->word,
                    $syllableNoNumber,
                    ($sylStart + strlen($syllableNoNumber))
                );
            }
        }
        $this->saveWordSylables();
    }

    private function changePosition($syllable, $sylStart)
    {
        $position = $this->position;
        $syllable = str_replace('.', '', $syllable);
        if (($sylStart - 1) < 0) {
            $letterNumber = 0;
        } else {
            $letterNumber = $sylStart - 1;
        }
        for ($i = (($sylStart - 1) < 0) ? 1 : 0; $i < strlen($syllable); $i++) {
            if (is_numeric($syllable[$i])) {
                if ($position[$letterNumber] < $syllable[$i]) {
                    $position[$letterNumber] = $syllable[$i];
                }
            } else {
                $letterNumber++;
            }
        }
         $this->position = $position;
    }

    private function saveWordSylables()
    {
        $syllableWord = "";
        $this->position[strlen($this->word) - 1] = 0;
        $splitWord = str_split($this->word, 1);

        for ($i = 0; $i < strlen($this->word); $i++) {
            $syllableWord = $syllableWord . $splitWord[$i];
            if ($this->position[$i] % 2 > 0) {
                $syllableWord = $syllableWord . "-";
            }
        }
        $this->syllableWord = $syllableWord;
    }

    public function getSyllableWord()
    {
        return $this->syllableWord;
    }
}
