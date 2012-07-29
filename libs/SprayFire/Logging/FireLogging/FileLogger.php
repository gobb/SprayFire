<?php

/**
 * A logger that allows messages to be logged to a file on disk.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Logging\FireLogging;

use \SprayFire\Logging\Logger as Logger,
    \SprayFire\CoreObject as CoreObject;

class FileLogger extends CoreObject implements Logger  {

    /**
     * Holds the file we write messages to
     *
     * @property SplFileObject
     */
    protected $LogFile;

    /**
     * @param SplFileInfo $LogFile
     * @param string $openMode
     */
    public function __construct(\SplFileInfo $LogFile, $openMode = 'a') {
        try {
            $this->LogFile = $LogFile->openFile($openMode);
        } catch (\RuntimeException $RuntimeException) {
            throw new \InvalidArgumentException('There was an error attempting to open a writable log file.', null, $RuntimeException);
        }
    }

    /**
     * Log a message to the \a $LogFile; will lock the file before writing and
     * unlock the file afterwards.
     *
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
     * @param $message string The message string to log
     * @param $options array This parameter is not used in this implementation
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
     * @param $message string The message to prepend a timestamp to
     * @param $timestampFormat string The format for the prepended timestamp
     * @return string A message with the current timestamp prepended to it
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
     * @param $message string The message to determine the write length for
     * @param $options array An array of options that may or may not have a length key
     * @return int Number of bytes in \a $options['length'] or length of \a $message
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
     * @param $options array An array of options that may or may not have a timestampFormat key
     * @return string The format in \a $options['timestampFormat'] or a default value
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