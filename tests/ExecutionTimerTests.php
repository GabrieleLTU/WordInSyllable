<?php
/**
 * Created by PhpStorm.
 * User: Gabrielė.Valaikaitė
 * Date: 7/23/18
 * Time: 12:38 PM
 */

namespace WordInSyllable\tests;

require __DIR__ . '/../ExecutionTimer/ExecutionTimer.php';

use PHPUnit\Framework\TestCase;
use WordInSyllable\ExecutionTimer\ExecutionTimer;

class ExecutionTimerTests extends TestCase
{
    private $timer;

    public function setUp()
    {
        $this->timer = new ExecutionTimer();
    }

    public function testGetExecutionTimeSuccess()
    {
        $expected = "Execute time (sec): 0.004";
        $this->timer->startTime();
        $this->timer->endTime();

        $this->assertSame($expected, $this->timer->getExecutionTime());
    }

    public function testGetExecutionTimeNoEndTime()
    {
        $expected = "Not started or ended time of execution calculating.";
        $this->timer->startTime();

        $this->assertSame($expected, $this->timer->getExecutionTime());
    }
}