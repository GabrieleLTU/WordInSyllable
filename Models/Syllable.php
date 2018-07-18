<?php
namespace  WordInSyllable\Models;

use WordInSyllable\Database\SqlQueryBuilder;
use WordInSyllable\Database\WorkWithDB;

class Syllable
{
    private $s_id;
    private $syllable;


    public function __construct()
    {
    }

    public function getAllSyllableData()
    {
        $workWithDB = new WorkWithDB();
        return $workWithDB->runQuery(
            (new SqlQueryBuilder)
                ->select(["s_id", "syllable"])
                ->from("syllable")
        );
    }



}