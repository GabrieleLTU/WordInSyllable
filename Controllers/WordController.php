<?php
namespace WordInSyllable\Controllers;

use WordInSyllable\Models\Word;


class WordController implements ControllerInterface
{
    const WORD_IDENTIFIER = 2;
    private $urlData = [];
    private $word = null;

    public function __construct(array $urlData)
    {
        $this->urlData = $urlData;
        $this->word = new Word();
    }


    public function get(): array
    {
        if (array_key_exists(self::WORD_IDENTIFIER, $this->urlData) && !empty($this->urlData[self::WORD_IDENTIFIER])) {
            if (is_numeric($this->urlData[self::WORD_IDENTIFIER])) {
                return $this->word-> getWordData("w_id={$this->urlData[self::WORD_IDENTIFIER]}");
            } else {
                return $this->word-> getWordData("word='{$this->urlData[self::WORD_IDENTIFIER]}'");
            }
        } else {
            return $this->word-> getAllWordsData();
        }
    }

    public function put():void
    {
        $phpInput = json_decode(file_get_contents("php://input"), true);

        if (is_numeric($this->urlData[self::WORD_IDENTIFIER])) {
            $this->word-> updateWord($phpInput, "w_id={$this->urlData[self::WORD_IDENTIFIER]}");
        } else {
            $this->word-> updateWord($phpInput, "word='{$this->urlData[self::WORD_IDENTIFIER]}'");
        }
    }

    public function post():void
    {
        $phpInput = json_decode(file_get_contents("php://input"), true);
        $this->word-> insertWord($phpInput);
    }

    public function delete():void
    {
        if (array_key_exists(self::WORD_IDENTIFIER, $this->urlData) && !empty($this->urlData[self::WORD_IDENTIFIER])) {
            if (is_numeric($this->urlData[self::WORD_IDENTIFIER])) {
                $this->word-> deleteWord("w_id={$this->urlData[self::WORD_IDENTIFIER]}");
            } else {
                $this->word-> deleteWord("word='{$this->urlData[self::WORD_IDENTIFIER]}'");
            }
        } else {
             $this->word-> deleteAllWords();
        }
    }

}