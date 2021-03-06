<?php

namespace WordInSyllable\Execution;

use WordInSyllable\Database\SqlQueryBuilder;
use WordInSyllable\Database\WorkWithDB;
use WordInSyllable\IntoSyllable\WordInSyllable;
use WordInSyllable\IntoSyllable\WordInSyllableRegExp;
use WordInSyllable\IO_Classes\IOinterface;
use WordInSyllable\IO_Classes\WorkWithFile;
use WordInSyllable\IO_Classes\WorkWithConsole;
use WordInSyllable\Logger\FileLogger;

class Execution
{
    const CHOICE_CONSOLE = 'c';
    const CHOICE_FILE = 'f';

    public function execute()
    {
        echo "
            w - word(s) into syllables;
            s - add syllables to database;
            Your choice: ";
        $input = fopen("php://stdin", "r");
        $choice = trim(fgets($input));

        switch ($choice) {
            case 'w':
                $this->executeWordInSyllable();
                break;
            case 's':
                $this->writeNewSyllablesToDB();
                break;
            default:
                $error = "Your choice is not correct. \n";
                throw new \Exception($error);
                break;
        }
    }

    private function executeWordInSyllable()
    {
        try {
            //$loggerObject = new FileLogger('Data\logger_execute.txt');
            $wordsList = $this->getWords();
            $returnSyllables = $this->getSyllables();
            if (is_array($returnSyllables[0])) {
                $syllablesList = $returnSyllables[0];
                $syllabledWordsList = $this->wordsInSyllableAlgorithm2(
                    $wordsList,
                    $syllablesList,
                    "Data\logger_execute.txt",
                    $returnSyllables[1]
                );
                $this->outputContent($syllabledWordsList, $returnSyllables[1]);
            } else {
                $syllablesList = $returnSyllables;
                $syllabledWordsList = $this->wordsInSyllableAlgorithm2(
                    $wordsList,
                    $syllablesList,
                    "Data\logger_execute.txt",
                    null
                );
                $this->outputContent($syllabledWordsList);
            }

        } catch (\Exception $e) {
            $error = "Execute WordInSyl fail: " . $e->getMessage() . "\n";
            throw new \Exception($error);
        }
    }

    private function writeNewSyllablesToDB()
    {
        try {
            $syllablesArray = $this->getSyllables();
            $db = new WorkWithDB();
            $db->beginTransaction();
            $db->delete("syllable");
            $db->delete("word");
            $db->delete("syllablebyword");
            $dot = "";
            foreach ($syllablesArray as $syllable) {
                try {
                    $syllable = preg_replace('/[[:space:]]+/', '', $syllable);
                    if (strlen($syllable) > 1) {
                        $db->insert("syllable", ["syllable"], [$dot . $syllable]);
                        $dot = "";
                    } else {
                        $dot = $syllable;
                    }
                } catch (\Exception $e) {
                    echo $e->getMessage();
                }
            }
            $db->endTransaction();
            echo "Syllables insert end.";
        } catch (\Exception $e) {
            $error = "write new syllables fail: " . $e->getMessage() . "\n";
            throw new \Exception($error);
        }
    }

    private function wordsInSyllableAlgorithm2(
        array $wordsList,
        array $syllables,
        $loggerFile = null,
        WorkWithDB $dbObj = null
    ): array
    {
        $syllabledWordsList = [];

        foreach ($wordsList as $words) { //wordsList - array of file/console line
            $splitWord = preg_split("/\b/", $words);//array of words of one line
            $oneElement = "";
            foreach ($splitWord as $word) {//words - one line
                $temp = preg_replace('/[^[:alpha:]]/i', '', $word);
                if (strlen($temp) > 0) {
                    $oneWord = new WordInSyllableRegExp(
                        $word,
                        $loggerFile,
                        $dbObj
                    );
                    $oneElement = $oneElement . $oneWord->wordIntoSyllable($syllables);//checkWordWithAllSyllables($syllables);
                } else {
                    $oneElement = $oneElement . $word;
                }
            }
            $syllabledWordsList[] = $oneElement;
        }
        return $syllabledWordsList;
    }

