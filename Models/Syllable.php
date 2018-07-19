<?php
namespace  WordInSyllable\Models;

use WordInSyllable\Database\SqlQueryBuilder;
use WordInSyllable\Database\WorkWithDB;

class Syllable
{
    private $s_id;
    private $syllable;
    private $workWithDB;


    public function __construct($syllable = null)
    {
        $this->syllable = $syllable;
        $this->workWithDB = new WorkWithDB();
    }

    public function getSyllableData($condition):array
    {
            $query = (new SqlQueryBuilder)
                ->select(["s_id", "syllable"])
                ->from("syllable")
                ->where($condition);

            return $this->workWithDB->runQuery($query);
    }

    public function getAllSyllablesData():array
    {
        $query = (new SqlQueryBuilder)
            ->select(["s_id", "syllable"])
            ->from("syllable");

        return $this->workWithDB->runQuery($query);
    }

    public function insertSyllable(array $valuesByKey):void
    {
        $query = (new SqlQueryBuilder)
            ->insertInto("syllable")
            ->values($valuesByKey);
        $this->workWithDB->runQuery($query);
    }

    public function updateSyllable(array $valuesByKey, $condition):void
    {
        $query = (new SqlQueryBuilder)
            ->update("syllable")
            ->set($valuesByKey)
            ->where($condition);

        $this->workWithDB->runQuery($query);
    }

    public function deleteSyllable($condition):void
    {
        $query = (new SqlQueryBuilder)
            ->delete()
            ->from("syllable")
            ->where($condition);

        $this->workWithDB->runQuery($query);
    }

    public function deleteAllSyllables():void
    {
        $query = (new SqlQueryBuilder)
            ->delete()
            ->from("syllable");
        $this->workWithDB->runQuery($query);
    }
}