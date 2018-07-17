<?php

use WordInSyllable\IntoSyllable\Word;

/**
 * Created by PhpStorm.
 * User: Gabrielė.Valaikaitė
 * Date: 7/17/18
 * Time: 5:33 PM
 */

class WordController
{
    public function get()
    {
        $string = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $urlData = explode("/", $string);

        $word = new Word();

        if(exist($urlData[2])){

            if (is_numeric($urlData[2])){
                $word-> getWordData("w_id=$urlData[2]");
            } else {
                $word-> getWordData("word=$urlData[2]");
            }

        } else{
            $word-> getAllWordsData();
        }


    }

}