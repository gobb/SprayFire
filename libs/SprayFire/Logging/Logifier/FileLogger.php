<?php

/**
 * @file
 * @brief Holds a class that allows messages to be logged to a file on disk.
 */

namespace SprayFire\Logging\Logifier;

/**
 * @brief A framework implemented class that adds a timestamp log message to
 * the end of an injected file.
 *
 * @uses SplFileInfo
 * @uses SplFileObject
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
     * @param $openMode String for the mode to open the file
     * @throws InvalidArgumentException thrown if a writable \a $LogFile wasn't passed
     * @see http://www.php.net/manual/en/function.fopen.php
     */
    public function __construct(\SplFileInfo $LogFile, $openMode = 'a') {
        try {
            $this->LogFile = $LogFile->openFile($openMode);
        } catch (\RuntimeException $RuntimeException) {
            throw new \InvalidArgumentException('There was an error attempting to open a writable log file.', null, $RuntimeException);
        }
    }

    /**
     * @brief Will log a message to the \a $LogFile for this object; this method
     * will lock the file before writing and unlock the file afterwards.
     *
     * @details
     * This logger accepts options in the form of an associative array with the
     * following keys
     *
     * <pre>
     * -------------------------------------------------------------------------
     * |  Key               |  Description              |  Default Value       |
     * -------------------------------------------------------------------------
     * |  'length'          | The # of bytes to write   | \strlen($message)    |
     * -------------------------------------------------------------------------
     * |  'timestampFormat' | The format for timestamp  | '[M-d-Y H:i:s]'      |
     * -------------------------------------------------------------------------
     * </pre>
     *
     * @param $message The message string to log
     * @param $options This parameter is not used in this implementation
     * @return int The number of bytes written or null on error
     */
    public function log($message, $options = array()) {
        $timestampFormat = $this->getTimestampFormat($options);
        $message = $this->getTimestampedMessage($message, $timestampFormat);
        $length = $this->getWriteLength($message, $options);
        $this->LogFile->flock(\LOCK_EX);
        $wasWritten = $this->LogFile->fwrite($message, $length);
        $this->LogFile->flock(\LOCK_UN);
        return $wasWritten;
    }

    /**
     * @param $message The message to prepend a timestamp to
     * @param $timestampFormat The format for the prepended timestamp
     * @return A message with the current timestamp prepended to it
     */
    protected function getTimestampedMessage($message, $timestampFormat) {
        if (!isset($message) || empty($message)) {
            $message = 'Blank message.';
        }

        $timestamp = \date($timestampFormat);
        $separator = ' := ';
        return $timestamp . $separator . $message . PHP_EOL;
    }

    /**
     * @param $message The message to determine the write length for
     * @param $options An array of options that may or may not have a length key
     * @return Number of bytes in \a $options['length'] or length of \a $message
     */
    protected function getWriteLength($message, array $options) {
        if (isset($options['length']) && \is_int($options['length'])) {
            $length = $options['length'];
        } else {
            $length = \strlen($message);
        }
        return $length;
    }

    /**
     * @param $options An array of options that may or may not have a timestampFormat key
     * @return The format in \a $options['timestampFormat'] or a default value
     */
    protected function getTimestampFormat(array $options) {
        if (isset($options['timestampFormat']) && \is_string($options['timestampFormat'])) {
            $timestampFormat = $options['timestampFormat'];
        } else {
            $timestampFormat = '[M-d-Y H:i:s]';
        }
        return $timestampFormat;
    }

}