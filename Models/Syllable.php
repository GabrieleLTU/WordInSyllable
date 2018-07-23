<?php
namespace  WordInSyllable\Models;

use WordInSyllable\Database\SqlQueryBuilder;
use WordInSyllable\Database\WorkWithDB;

class Syllable
{
    const TABLE_NAME = "syllable";
    const ATTRIBUTE_SYLLABLE_ID = "s_id";
    const ATTRIBUTE_SYLLABLE = "syllable";

    private $s_id;
    private $syllable;
    private $workWithDB;


    public function __construct(WorkWithDB $workWithDb, string $syllable = null)
    {
        $this->syllable = $syllable;
        $this->workWithDB = $workWithDb;//new WorkWithDB();
    }

    public function getSyllableData($condition):array
    {
            $query = (new SqlQueryBuilder())
                ->select([self::ATTRIBUTE_SYLLABLE_ID, self::ATTRIBUTE_SYLLABLE])
                ->from(self::TABLE_NAME)
                ->where($condition);

            return $this->workWithDB->runQuery($query);
    }

    public function getAllSyllablesData():array
    {
        $query = (new SqlQueryBuilder())
            ->select([self::ATTRIBUTE_SYLLABLE_ID, self::ATTRIBUTE_SYLLABLE])
            ->from(self::TABLE_NAME);

        return $this->workWithDB->runQuery($query);
    }

    public function insertSyllable(array $valuesByKey):void
    {
        $query = (new SqlQueryBuilder())
            ->insertInto(self::TABLE_NAME)
            ->values($valuesByKey);
        $this->workWithDB->runQuery($query);
    }

    public function updateSyllable(array $valuesByKey, $condition):void
    {
        $query = (new SqlQueryBuilder())
            ->update(self::TABLE_NAME)
            ->set($valuesByKey)
            ->where($condition);

        $this->workWithDB->runQuery($query);
    }

    public function deleteSyllable($condition):void
    {
        $query = (new SqlQueryBuilder())
            ->delete()
            ->from(self::TABLE_NAME)
            ->where($condition);

        $this->workWithDB->runQuery($query);
    }

    public function deleteAllSyllables():void
    {
        $query = (new SqlQueryBuilder())
            ->delete()
            ->from(self::TABLE_NAME);
        $this->workWithDB->runQuery($query);
    }
}