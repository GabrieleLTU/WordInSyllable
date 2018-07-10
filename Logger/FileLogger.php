<?php
namespace WordInSyllable\Logger;

/**
 * Describes a logger instance
 *
 * The message MUST be a string or object implementing __toString().
 *
 * The message MAY contain placeholders in the form: {foo} where foo
 * will be replaced by the context data in key "foo".
 *
 * The context array can contain arbitrary data, the only assumption that
 * can be made by implementors is that if an Exception instance is given
 * to produce a stack trace, it MUST be in a key named "exception".
 *
 * See https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
 * for the full interface specification.
 */
 class FileLogger
 {
 private $file;

 function __constructor($fileName)
 {
   $this->file = new SplFileObject($fileName);
 }


    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    public function emergency($message, array $context = array())
    {
      $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    public function alert($message, array $context = array())
    {
      $this->log(LogLevel::ALERT, $message, $context);
    }

    public function critical($message, array $context = array())
    {
      $this->log(LogLevel::CRITICAL, $message, $context);
    }

    public function error($message, array $context = array())
    {
      $this->log(LogLevel::ERROR, $message, $context);
    }

    public function warning($message, array $context = array())
    {
      $this->log(LogLevel::WARNING, $message, $context);
    }

    public function notice($message, array $context = array())
    {
      $this->log(LogLevel::NOTICE, $message, $context);
    }

    public function info($message, array $context = array())
    {
      $this->log(LogLevel::INFO, $message, $context);
    }

    public function debug($message, array $context = array())
    {
      $this->log(LogLevel::DEBUG, $message, $context);
    }

    public function log($level, $message, array $context = array())
    {
      $content = '[' . date('Y-m-d H:i:s') . '] ' . $level . ':' . $message . "\n";
      file_put_contents($this->file, $content, FILE_APPEND);
    }
}
