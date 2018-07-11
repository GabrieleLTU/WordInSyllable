<?php
    namespace WordInSyllable\Exceptions;

    class CriticalException extends Exception
    {
        protected $exceptionLevel;

        public function __construct(
            string $message = "",
            LogLevel $level,
            $code = 0,
            Exception $previous = NULL)
        {
            $this->exceptionLevel = $level;
            parent::__construct($message, $code, $previous);
        }

        public function getLevel(): LogLevel
        {
            return $this->exceptionLevel;
        }
    }
?>
