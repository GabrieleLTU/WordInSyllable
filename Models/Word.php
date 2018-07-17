<?php
    namespace WordInSyllable\IntoSyllable;

    use WordInSyllable\Database\SqlQueryBuilder;

    class Word
    {
        protected $word;
        protected $syllableWord;
        protected $w_id;

        function __construct($word = null)
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

        public function getWordData($condition)
        {
            if (is_null($this->word)) {
                $workWithDB = new WorkWithDB();
                $workWithDB->runQuery(
                    (new SqlQueryBuilder)
                    ->select(["w_id", "word", "syllableWord"])
                    ->from("word")
                    ->where($condition)
                );

            }
            return $this->word;
        }

        public function getAllWordsData()
        {
            $workWithDB = new WorkWithDB();
            return $workWithDB->runQuery(
                (new SqlQueryBuilder)
                    ->select(["w_id", "word", "syllableWord"])
                    ->from("word")
            );
        }

        public function printAllWordData()
        {
            echo "\nWord: " . $this->word;//.
            //"\nWord in syllable: ".$this->$syllableWord."\n";
        }
    }
