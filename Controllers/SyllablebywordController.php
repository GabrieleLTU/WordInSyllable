<?php
/**
 * Created by PhpStorm.
 * User: Gabrielė.Valaikaitė
 * Date: 7/18/18
 * Time: 3:59 PM
 */

namespace WordInSyllable\Controllers;


use WordInSyllable\Models\Syllablebyword;

class SyllablebywordController implements ControllerInterface
{
    const TABLE_NAME = "syllable";
    const WORD_IDENTIFIER = 2;
    const SYLLABLE_IDENTIFIER = 3;

    private $urlData = [];
    private $syllableByWord;

    public function __construct(array $urlData)
    {
        $this->urlData = $urlData;
        $this->syllableByWord = new syllableByWord();
    }


    public function get(): array
    {
        if (!empty($this->urlData[self::WORD_IDENTIFIER]) && !empty($this->urlData[self::SYLLABLE_IDENTIFIER])) {
            return $this->setGetAllSyllableOfWord(
                $this->urlData[self::WORD_IDENTIFIER],
                $this->urlData[self::SYLLABLE_IDENTIFIER]
            );
        } elseif (empty($this->urlData[self::WORD_IDENTIFIER]) && !empty($this->urlData[self::SYLLABLE_IDENTIFIER])) {
            if (!is_numeric($this->urlData[self::SYLLABLE_IDENTIFIER])) {
                return $this->syllableByWord-> getAllSyllablesOfWord(
                    ["syllable='{$this->urlData[self::SYLLABLE_IDENTIFIER]}'"]
                );
            } else {
                return $this->syllableByWord->getSyllablebywordData(
                    "s_id={$this->urlData[self::SYLLABLE_IDENTIFIER]}"
                );
            }
        } elseif (!empty($this->urlData[self::WORD_IDENTIFIER]) && empty($this->urlData[self::SYLLABLE_IDENTIFIER])) {
            if (!is_numeric($this->urlData[self::WORD_IDENTIFIER])) {
                return $this->syllableByWord-> getAllSyllablesOfWord(
                    ["word='{$this->urlData[self::WORD_IDENTIFIER]}'"]
                );
            } else {
                return $this->syllableByWord->getSyllablebywordData(
                    "w_id={$this->urlData[self::WORD_IDENTIFIER]}"
                );
            }
        } else {
            return $this->syllableByWord->getAllSyllablebywordsData();
        }
    }

    /***
     * @param $wordData int|string
     * @param $syllableData int|string
     * @return array
     */
    public function setGetAllSyllableOfWord($wordData, $syllableData): array
    {
        $where = [];
        if (!empty($wordData)) {
            if (is_numeric($wordData)) {
                $where[] = "word.w_id={$wordData}";
            } else {
                $where[] = "word='{$wordData}'";
            }
        }

        if (!empty($syllableData)) {
            if (is_numeric($syllableData)) {
                $where[] = "syllable.s_id='{$syllableData}'";
            } else {
                $where[] = "syllable='{$syllableData}'";
            }
        }

        return $this->syllableByWord->getAllSyllablesOfWord($where);
    }

    public function put():void
    {
        $phpInput = json_decode(file_get_contents("php://input"), true);

        if (!empty($this->urlData[self::WORD_IDENTIFIER]) && !empty($this->urlData[self::SYLLABLE_IDENTIFIER])) {
            $this->syllableByWord->updateSyllablebyword(
                $phpInput,
                ["w_id={$this->urlData[self::WORD_IDENTIFIER]}","s_id={$this->urlData[self::SYLLABLE_IDENTIFIER]}"]
            );
        } elseif (empty($this->urlData[self::WORD_IDENTIFIER]) && !empty($this->urlData[self::SYLLABLE_IDENTIFIER])) {
            $this->syllableByWord->updateSyllablebyword(
                $phpInput,
                "s_id={$this->urlData[self::SYLLABLE_IDENTIFIER]}"
            );
        } elseif (!empty($this->urlData[self::WORD_IDENTIFIER]) && empty($this->urlData[self::SYLLABLE_IDENTIFIER])) {
            $this->syllableByWord->updateSyllablebyword(
                $phpInput,
                "w_id={$this->urlData[self::WORD_IDENTIFIER]}"
            );
        }
    }

    public function post():void
    {
        $phpInput = json_decode(file_get_contents("php://input"), true);
        $this->syllableByWord-> insertSyllablebyword($phpInput);
    }

    public function delete():void
    {
        if (!empty($this->urlData[self::WORD_IDENTIFIER]) && !empty($this->urlData[self::SYLLABLE_IDENTIFIER])) {
            $this->syllableByWord->deleteSyllablebyword(
                ["w_id={$this->urlData[self::WORD_IDENTIFIER]}","s_id={$this->urlData[self::SYLLABLE_IDENTIFIER]}"]
            );
        } elseif (empty($this->urlData[self::WORD_IDENTIFIER]) && !empty($this->urlData[self::SYLLABLE_IDENTIFIER])) {
            $this->syllableByWord->deleteSyllablebyword(
                "s_id={$this->urlData[self::SYLLABLE_IDENTIFIER]}"
            );
        } elseif (!empty($this->urlData[self::WORD_IDENTIFIER]) && empty($this->urlData[self::SYLLABLE_IDENTIFIER])) {
            $this->syllableByWord->deleteSyllablebyword(
                "w_id={$this->urlData[self::WORD_IDENTIFIER]}"
            );
        } else {
            $this->syllableByWord->deleteAllSyllablebyword();
        }
    }
}
