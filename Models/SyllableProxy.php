<?php
/**
 * Created by PhpStorm.
 * User: Gabrielė.Valaikaitė
 * Date: 7/19/18
 * Time: 9:40 AM
 */

namespace WordInSyllable\Models;

use WordInSyllable\Database\WorkWithDB;
use WordInSyllable\Logger\FileLogger;

class SyllableProxy
{
    private $syllableObject;
    private $syllable;
    private $loggerFile;

    public function __construct()
    {
        //$this->loggerFile = new FileLogger("Data\logger_execute.txt");
    }

    public function getAndCreateSyllable(string $syllable = null): void
    {
        if (is_null($this->syllableObject)) {
            $this->syllableObject = new Syllable(new WorkWithDB(), $syllable);
        }
    }

    public function getSyllableData($condition): array
    {
        if (is_null($this->syllableObject)) {
            $this->getAndCreateSyllable();
        }
        return $this->syllableObject->getSyllableData($condition);
    }

    public function getAllSyllablesData(): array
    {
        if (is_null($this->syllableObject)) {
            $this->getAndCreateSyllable();
        }
        return $this->syllableObject->getAllSyllablesData();
    }

    public function insertSyllable(array $valuesByKey): void
    {
        $this->getAndCreateSyllable();

        if (!empty($valuesByKey)) {
            $this->syllableObject->insertSyllable($valuesByKey);
        } else {
            // return [];
        }
    }

    public function updateSyllable(array $valuesByKey, $condition): void
    {
        if (is_null($this->syllableObject)) {
            $this->getAndCreateSyllable();
        }

        if (!empty($valuesByKey)) {
            $this->syllableObject->updateSyllable($valuesByKey, $condition);
        } else {
            // return "there is no syllable to update.";
        }
    }

    public function deleteSyllable($condition): void
    {
        if (is_null($this->syllableObject)) {
            $this->getAndCreateSyllable();
        }

        if (!empty($condition)) {
            $this->syllableObject->deleteSyllable($condition);
        } else {
            // return "there is no info which syllable to delete.";
        }
    }

    public function deleteAllSyllables(): void
    {
        try {
            if (is_null($this->syllableObject)) {
                $this->getAndCreateSyllable();
            }
            $this->syllableObject = new Syllable();
            $this->syllableObject->deleteAllSyllables();
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