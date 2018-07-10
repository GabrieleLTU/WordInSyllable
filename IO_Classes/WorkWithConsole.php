<?php
namespace WordInSyllable\IO_Classes;

  class WorkWithConsole implements IOinterface
  {
    private $consoleContent;

    public function setContent($content)
    {
      if (is_array($content)) {
        $this->consoleContent = $content;
      } else {
        $this->consoleContent = $content;
      }
    }

// read the input and save it in the array (as one element)
    public function inputContent()
    {
      echo "Input: ";
      $input = fopen ("php://stdin","r");
      $line = trim(fgets($input));
      //var_dump(strlen(trim(fgets($input))));
      if (strlen($line) === 0) {
        return null;
      } else {
        $this->consoleContent[] = $line;
      }
    }

//return array of the content
    public function getContent()
    {
      return $this->consoleContent;
    }

// output all content in console
    public function outputContent()
    {
      foreach ($this->consoleContent as $oneElement) {
        echo $oneElement."\n";
      }
    }

    // output all content in console
    public static function outputParameterToConsole($content)
    {
      if (is_array($content)) {
        echo $content;
      } else {
        foreach ($content as $oneElement) {
          echo $oneElement."\n";
        }
      }

    }
  }

?>
