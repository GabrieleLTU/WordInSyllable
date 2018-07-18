<?php
    namespace WordInSyllable\Models;

    use WordInSyllable\Database\SqlQueryBuilder;
    use WordInSyllable\Database\WorkWithDB;

    class Word
    {
        protected $word;
        protected $syllableWord;
        protected $w_id;
        private $workWithDB;

        function __construct($word = null)
        {
            $this->word = $word;
            $this->workWithDB = new WorkWithDB();
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
                return $this->workWithDB->runQuery(
                    (new SqlQueryBuilder)
                    ->select(["w_id", "word", "syllableWord"])
                    ->from("word")
                    ->where($condition)
                );

            }
        }

        public function getAllWordsData()
        {
            return $this->workWithDB->runQuery(
                (new SqlQueryBuilder)
                    ->select(["w_id", "word", "syllableWord"])
                    ->from("word")
            );
        }

        public function updateWord()
        {
        }

        public function deleteWord($condition)
        {
            return $this->workWithDB->runQuery(
                (new SqlQueryBuilder)
                    ->delete()
                    ->from("word")
                    ->where($condition)
            );
        }

        public function deleteAllWords()
        {
            return $this->workWithDB->runQuery(
                (new SqlQueryBuilder)
                    ->delete()
                    ->from("word")
            );
        }

        public function printAllWordData()
        {
            echo "\nWord: " . $this->word;//.
            //"\nWord in syllable: ".$this->$syllableWord."\n";
        }
    }
