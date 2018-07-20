<?php
/**
 * Created by PhpStorm.
 * User: Gabrielė.Valaikaitė
 * Date: 7/19/18
 * Time: 9:40 AM
 */

namespace WordInSyllable\Models;

use WordInSyllable\Logger\FileLogger;

class SyllableProxy
{
    private $syllableObject;
    private $syllable;
    private $loggerFile;

    public function __construct()
    {
        $this->loggerFile = new FileLogger("Data\logger_execute.txt");
    }

    public function createSyllable(string $syllable = null):void
    {
        $this->syllable = $syllable;
        $this->syllableObject = new Syllable($syllable);
    }

    public function getSyllableData($condition):array
    {
        if (is_null($this->syllableObject)) {
            $this->createSyllable();
            return $this->syllableObject->getSyllableData($condition);
        } else {
            return [];//"there is no condition"
        }
    }

    public function getAllSyllablesData():array
    {
        $syllableObject = new Syllable();
        return $syllableObject->getAllSyllablesData();
    }

    public function insertSyllable(array $valuesByKey):void
    {
        if (is_null($this->syllableObject)) {
            $this->createSyllable();
        }

        if (!empty($valuesByKey)) {
            $this->syllableObject->insertSyllable($valuesByKey);
        } else {
           // return [];
        }
    }

    public function updateSyllable(array $valuesByKey, $condition):void
    {
        if (is_null($this->syllableObject)) {
            $this->createSyllable();
        }

        if (!empty($valuesByKey)) {
            $this->syllableObject->updateSyllable($valuesByKey, $condition);
        } else {
            // return "there is no syllable to update.";
        }
    }

    public function deleteSyllable($condition):void
    {
        if (is_null($this->syllableObject)) {
            $this->createSyllable();
        }

        if (!empty($condition)) {
            $this->syllableObject->deleteSyllable($condition);
        } else {
            // return "there is no info which syllable to delete.";
        }
    }

    public function deleteAllSyllables():void
    {
        try {
            if (is_null($this->syllableObject)) {
                $this->createSyllable();
            }
            $$this->csyllableObject = new Syllable();
            $$this->csyllableObject->deleteAllSyllables();
            $this->loggerFile->info(
                "All Syllables was deleted from database"
            );
        } catch (\Exception $e) {
            $this->loggerFile->warning(
                "All syllable delete from database error: {$e}"
            );
        }
    }
}