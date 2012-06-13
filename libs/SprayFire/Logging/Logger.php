<?php

/**
 * Implemented by objects responsible for logging messages and data.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Logging;

interface Logger {

    /**
     * @param $message string Message that should be logged
     * @param $options array Data that might need to be used as an option or flag for
     *        the underlying implementation of a logger.
     * @return boolean true if the message was logged, false if it didn't
     */
    public function log($message, $options = null);

}