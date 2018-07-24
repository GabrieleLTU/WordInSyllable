<?php
namespace WordInSyllable\Models;

use WordInSyllable\Database\SqlQueryBuilder;
use WordInSyllable\Database\WorkWithDB;

class Syllablebyword
{
    const  TABLE_NAME = "syllablebyword";
    private $syllableId;
    private $wordId;
    private $workWithDB;

    public function __construct()
    {
        $this->workWithDB = new WorkWithDB();
    }

    public function getSyllablebyword():array
    {
        return [$this->syllableId, $this->wordId];
    }

    public function getSyllablebywordData($condition):?array
    {
            $query = (new SqlQueryBuilder())
                ->select(["w_id", "s_id"])
                ->from(self::TABLE_NAME)
                ->where($condition);

            return $this->workWithDB->runQuery($query);
    }

    public function getAllSyllablebywordsData():array
    {
        $query = (new SqlQueryBuilder())
            ->select(["w_id", "s_id"])
            ->from(self::TABLE_NAME);

        return $this->workWithDB->runQuery($query);
    }

    public function getAllSyllablesOfWord(array $where):array
    {
        try {
            return $this->workWithDB->selectInnerJoin(
                ["word", self::TABLE_NAME, "syllable"],
                ["syllable", "syllable.s_id", "word", "word.w_id"],
                [
                    "word.w_id=syllablebyword.w_id",
                    "syllablebyword.s_id=syllable.s_id"
                ],
                $where
            );
        } catch (\Exception $e) {
        }
    }

    public function insertSyllablebyword(array $valuesByKey):void
    {
        $query = (new SqlQueryBuilder())
            ->insertInto(self::TABLE_NAME)
            ->values($valuesByKey);
        $this->workWithDB->runQuery($query);
    }

    public function updateSyllablebyword(array $valuesByKey, $condition):void
    {
        $query = (new SqlQueryBuilder())
            ->update(self::TABLE_NAME)
            ->set($valuesByKey)
            ->where($condition);

        $this->workWithDB->runQuery($query);
    }

    public function deleteSyllablebyword($condition):void
    {
        $query = (new SqlQueryBuilder())
            ->delete()
            ->from(self::TABLE_NAME)
            ->where($condition);

        $this->workWithDB->runQuery($query);
    }

    public function deleteAllSyllablebyword():void
    {
        $query = (new SqlQueryBuilder())
            ->delete()
            ->from("self::TABLE_NAME");
        $this->workWithDB->runQuery($query);
    }
}