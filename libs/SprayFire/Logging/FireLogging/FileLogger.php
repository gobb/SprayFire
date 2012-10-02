<?php

/**
 * Implementation of SprayFire.Logging.Logger that will store information to a
 * specific file on disk.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Logging\FireLogging;

use \SprayFire\Logging as SFLogging,
    \SprayFire\CoreObject as SFCoreObject;

/**
 *
 * @package package
 * @subpackage Logging.FireLogging
 */
class FileLogger extends SFCoreObject implements SFLogging\Logger  {

    /**
     * Representation of the file we are writing to.
     *
     * @property SplFileObject
     */
    protected $LogFile;

    /**
     * @param SplFileInfo $LogFile
     * @param string $openMode
     *
     * @todo
     * Perhaps the fact that we are using an SplFileInfo object is an implementation
     * detail and not something we should force the user to provide?  We should
     * take a look at changing $LogFile to a string and we'll take care of the
     * rest of the stuff.
     *
     * Or perhaps we should let them provide this object and they can then read
     * from it in some other code.
     */
    public function __construct(\SplFileInfo $LogFile, $openMode = 'a') {
        try {
            $this->LogFile = $LogFile->openFile($openMode);
        } catch (\RuntimeException $RuntimeException) {
            throw new \InvalidArgumentException('There was an error attempting to open a writable log file.', null, $RuntimeException);
        }
    }

    /**
     * Log a message to the \a $LogFile, returning the number of bytes written
     * or null if there was an error.
     *
     * Please note that this implementation will lock the file before writing and
     * unlock the file afterwards.
     *
     * This logger accepts options in the form of an associative array with the
     * following keys:
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
     * @param string $message
     * @param array $options
     * @return mixed
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
     * Will return a format to be used when logging to a file, the format is
     * expected to be used with PHP's date() function.
     *
     * If the $options array has a 'timestampFormat' option the format will be of
     * the chosen type, otherwise a default value will be returned.
     *
     * @param array $options
     * @return string
     */
    protected function getTimestampFormat(array $options) {
        if (isset($options['timestampFormat']) && \is_string($options['timestampFormat'])) {
            $timestampFormat = $options['timestampFormat'];
        } else {
            $timestampFormat = '[M-d-Y H:i:s]';
        }
        return $timestampFormat;
    }

    /**
     * Will return a string with the timestamp of the creation and the $message
     * passed appended to one another.
     *
     * @param string $message
     * @param string $timestampFormat
     * @return string
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
     * Returns the number of bytes that should be written to the file, if the
     * 'length' attribute is set as an integer to $options this value will be
     * used otherwise the string length of the $message will be returned.
     *
     * @param string $message
     * @param array $options
     * @return int
     */
    protected function getWriteLength($message, array $options) {
        if (isset($options['length']) && \is_int($options['length'])) {
            $length = $options['length'];
        } else {
            $length = \strlen($message);
        }
        return $length;
    }



}