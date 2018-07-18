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
                return$this->word-> getWordData("word='{$this->urlData[2]}'");
            }

        } else{
            return $this->word-> getAllWordsData();
        }
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