<?php
namespace WordInSyllable\IO_Classes;
use SplFileObject;

class WorkWithFile implements IOinterface
{
    private $fileContent;
    private $fileInput;
    private $fileOutput;

    public function setInputFile(string $fileName)
    {
        $this->fileInput = new SplFileObject($fileName);
    }

    public function setOutputFile(string $fileName)
    {
        $this->fileOutput = new SplFileObject($fileName, 'a+');
    }

    /**
     * @param array/string
     */
    public function setContent($content)
    {
        if (is_array($content)) {
            $this->fileContent = $content;
        } else {
            $this->fileContent = $content;
        }
    }

    /**
    * read the file and save its content in the array (one line - one element)
    */
    public function inputContent()
    {
        $fileContent = [];
        while (!$this->fileInput->eof()) {
            $fileContent[] = preg_replace('/[[:space:]]/', '', $this->fileInput->current());
            $this->fileInput->next();
        }
        $this->fileContent = $fileContent;
    }

    //return array of the file content ((one line in file - one element))
    public function getContent(): array
    {
        return $this->fileContent;
    }

    /**
    * writeFileContentInFile
    */
    public function outputContent()
    {
        foreach ($this->fileContent as $line) {
            $this->fileOutput->fwrite($line);
        }
    }
}
