<?php

/**
 * @file
 * @brief Holds a class that allows messages to be logged to a file on disk.
 */

namespace SprayFire\Logging;

/**
 * @brief A framework implemented class that adds a timestamp log message to
 * the end of an injected file.
 *
 * @uses SplFileInfo
 * @uses InvalidArgumentException
 * @uses SprayFire.Logging.Logger
 * @uses SprayFire.Core.Util.CoreObject
 */
class FileLogger extends \SprayFire\Core\Util\CoreObject implements \SprayFire\Logging\Logger  {

    /**
     * @brief A SplFileObject that should be used to write log messages to.
     *
     * @property $LogFile
     */
    protected $LogFile;

    /**
     * @param $LogFile SplFileObject that should have log messages written to
     * @throws InvalidArgumentException thrown if a writable \a $LogFile wasn't passed
     */
    public function __construct(\SplFileInfo $LogFile) {
        try {
            $this->LogFile = $LogFile->openFile('a');
        } catch (\RuntimeException $RuntimeException) {
            throw new \InvalidArgumentException('There was an error attempting to open a writable log file.', null, $RuntimeException);
        }
    }

    /**
     * @param $timestamp A formatted timestamp string
     * @param $message The message string to log
     * @return int The number of bytes written or null on error
     */
    public function log($message) {
        if (!isset($timestamp) || empty($timestamp)) {
            $timestamp = '00-00-0000 00:00:00';
        }

        if (!isset($message) || empty($message)) {
            $message = 'Blank message.';
        }

        $separator = ' := ';
        $message = $timestamp . $separator . $message . PHP_EOL;
        $this->LogFile->flock(\LOCK_EX);
        $wasWritten = $this->LogFile->fwrite($message);
        $this->LogFile->flock(\LOCK_UN);
        return $wasWritten;
    }

}