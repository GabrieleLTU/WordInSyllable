<?php
namespace WordInSyllable\IO_Classes;

class WorkWithConsole implements IOinterface
{
    private $consoleContent;

    /**
     * @param array/string
     */
    public function setContent($content)
    {
        if (is_array($content)) {
            $this->consoleContent = $content;
        } else {
            $this->consoleContent = $content;
        }
    }

    /**
    * read the input and save it in the array (as one element)
    *@return null or nothing
    */
    public function inputContent()
    {
        echo "Input: ";
        $input = fopen("php://stdin", "r");
        $line = trim(fgets($input));
        //var_dump(strlen(trim(fgets($input))));
        if (strlen($line) === 0) {
            return null;
        } else {
            $this->consoleContent[] = $line;
        }
    }

    public function getContent(): array
    {
        return $this->consoleContent;
    }

    public function outputContent()
    {
        if (is_array($this->consoleContent)) {
            foreach ($this->consoleContent as $oneElement) {
                echo $oneElement . "\n";
            }
        } else {
            echo $this->consoleContent . "\n";
        }
    }

    /**
     * @param array/string
     */
    public static function outputParameterToConsole($content)
    {
        if (is_array($content)) {
            echo $content;
        } else {
            foreach ($content as $oneElement) {
                echo $oneElement . "\n";
            }
        }
    }
}
