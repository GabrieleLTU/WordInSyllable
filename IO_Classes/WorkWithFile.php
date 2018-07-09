<?php

  class WorkWithFile implements IOinterface
  {
    private $fileContent;
    public $file;

    public function setFile($fileName)
    {
      //need to add: if file exist check
      $this->file = new SplFileObject($fileName);
    }

    public function setContent($content)
    {
      if (is_array($content)) {
        $this->fileContent = $content;
      } else {
        $this->fileContent[] = $content;
      }
    }

// read the file and save its content in the array (one line - one element)
    public function inputContent()
    {
      $fileContent;
      while (!$this->file->eof()) {
          $fileContent[] = $this->file->current();
          $this->file->next();
      }
      //echo "fileContent: "; print_r($fileContent);
      $this->fileContent = $fileContent;
    }

//return array of the file content ((one line in file - one element))
    public function getContent ()
    {
      return $this->fileContent;
    }

// writeFileContentInFile
    public function outputContent ()
    {
      file_put_contents($this->file, $this->fileContent);//print_r($this->fileContent));
    }
  }
?>
