<?php
  require 'IntoSyllable\Word.php';

  class WordInSyllable extends Word
  {
    private $position;
    private $syllableWord;

    function __construct($word)
    {
      $this->word = $word;
      //parameter: int $start_index , int $num , mixed $value
      $this->position = array_fill (0,strlen ($this->word), 0);
      //echo"new";print_r($this->position);
    }

    public function checkWordWithAllSyllables ($syllables)
    {
      echo "1.Checking: ".$this->word.";\n pos: \n".print_r($this->position)."\nsw.:".$this->syllableWord."\n";
      foreach ($syllables as $syllable)
      {
        $this->checkWord($syllable);
      }

      echo "2.Checking: ".$this->word.";\n pos: \n".print_r($this->position)."\nsw.:".$this->syllableWord."\n";
      return $this->syllableWord;
    }

    public function checkWord ($syllable)
    {
      //echo $syllable;
      $syllableNoNumber = preg_replace('/[0-9]+/', '', $syllable);
      $syllableNoNumber = str_replace('.','',$syllableNoNumber);
      //echo $syllableNoNumber;

      if ($syllable[0]=='.')//at the start of the word
      {
        if (stripos ( $this->word, $syllableNoNumber, 0)===0)//$word[0]===$syllableNoNumber[0])
        //if(substr_compare($word, $syllableNoNumber, 0, strlen($syllableNoNumber)))
        {
          echo "\n syl.: ".$syllable." -> ";
          //change $position array
          $this->changePosition($syllable, 0);

        }
      }
      else if ($syllable[strlen ($syllable)-1]==='.') //at the end of the word
      {
        $sylStart = strlen($this->word)-strlen($syllableNoNumber);
        $temp = stripos ($this->word, $syllableNoNumber, $sylStart);
        if ($temp==$sylStart)// stripos ($word, $syllableNoNumber, $sylStart)!=false
        {
          echo "\n syl.: ".$syllable." -> ";
          //change $position array
          $this->changePosition($syllable, $sylStart);

        }
      }
      else //somewere in the word
      {
        //$this->checkAnywereInWord ($syllable, 0);

        $sylStart = stripos ( $this->word, $syllableNoNumber, 0);
        while ($sylStart!==FALSE)
        {
          //change $position array
          $this->changePosition($syllable, $sylStart);
          echo "\n syl.: ".$syllable." -> "; //print_r($position);
          $sylStart = stripos ( $this->word, $syllableNoNumber, $sylStart+strlen($syllableNoNumber));
        }

      }

    }

   private function checkAnywereInWord ($syllable, $searchStart)
    {
      $syllableNoNumber = preg_replace('/[0-9]+/', '', $syllable);
      $syllableNoNumber = str_replace('.','',$syllableNoNumber);
      $sylStart = stripos ( $this->word, $syllableNoNumber, $searchStart);
      while ($sylStart!==FALSE)
      {
        //change $position array
        $this->changePosition($syllable, $sylStart);
        //echo "\n syl.: ".$syllable." -> "; //print_r($position);
        $sylStart = stripos ( $this->word, $syllableNoNumber, $sylStart+strlen($syllableNoNumber));
      }
    }

    private function changePosition($syllable, $sylStart)
    {
      $position = $this->position;
      $syllable = str_replace('.','',$syllable);
      if (($sylStart-1)<0) {
        $letterNumber=0;
      }
      else {
        $letterNumber=$sylStart-1;
      }
      for ($i = (($sylStart-1)<0) ? 1 : 0 ; $i < strlen($syllable); $i++) {
        if(is_numeric($syllable[$i]))
        {
          if ($position[$letterNumber]<$syllable[$i]) {
            $position[$letterNumber] = $syllable[$i];
          }
        }
        else {
          $letterNumber++;
        }
      }
      $this->saveWordSylables();
     $this->position = $position;
    }

    private function saveWordSylables ()
    {
      $syllableWord = "";
      $this->position[strlen($this->word)]=0;
      $splitWord = str_split($this->word,1);
      for ($i=0; $i < strlen($this->word); $i++)
      {
        $syllableWord=$syllableWord.$splitWord[$i];
        //echo $word[$i];

        if ($this->position[$i]%2>0)
        {
          $syllableWord=$syllableWord."-";
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
