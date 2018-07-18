<?php
namespace WordInSyllable\Controllers;

use WordInSyllable\Models\Word;


class WordController
{
    /**
     * @var array
     */
    private $urlData;
    private $word;

    public function __construct(array $urlData)
    {
        $this->urlData = $urlData;
        $this->word = new Word();
    }


    public function get(): array
    {
        if(array_key_exists(2, $this->urlData) && !empty($this->urlData[2])){

            if (is_numeric($this->urlData[2])){
                return $this->word-> getWordData("w_id={$this->urlData[2]}");
            } else {
                return $this->word-> getWordData("word='{$this->urlData[2]}'");
            }

        } else{
            return $this->word-> getAllWordsData();
        }
    }

    public function put()
    {
        $phpInput = json_decode(file_get_contents("php://input"), true);

        if (is_numeric($this->urlData[2])){
            return $this->word-> updateWord($phpInput, "w_id={$this->urlData[2]}");
        } else {
            return $this->word-> updateWord($phpInput, "word='{$this->urlData[2]}'");
        }
    }

    public function post()
    {
        $phpInput = json_decode(file_get_contents("php://input"), true);
        //die(var_dump($phpInput));

        return $this->word-> insertWord($phpInput);
    }

    public function delete()
    {
        if(array_key_exists(2, $this->urlData) && !empty($this->urlData[2])){

            if (is_numeric($this->urlData[2])){
                return $this->word-> deleteWord("w_id={$this->urlData[2]}");
            } else {
                return$this->word-> deleteWord("word='{$this->urlData[2]}'");
            }

        } else{
            return $this->word-> deleteAllWords();
        }
    }

}