    private function wordsInSyllableAlgorithm(
        array $words,
        array $syllables,
        $loggerFile = null
    ): array
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
        echo "c - input word(s) in console;\nf - input word(s) from file;\nYour choice: ";
        $input = fopen("php://stdin", "r");
        $choice = trim(fgets($input));

        switch ($choice) {
            case self::CHOICE_CONSOLE:
                $wordsList = $this->getDataFromConsole();
                break;
            case self::CHOICE_FILE:
                $wordsList = $this->getDataFromFile();
                break;
            default:
                throw new \Exception("Your choice is not correct.");
                break;
        }
        if (is_null($wordsList)) {
            throw new \Exception('There is no words...');
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
        $input = fopen("php://stdin", "r");
        $choice = trim(fgets($input));

        switch ($choice) {
            case self::CHOICE_CONSOLE:
                $syllablesList = $this->getDataFromConsole();
                break;
            case self::CHOICE_FILE:
                $syllablesList = $this->getDataFromFile();
                break;
            case 'd':
                try {
                    $dbObj = new WorkWithDB();
                    $syllablesList = $this->getSyllablesFromDatabase($dbObj);
                    return [$syllablesList, $dbObj];
                } catch (\Exception $e) {
                    $error = "Get syllables from " . $e->getMessage() . "\n";
                    throw new \Exception($error);
                }
                break;
            default:
                $error = "Your choice is not correct. \n";
                throw new \Exception($error);
                break;
        }
        return $syllablesList;
    }

    /**
     * @return array
     * Originally data is taken from: https://gist.githubusercontent.com/cosmologicon/1e7291714094d71a0e25678316141586/raw/006f7e9093dc7ad72b12ff9f1da649822e56d39d/tex-hyphenation-patterns.txt
     */
    private function getDataFromFile(): array
    {
        $file = new WorkWithFile();
        echo "File destination:\n";
        $input = fopen("php://stdin", "r");
        $choice = trim(fgets($input));
        $file->setInputFile($choice);
        $file->inputContent();

        return $file->getContent();
    }

    private function getDataFromConsole(): array
    {
        $fromConsole = new WorkWithConsole();
        $fromConsole->inputContent();
        //$word = ($fromConsole->getContent())[0];
        return $fromConsole->getContent();
    }

    private function getSyllablesFromDatabase(WorkWithDB $dbObj): array
    {
        try {
            $query = (new SqlQueryBuilder())
                ->select(["syllable"])
                ->from("syllable");

            return array_column($dbObj->runQuery($query), "syllable");
        } catch (\Exception $e) {
            $error = "Database fail: " . $e->getMessage() . "\n";
            throw new \Exception($error);
        }
    }

    /**
     * @param outputData-array/string
     */
    private function outputContent($outputData, WorkWithDB $dbObj = null): void
    {
        echo "Output data to:\nc - console;\nf -  file;\n";
        $input = fopen("php://stdin", "r");
        $choice = trim(fgets($input));

        switch ($choice) {
            case self::CHOICE_CONSOLE:
                $output = new WorkWithConsole();
                break;
            case self::CHOICE_FILE:
                $output = new WorkWithFile();
                echo "Write file destination:\n";
                $choice = trim(fgets(STDIN));
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
            if (!is_null($dbObj)) {
                $syllableToOutput = $dbObj->selectInnerJoin(
                    ["syllable", "syllablebyword", "word"],
                    ["syllable"],
                    ["syllable.s_id=syllablebyword.s_id", "word.w_id=syllablebyword.w_id"],
                    ["syllableword='$outputData[0]'"]
                );
                $syllableToOutput2[] = "Syllables: \n";
                for ($i = 0; $i < count($syllableToOutput); $i++) {
                    $syllableToOutput2[] = $syllableToOutput[$i]["syllable"];
                }

                $syllableToOutput2 = (count($syllableToOutput2) == 1) ? " " : $syllableToOutput2;
                $output->setContent($syllableToOutput2);
                $output->outputContent();
            }

        }
    }
}
