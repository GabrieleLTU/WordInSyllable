<?php
/**
 * Created by PhpStorm.
 * User: Gabrielė.Valaikaitė
 * Date: 7/23/18
 * Time: 4:01 PM
 */

namespace WordInSyllable\tests;

require __DIR__ . '/../Controllers/SyllableController.php';
require __DIR__. '/../Models/SyllableProxy.php';

use PHPUnit\Framework\TestCase;
use WordInSyllable\Controllers\SyllableController;
use WordInSyllable\Models\SyllableProxy;

class SyllableControllerTests extends TestCase
{

    const WITH_NO_PARAM = ["syllable"];
    const WITH_SYLLABLE_ID = ["syllable", 1];
    const WITH_SYLLABLE_WORD = ["syllable", "hovercraft"];
    private $syllableControllerWithNoParam;
    private $syllableControllerWithSyllableId;
    private $syllableControllerWithSyllableWord;
    private $syllableProxy;

    public function setUp()
    {
        $this->syllableControllerWithNoParam =
            new SyllableController(self::WITH_NO_PARAM);

        $this->syllableControllerWithSyllableId =
            new SyllableController(self::WITH_SYLLABLE_ID);

        $this->syllableControllerWithSyllableWord =
            new SyllableController(self::WITH_SYLLABLE_WORD);

        $this->syllableProxy = $this->createMock(SyllableProxy::class);
    }

    public function testGet()
    {
        $expectedWithIdentif = [["s_id" => '1', "syllable" => ".ach4"]];
        $expectedNoIdentifier = [
            ["s_id" => '1', "syllable" => ".ach4"],
            ["s_id" => '2', "syllable" => ".ad4der"]
        ];

        $this->syllableProxy
            ->method('getSyllableData')
            ->with(self::WITH_SYLLABLE_ID, self::WITH_SYLLABLE_WORD)
            ->willReturn($expectedWithIdentif);

        $this->syllableProxy
            ->method('getAllSyllablesData')
            ->with(self::WITH_NO_PARAM)
            ->willReturn($expectedNoIdentifier);

        $this->assertSame(
            $expectedWithIdentif,
            $this->syllableControllerWithSyllableId->get()
        );

        $this->assertSame(
            $expectedWithIdentif,
            $this->syllableControllerWithSyllableWord->get()
        );

        $this->assertSame(
            $expectedNoIdentifier,
            $this->syllableControllerWithNoParam->get()
        );



            ///----
        ///  $mock = $this->getMock('AnInterface');
        //    $mock->expects($this->any())
        //         ->method('doSomething')
        //         ->willReturnOnConsecutiveCalls('a', 'b', 'c');
        //    $this->assertEquals('a', $mock->doSomething());
    }

}