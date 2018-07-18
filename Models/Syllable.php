<?php
namespace  WordInSyllable\Models;

use WordInSyllable\Database\SqlQueryBuilder;
use WordInSyllable\Database\WorkWithDB;

class Syllable
{
    private $s_id;
    private $syllable;
    private $workWithDB;


    function __construct($syllable = null)
    {
        $this->syllable = $syllable;
        $this->workWithDB = new WorkWithDB();
    }

    public function getSyllableData($condition)
    {
        if (is_null($this->syllable)) {
            $query = (new SqlQueryBuilder)
                ->select(["s_id", "syllable"])
                ->from("syllable")
                ->where($condition);

            return $this->workWithDB->runQuery($query);
        }
    }

    public function getAllSyllablesData()
    {
        $query = (new SqlQueryBuilder)
            ->select(["s_id", "syllable"])
            ->from("syllable");

        return $this->workWithDB->runQuery($query);
    }

    public function insertSyllable(array $valuesByKey)
    {
        $query = (new SqlQueryBuilder)
            ->insertInto("syllable")
            ->values($valuesByKey);
        return $this->workWithDB->runQuery($query);
    }

    public function updateSyllable(array $valuesByKey, $condition)
    {
        $query = (new SqlQueryBuilder)
            ->update("syllable")
            ->set($valuesByKey)
            ->where($condition);

        return $this->workWithDB->runQuery($query);
    }

    public function deleteSyllable($condition)
    {
        $query = (new SqlQueryBuilder)
            ->delete()
            ->from("syllable")
            ->where($condition);

        return $this->workWithDB->runQuery($query);
    }

    public function deleteAllSyllables()
    {
        $query = (new SqlQueryBuilder)
            ->delete()
            ->from("syllable");
        return $this->workWithDB->runQuery($query);
    }



}