<?php
    namespace WordInSyllable\Execution;

    use WordInSyllable\Database\WorkWithDB;
    use WordInSyllable\IntoSyllable\WordInSyllable;
    use WordInSyllable\IntoSyllable\WordInSyllableRegExp;
    use WordInSyllable\IO_Classes\IOinterface;
    use WordInSyllable\IO_Classes\WorkWithFile;
    use WordInSyllable\IO_Classes\WorkWithConsole;
    use WordInSyllable\Logger\FileLogger;

    class Execution
    {
        public function execute()
        {
            try {
                //$db = new WorkWithDB;
                //die();
                $loggerObject = new FileLogger('Data\logger_execute.txt');
                $wordsList = $this->getWords();
                //var_dump($wordsList);
                $syllablesList = $this->getSyllables();
                $syllabledWordsList = $this->wordsInSyllableAlgorithm2(
                    $wordsList,
                    $syllablesList,
                    "Data\logger_execute.txt"
                );
                $this->outputContent($syllabledWordsList);
            } catch (\Exception $e) {
                //sent $e to logger;
                echo "\nMessage:  " . $e->getMessage() . "\n";
            }
        }

        private function wordsInSyllableAlgorithm2(
            array $wordsList,
            array $syllables,
            $loggerFile = NULL
            ): array
        {
            $syllabledWordsList = [];
            $oneELement = "";
            //echo "param: "; var_dump($wordsList);

            foreach ($wordsList as $words) { //wordsList - array of file/console line
                $splitWord = preg_split("/\b/", $words);//array of words of one line
                //echo "splitWord: "; var_dump($splitWord);
                $oneELement = "";
                foreach ($splitWord as $word) {//words - one line
                    $temp = preg_replace('/[^[:alpha:]]/i', '', $word);
                    //var_dump($word); echo " -> ";
                    //var_dump($temp); echo " -> ".strlen($temp);
                    if (strlen($temp) > 0) {
                        //echo "to syllable: " . $word . "\n";
                        $oneWord = new WordInSyllableRegExp($word, $loggerFile);
                        $oneELement = $oneELement . $oneWord->checkWordWithAllSyllables($syllables);
                    } else {
                        $oneELement = $oneELement.$word;
                    }
                }
                $syllabledWordsList[] = $oneELement;
          }
          return $syllabledWordsList;
      }

        private function wordsInSyllableAlgorithm(
            array $words,
            array $syllables,
            $loggerFile = NULL): array
        {
            $syllabledWordsList = [];

            foreach ($words as $word) {
                $oneWord = new WordInSyllableRegExp($word, $loggerFile);
                $syllabledWordsList[] = $oneWord->checkWordWithAllSyllables(
                    $syllables
                );
            }
            return $syllabledWordsList;
        }

        private function getWords(): array
        {
            $wordsList = [];
            echo "
            c - input word(s) in console;
            f - input word(s) from file;
            Your choice: ";
            $input = fopen ("php://stdin","r");
            $choice = trim(fgets($input));

            switch ($choice) {
                case 'c':
                    $wordsList = $this->getDataFromConsole();
          	        break;
                case 'f':
                    $wordsList = $this->getDataFromFile();
            		break;
          		default:
                    $error = "Your choice is not correct. \n";
                    throw new \Exception($error);
          			break;
          		}
            if (is_null($wordsList)) {
                $error = 'There is no words...';
                throw new \Exception($error);
            }
            return $wordsList;
        }

        private function getSyllables(): array
        {
          $syllablesList = [];
          echo "
          c - input syllable(s) in console;
          f - input syllable(s) from file;
          d - database;
          Your choice: ";
          $input = fopen ("php://stdin","r");
          $choice = trim(fgets($input));

          switch ($choice) {
              case 'c':
                    $syllablesList = $this->getDataFromConsole();
          			break;
              case 'f':
                    $syllablesList = $this->getDataFromFile();
            		break;
              case 'd':
                    $syllablesList = $this->getDataFromDatabase();
            		break;
          		default:
                    $error = "Your choice is not correct. \n";
                    throw new \Exception($error);
          			    break;
          		}
          return $syllablesList;
        }

        private function getDataFromFile(): array
        {
            $file = new WorkWithFile;
            echo "File destination:\n";
            $input = fopen ("php://stdin","r");
            $choice = trim(fgets($input));
            $file->setInputFile($choice);
            //$file->setFile("https://gist.githubusercontent.com/cosmologicon/1e7291714094d71a0e25678316141586/raw/006f7e9093dc7ad72b12ff9f1da649822e56d39d/tex-hyphenation-patterns.txt");
            $file->inputContent();//Data\syllable_example.txt");//
            //$file->setFile('Data\filename.txt');
            return $file->getContent();
        }

        private function getDataFromConsole(): array
        {
            $fromConsole = new WorkWithConsole;
            $fromConsole->inputContent();
            $word = ($fromConsole->getContent())[0];
            return $fromConsole->getContent();
        }

        private function getDataFromDatabase(): array
        {
            try {
                $fromDB = new WorkWithDB;
                //var_dump($fromDB->selectSyllables());
                return $fromDB->selectSyllables();
            } catch (\Exception $e) {
                $error = "Database fail: " . $e->getMessage() . "\n";
                throw new \Exception($error);
            }

        }

        /**
         * @param array/string
         */
        private function outputContent ($outputData)
        {
            echo "Output data to:\nc - console;\nf -  file;\n";
            $input = fopen ("php://stdin","r");
            $choice = trim(fgets($input));

            switch ($choice) {
                case 'c':
                    $output = new WorkWithConsole();
                    break;
                case 'f':
                    $output = new WorkWithFile();
                    echo "Write file destination:\n";
                    $input = fopen ("php://stdin","r");
                    $choice = trim(fgets($input));
                    $output->setOutputFile($choice);
                    //$output->setFile("Data\SyllableWords.txt");
                    break;
                case 'e':
                    break;
                default:
                    $error = "Your choice is not correct. \n";
                    throw new \Exception($error);
                    break;
            		}

            if (isset($output)) {
                $output->setContent($outputData);
                $output->outputContent();
            }
        }
    }
?>
