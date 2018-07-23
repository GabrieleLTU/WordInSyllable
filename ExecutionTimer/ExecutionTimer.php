<?php
namespace WordInSyllable\ExecutionTimer;

class ExecutionTimer
{
    private $dtStart;
    private $dtEnd;

    //private $dtStart2;
    //private $dtEnd2;

    public function __construct()
    {
        //date_default_timezone_set(date_default_timezone_get());
    }

    public function startTime(): void
    {
        $this->dtStart = microtime(true);
        $this->dtEnd = null;
        //$this->dtStart2 = new DateTime();
        //$this->dtEnd2 = null;
    }

    public function endTime():void
    {
        $this->dtEnd = microtime(true);
       //$this->dtEnd2 = new DateTime();
    }

    public function getExecutionTime(): string
    {
        if (!is_null($this->dtStart) && !is_null($this->dtEnd)) {
            $execTimeSec = $this->dtEnd - $this->dtStart;
            return "Execute time (sec): " . $execTimeSec;
        } else {
            return "Not started or ended time of execution calculating.";
        }

      /*if (!is_null($this->dtStart2) && !is_null($this->dtEnd2))
      {
        echo "\n".$this->dtStart2->diff($this->dtEnd2)
                ->format('Execute time2 (sec): %m:%i.%f');
      }*/
    }
}
