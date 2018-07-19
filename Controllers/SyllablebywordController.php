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
    private $urlData = [];
    private $syllableByWord;

    public function __construct(array $urlData)
    {
        $this->urlData = $urlData;
        $this->syllableByWord = new syllableByWord();
    }


    public function get(): array
    {
        //if ((array_key_exists(2, $this->urlData)) && (array_key_exists(3, $this->urlData)))
        if (!empty($this->urlData[2]) && !empty($this->urlData[3])) {
            return $this->setGetAllSyllableOfWord(
                $this->urlData[2],
                $this->urlData[3]
            );
        } elseif (empty($this->urlData[2]) && !empty($this->urlData[3])) {
            if (!is_numeric($this->urlData[3])) {
                return $this->syllableByWord-> getAllSyllablesOfWord(
                    ["syllable='{$this->urlData[3]}'"]
                );
            } else {
                return $this->syllableByWord->getSyllablebywordData(
                    "s_id={$this->urlData[3]}"
                );
            }
        } elseif (!empty($this->urlData[2]) && empty($this->urlData[3])) {
            if (!is_numeric($this->urlData[2])) {
                return $this->syllableByWord-> getAllSyllablesOfWord(
                    ["word='{$this->urlData[2]}'"]
                );
            } else {
                return $this->syllableByWord->getSyllablebywordData(
                    "w_id={$this->urlData[2]}"
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

        if (!empty($this->urlData[2]) && !empty($this->urlData[3])) {
            $this->syllableByWord->updateSyllablebyword(
                $phpInput,
                ["w_id={$this->urlData[2]}","s_id={$this->urlData[3]}"]
            );
        } elseif (empty($this->urlData[2]) && !empty($this->urlData[3])) {
            $this->syllableByWord->updateSyllablebyword(
                $phpInput,
                "s_id={$this->urlData[3]}"
            );
        } elseif (!empty($this->urlData[2]) && empty($this->urlData[3])) {
            $this->syllableByWord->updateSyllablebyword(
                $phpInput,
                "w_id={$this->urlData[2]}"
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
        if (!empty($this->urlData[2]) && !empty($this->urlData[3])) {
            $this->syllableByWord->deleteSyllablebyword(
                ["w_id={$this->urlData[2]}","s_id={$this->urlData[3]}"]
            );
        } elseif (empty($this->urlData[2]) && !empty($this->urlData[3])) {
            $this->syllableByWord->deleteSyllablebyword(
                "s_id={$this->urlData[3]}"
            );
        } elseif (!empty($this->urlData[2]) && empty($this->urlData[3])) {
            $this->syllableByWord->deleteSyllablebyword(
                "w_id={$this->urlData[2]}"
            );
        } else {
            $this->syllableByWord->deleteAllSyllablebyword();
        }
    }
}
