<?php
/**
 * Created by PhpStorm.
 * User: Gabrielė.Valaikaitė
 * Date: 7/23/18
 * Time: 11:00 AM
 */

namespace WordInSyllable\tests;
require __DIR__ . '/../IntoSyllable/WordInSyllableRegExp.php';

//use SebastianBergmann\CodeCoverage\TestCase;
use PHPUnit\Framework\TestCase;


class WordInSyllableRegExpTests extends TestCase
{

    private $testingClassObject;  //WordInSyllableRegExp
    private $wordClass;

    public function setup()
    {
        $word = "hovercraft";
        $loggerFilePath = "Data\logger_execute.txt";
        $this->testingClassObject = new WordInSyllableRegExp($word, $loggerFilePath);
    }

    public function testWordIntoSyllable()
    {
        //arrange:
        $expected = true;//"ho-ver-craft";
        $syllableArray = [".hov5", "a2f", "2ft.", "r1c"];

        //action:
        $this->testingClass->method('checkWordWithAllSyllables')->willReturn($expected);
        $output = $this->testingClassObject->checkWordWithAllSyllables($syllableArray);


        //assert:
        $this->assertSame($expected, $output);
    }


}