<?php
/**
 * Created by PhpStorm.
 * User: Gabrielė.Valaikaitė
 * Date: 7/23/18
 * Time: 1:04 PM
 */

namespace WordInSyllable\tests;

require __DIR__ . '/../Models/Syllable.php';
require __DIR__ . '/../Database/WorkWithDB.php';
require __DIR__ . '/../Database/SqlQueryBuilder.php';

use PHPUnit\Framework\TestCase;
use WordInSyllable\Database\WorkWithDB;
use WordInSyllable\Database\SqlQueryBuilder;
use WordInSyllable\Models\Syllable;

class SyllableTests extends TestCase
{
    private $syllable;
    private $workWithDB;

    public function setUp():void
    {
        $this->workWithDB = $this->createMock(WorkWithDB::class);
        $this->syllable = new Syllable($this->workWithDB);
        //$this->sqlQueryBuilder = $this->createMock(SqlQueryBuilder::class);
    }

    public function testGetSyllableData():void
    {
        $expected = [["s_id" => '1', "syllable" => ".ach4"]];
        $condition = ["s_id = 1"]; // ["syllable = '.ach4'"]

        $query = (new SqlQueryBuilder())
            ->select([Syllable::ATTRIBUTE_SYLLABLE_ID, Syllable::ATTRIBUTE_SYLLABLE])
            ->from(Syllable::TABLE_NAME)
            ->where($condition);

        $this->workWithDB->method('runQuery')
            ->with($query)
            ->willReturn($expected);

        $output = $this->syllable->getSyllableData($condition);
        $this->assertSame($expected, $output);
    }

    public function testGetAllSyllablesData():void
    {
        $expected = [["s_id" => '1', "syllable" => ".ach4"], ["s_id" => '2', "syllable" => ".ad4der"]];

        $query = (new SqlQueryBuilder())
            ->select([Syllable::ATTRIBUTE_SYLLABLE_ID, Syllable::ATTRIBUTE_SYLLABLE])
            ->from(Syllable::TABLE_NAME);

        $this->workWithDB->method('runQuery')
            ->with($query)
            ->willReturn($expected);

        $output = $this->syllable->getAllSyllablesData();
        $this->assertSame($expected, $output);
    }

    public function testInsertSyllable():void
    {
        $valuesByKey = [["syllable" => ".ach4"]];

        $query = (new SqlQueryBuilder())
            ->insertInto(Syllable::TABLE_NAME)
            ->values($valuesByKey);

        $this->workWithDB
            ->expects($this->once())
            ->method('runQuery')
            ->with($query);

        $this->syllable->insertSyllable($valuesByKey);
    }

    public function testUpdateSyllable():void
    {
        $valuesByKey = ["syllable" => ".ach4"];
        $condition = ["s_id='1'"];

        $query = (new SqlQueryBuilder())
            ->update(Syllable::TABLE_NAME)
            ->set($valuesByKey)
            ->where($condition);

        $this->workWithDB
            ->expects($this->once())
            ->method('runQuery')
            ->with($query);

        $this->syllable->updateSyllable($valuesByKey, $condition);
    }

    public function testDeleteSyllable():void
    {
        $condition = ["s_id='1'"];

        $query = (new SqlQueryBuilder())
            ->delete()
            ->from(Syllable::TABLE_NAME)
            ->where($condition);

        $this->workWithDB
            ->expects($this->once())
            ->method('runQuery')
            ->with($query);

        $this->syllable->deleteSyllable($condition);
    }

    public function testDeleteAllSyllables():void
    {
        $query = (new SqlQueryBuilder())
            ->delete()
            ->from(Syllable::TABLE_NAME);

        $this->workWithDB
            ->expects($this->once())
            ->method('runQuery')
            ->with($query);

        $this->syllable->deleteAllSyllables();
    }
}