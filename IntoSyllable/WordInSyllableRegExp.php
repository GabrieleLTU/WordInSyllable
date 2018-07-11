<?php
    namespace WordInSyllable\IntoSyllable;

    use WordInSyllable\IntoSyllable\Word;
    use WordInSyllable\IO_Classes\IOinterface;
    use SplFileObject;

    final class WordInSyllableRegExp extends Word
    {
        private $position;
        private $syllableWord;
        private $loggerFile;

        function __construct(string $word, string $loggerFile)
        {
          $this->word = $word;
          $this->loggerFile = new SplFileObject($loggerFile);
          //parameter: int $start_index , int $num , mixed $value
          $this->position = array_fill (0, strlen($this->word), 0);
        }

        public function checkWordWithAllSyllables(array $syllables): string
        {
            //parameter: int $start_index , int $num , mixed $value
            $this->position = array_fill (0, strlen($this->word), 0);
            foreach ($syllables as $syllable) {
                $this->checkWord($syllable);
            }
            return $this->syllableWord;
        }

        private function checkWord(string $syllable)
        {
            $syllableNoNumber = preg_replace('/[\.\d\n\r]+/', '', $syllable);
            preg_match_all(
                '/(' . $syllableNoNumber . ')/i',
                $this->word,
                $matches,
                PREG_OFFSET_CAPTURE);

            if ($syllable[0] === '.') {//at the start of the word
                preg_match(
                    '/^(' . $syllableNoNumber . ')/i',
                    $this->word, $matches,
                    PREG_OFFSET_CAPTURE);
                if (!empty($matches[0])) {
                  $this->changePosition($syllable, 0);
                }
            } else if ($syllable[strlen($syllable)-1] === '.') { //at the end of the word
                preg_match(
                  '/(' . $syllableNoNumber . ')$/i',
                  $this->word, $matches,
                  PREG_OFFSET_CAPTURE);
                if (!empty($matches[0])) {
                    $this->changePosition($syllable, $matches[0][1]);
                }
            } else {//somewere in the word
                for ($i=0; $i < count($matches[0]); $i++) {
                    $this->changePosition($syllable, $matches[0][$i][1]);
                }
            }
        }

        private function checkAnywereInWord(string $syllable, int $searchStart)
        {
            $syllableNoNumber = preg_replace('/[\.[:space:]]+/i', '', $syllable);
            preg_match_all(
                '/(' . $syllableNoNumber . ')/i',
                $this->word,
                $matches,
                PREG_OFFSET_CAPTURE
            );
            if (!empty($matches[0])) {
                for ($i=0; $i < count($matches[0]); $i++) {
                    $this->changePosition($syllable, $matches[0][$i][1]);
                }
            }
        }

        private function changePosition(string $syllable, int $sylStart)
        {
            $position = $this->position;
            $syllable = preg_replace('/\./', '', $syllable);
            if (($sylStart-1) < 0) {
                $letterNumber = 0;
            }
            else {
                $letterNumber = $sylStart - 1;
            }
            for ($i = (($sylStart-1) < 0) ? 1 : 0; $i < strlen($syllable); $i++) {
                if (is_numeric($syllable[$i])) {
                    if ($position[$letterNumber] < $syllable[$i]) {
                    $position[$letterNumber] = $syllable[$i];
                    }
                } else {
                  $letterNumber++;
                }
            }
            $this->position = $position;
            $this->saveWordSylables();
        }

        private function saveWordSylables()
        {
            $syllableWord = "";
            $this->position[strlen($this->word)-1] = 0;
            //var_dump($this->position);
            for ($i = 0; $i < strlen($this->word); $i++) {
                $syllableWord = $syllableWord . $this->word[$i];
                if ($this->position[$i]%2 > 0) {
                    $syllableWord = $syllableWord . "-";
                }
            }
            $this->syllableWord = $syllableWord;
        }

    public function getSyllableWord(): string
    {
      return $this->syllableWord;
    }
}
?>
