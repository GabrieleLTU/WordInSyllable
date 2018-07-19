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

    public function __construct($word = null)
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

    public function getWord():string
    {
        return $this->word;
    }

    public function getWordData($condition):?array
    {
        if (is_null($this->word)) {
            $query = (new SqlQueryBuilder)
                ->select(["w_id", "word", "syllableWord"])
                ->from("word")
                ->where($condition);

            return $this->workWithDB->runQuery($query);
        }
    }

    public function getAllWordsData():array
    {
        $query = (new SqlQueryBuilder)
            ->select(["w_id", "word", "syllableWord"])
            ->from("word");

        return $this->workWithDB->runQuery($query);
    }

    public function insertWord(array $valuesByKey):void
    {
        $query = (new SqlQueryBuilder)
            ->insertInto("word")
            ->values($valuesByKey);
        $this->workWithDB->runQuery($query);
    }

    public function updateWord(array $valuesByKey, $condition):void
    {
        $query = (new SqlQueryBuilder)
            ->update("word")
            ->set($valuesByKey)
            ->where($condition);

        $this->workWithDB->runQuery($query);
    }

    public function deleteWord($condition):void
    {
        $query = (new SqlQueryBuilder)
            ->delete()
            ->from("word")
            ->where($condition);

        $this->workWithDB->runQuery($query);
    }

    public function deleteAllWords():void
    {
        $query = (new SqlQueryBuilder)
            ->delete()
            ->from("word");
         $this->workWithDB->runQuery($query);
    }

    public function printAllWordData()
    {
        echo "\nWord: " . $this->word;//.
        //"\nWord in syllable: ".$this->$syllableWord."\n";
    }
}
