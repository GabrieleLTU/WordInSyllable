<?php
namespace WordInSyllable\IntoSyllable;

use WordInSyllable\Database\WorkWithDB;
use WordInSyllable\Models\Word;
use WordInSyllable\IO_Classes\IOinterface;
use SplFileObject;

final class WordInSyllableRegExp extends Word
{
    private $position;
    private $loggerFile;
    private $dbObj;
    private $wordId;

    public function __construct(
        string $word,
        string $loggerFile,
        WorkWithDB $dbObj = null
    ) {
        try {
            parent::__construct($word);
            $this->loggerFile = new SplFileObject($loggerFile);
            //parameter: int $start_index , int $num , mixed $value
            $this->position = array_fill(0, strlen($this->word), 0);
            $this->dbObj = $dbObj;
        } catch (\Exception $e) {
            $error = "Creating WordInSyllableRegExp object fail: " . $e->getMessage() . "\n";
            throw new \Exception($error);
        }
    }
    public function wordIntoSyllable(array $syllables): string
    {
        if (!is_null($this->dbObj)) {
            $wordData = $this->dbObj->insertIfNotExist(
                "word",
                ["word"],
                [$this->word],
                ["syllableWord", "w_id"]
            );
            $this->wordId = $wordData[0]["w_id"];
            if (is_null($wordData[0]["syllableWord"])) {
                $return = $this->checkWordWithAllSyllables($syllables);
                $this->dbObj->update("word", ["syllableWord"], [$return], [" word= '$this->word'"]);
                return $return;
            } else {
                return $wordData[0]["syllableWord"];
            }
        } else {
            return $this->checkWordWithAllSyllables($syllables);
        }
    }

    public function checkWordWithAllSyllables(array $syllables): string
    {
        //parameter: int $start_index , int $num , mixed $value
        $this->position = array_fill(0, strlen($this->word), 0);
        foreach ($syllables as $syllable) {
            //echo $this->word . $syllable;
            $this->checkWord($syllable);
        }

        return  (is_null($this->syllableWord)) ? $this->word : $this->syllableWord ;
    }

    private function checkWord(string $syllable)
    {
        $syllableNoNumber = preg_replace('/[\.\d\n\r]+/', '', $syllable);
        preg_match_all(
            '/(' . $syllableNoNumber . ')/i',
            $this->word,
            $matches,
            PREG_OFFSET_CAPTURE
        );

        if ($syllable[0] === '.') {//at the start of the word
            preg_match(
                '/^(' . $syllableNoNumber . ')/i',
                $this->word,
                $matches,
                PREG_OFFSET_CAPTURE
            );
            if (!empty($matches[0])) {
                $this->changePosition($syllable, 0);
            }
        } elseif ($syllable[strlen($syllable) - 1] === '.') { //at the end of the word
            preg_match(
                '/(' . $syllableNoNumber . ')$/i',
                $this->word,
                $matches,
                PREG_OFFSET_CAPTURE
            );
            if (!empty($matches[0])) {
                $this->changePosition($syllable, $matches[0][1]);
            }
        } else {//somewere in the word
            for ($i = 0; $i < count($matches[0]); $i++) {
                $this->changePosition($syllable, $matches[0][$i][1]);
            }
        }
    }

    private function checkAnywereInWord(string $syllable, int $searchStart)
    {
        $syllableNoNumber = preg_replace('/[\.[:space:]]+/i', '', $syllable);
        preg_match_all(
            '/(' . $syllableNoNumber . ')/i',
            $this->word,
            $matches,
            PREG_OFFSET_CAPTURE
        );
        if (!empty($matches[0])) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $this->changePosition($syllable, $matches[0][$i][1]);
            }
        }
    }

    private function changePosition(string $syllable, int $sylStart):void
    {
        $position = $this->position;
        $syllableNoDot = preg_replace('/\./', '', $syllable);
        if (($sylStart - 1) < 0) {
            $letterNumber = 0;
        } else {
            $letterNumber = $sylStart - 1;
        }
        for ($i = (($sylStart - 1) < 0) ? 1 : 0; $i < strlen($syllableNoDot); $i++) {
            if (is_numeric($syllableNoDot[$i])) {
                if ($position[$letterNumber] < $syllableNoDot[$i]) {
                    $position[$letterNumber] = $syllableNoDot[$i];
                }
            } else {
                $letterNumber++;
            }
        }
        $this->position = $position;
        $this->saveWordSyllables();
        if (!is_null($this->dbObj)) {
            //$this->dbObj->beginTransaction();
            $syllableId = $this->dbObj->select(
                "syllable",
                ["s_id"],
                ["syllable='$syllable'"]
            );
            $this->dbObj->insertIfNotExist(
                "syllablebyword",
                ["w_id","s_id"],
                [$this->wordId, $syllableId[0]["s_id"]]);
                //$this->dbObj->endTransaction();
        }
        //insert syllableByWord
    }

    private function saveWordSyllables(): void
    {
        $syllableWord = "";
        $this->position[strlen($this->word) - 1] = 0;
        //var_dump($this->position);
        for ($i = 0; $i < strlen($this->word); $i++) {
            $syllableWord = $syllableWord . $this->word[$i];
            if ($this->position[$i] % 2 > 0) {
                $syllableWord = $syllableWord . "-";
            }
        }
        $this->syllableWord = $syllableWord;
    }

    public function getSyllableWord(): string
    {
        return $this->syllableWord;
    }
}
