<?php
namespace WordInSyllable\Models;

use WordInSyllable\Database\SqlQueryBuilder;
use WordInSyllable\Database\WorkWithDB;

class Syllablebyword
{
    private $s_id;
    private $w_id;
    private $workWithDB;

    function __construct()
    {
        $this->workWithDB = new WorkWithDB();
    }

    public function getSyllablebyword():array
    {
        return [$this->s_id, $this->w_id];
    }

    public function getSyllablebywordData($condition):?array
    {
            $query = (new SqlQueryBuilder)
                ->select(["w_id", "s_id"])
                ->from("syllablebyword")
                ->where($condition);

            return $this->workWithDB->runQuery($query);
    }

    public function getAllSyllablebywordsData():array
    {
        $query = (new SqlQueryBuilder)
            ->select(["w_id", "s_id"])
            ->from("syllablebyword");

        return $this->workWithDB->runQuery($query);
    }

    public function getAllSyllablesOfWord(string $word):array
    {
        try {
            return $this->workWithDB->selectInnerJoin(
                ["word", "syllablebyword", "syllable"],
                ["syllable", "syllable.s_id"],
                ["word.w_id=syllablebyword.w_id", "syllablebyword.s_id=syllable.s_id"],
                ["word='$word'"]
            );
        } catch (\Exception $e) {
        }
    }

    public function insertSyllablebyword(array $valuesByKey):void
    {
        $query = (new SqlQueryBuilder)
            ->insertInto("syllablebyword")
            ->values($valuesByKey);
        $this->workWithDB->runQuery($query);
    }

    public function updateSyllablebyword(array $valuesByKey, $condition):void
    {
        $query = (new SqlQueryBuilder)
            ->update("syllablebyword")
            ->set($valuesByKey)
            ->where($condition);

        $this->workWithDB->runQuery($query);
    }

    public function deleteSyllablebyword($condition):void
    {
        $query = (new SqlQueryBuilder)
            ->delete()
            ->from("syllablebyword")
            ->where($condition);

        $this->workWithDB->runQuery($query);
    }

    public function deleteAllSyllablebyword():void
    {
        $query = (new SqlQueryBuilder)
            ->delete()
            ->from("syllablebyword");
        $this->workWithDB->runQuery($query);
    }
}