<?php
namespace WordInSyllable\IntoSyllable;

  use WordInSyllable\IntoSyllable\Word;
  use WordInSyllable\IO_Classes\IOinterface;
  use SplFileObject;

  final class WordInSyllableRegExp extends Word
  {
    private $position;
    private $syllableWord;
    private $loggerFile;

    function __construct($word, $loggerFile)
    {
      $this->word = $word;
      $this->loggerFile = new SplFileObject($loggerFile);
      //parameter: int $start_index , int $num , mixed $value
      $this->position = array_fill (0, strlen($this->word), 0);
    }

    public function checkWordWithAllSyllables($syllables)
    {
      //parameter: int $start_index , int $num , mixed $value
      $this->position = array_fill (0, strlen($this->word), 0);
      foreach ($syllables as $syllable) {
        $this->checkWord($syllable);
      }
      return $this->syllableWord;
    }

    public function checkWord($syllable)
    {
      $syllableNoNumber = preg_replace('/[\.\d\n\r]+/', '', $syllable);
      preg_match_all('/('.$syllableNoNumber.')/', $this->word, $matches, PREG_OFFSET_CAPTURE);

      if ($syllable[0] === '.') {//at the start of the word
        preg_match('/^('.$syllableNoNumber.')/', $this->word, $matches, PREG_OFFSET_CAPTURE);

        if (!empty($matches[0])) {
          $this->changePosition($syllable, 0);
        }
      } else if ($syllable[strlen($syllable)-1] === '.') { //at the end of the word
          preg_match('/('.$syllableNoNumber.')$/', $this->word, $matches, PREG_OFFSET_CAPTURE);
          if (!empty($matches[0])) {
            $this->changePosition($syllable, $matches[0][1]);
          }
      } else {//somewere in the word
      //  preg_match_all('/('.$syllableNoNumber.')/', $this->word, $matches, PREG_OFFSET_CAPTURE);
        for ($i=0; $i < count($matches[0]); $i++) {
          $this->changePosition($syllable, $matches[0][$i][1]);
        }
      }
      $this->saveWordSylables();
    }

   private function checkAnywereInWord ($syllable, $searchStart)
    {
      $syllableNoNumber = preg_replace('/[\.\d\n\r]+/', '', $syllable);
      preg_match_all('/('.$syllableNoNumber.')/', $this->word, $matches, PREG_OFFSET_CAPTURE);
      if (!empty($matches[0])) {
        for ($i=0; $i < count($matches[0]); $i++) {
          $this->changePosition($syllable, $matches[0][$i][1]);
        }
      }
    }

    private function changePosition($syllable, $sylStart)
    {
      $position = $this->position;
      $syllable = str_replace('.', '', $syllable);
      if (($sylStart-1)<0) {
        $letterNumber=0;
      }
      else {
        $letterNumber = $sylStart - 1;
      }
      for ($i = (($sylStart-1) < 0) ? 1 : 0 ; $i < strlen($syllable); $i++) {
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

    private function saveWordSylables ()
    {
      $syllableWord = "";
      //$this->position[strlen($this->word)] = 0;
      $splitWord = str_split($this->word, 1);

      for ($i=0; $i < strlen($this->word); $i++) {
        $syllableWord = $syllableWord.$splitWord[$i];
        if ($this->position[$i]%2 > 0) {
          $syllableWord = $syllableWord."-";
        }
      }
      $this->syllableWord = $syllableWord;
    }

    public function getSyllableWord()
    {
      return $this->syllableWord;
    }
}
?>
