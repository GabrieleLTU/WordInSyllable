<?php

  class WorkWithConsole implements IOinterface
  {
    private $consoleContent;

    public function setContent ($content)
    {
      if (is_array($content))
      {
        $this->consoleContent = $content;
      }
      else
      {
        $this->consoleContent[] = $content;
      }
    }

// read the input and save it in the array (as one element)
    public function inputContent ()
    {
      $input = fopen ("php://stdin","r");
      $this->consoleContent[] = trim(fgets($input));
    }

//return array of the content
    public function getContent ()
    {
      return $this->consoleContent;
    }

// output all content in console
    public function outputContent ()
    {
      for ($i=0; $i < sizeof($this->consoleContent); $i++)
      {
        echo $this->consoleContent[$i]."\n";
      }
    }

  }

?>
