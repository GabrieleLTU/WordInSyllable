<?php

  class Word
  {
    protected $word;
  //  protected $syllableWord;

    function __construct($word)
    {
      $this->word = $word;
    }

    public function setWord($word)
    {
      $this->word = $word;
    }


    /*public function setsyllableWord($syllableWord)
    {
      $this->syllableWord = $syllableWord;
    }
    public function getSyllableWord()
    {
      return $this->syllableWord;
    }*/

    public function getWord()
    {
      return $this->word;
    }

    public function printAllWordData()
    {
      echo "\nWord: ".$this->word;//.
            //"\nWord in syllable: ".$this->$syllableWord."\n";
    }
  }

?>
