<?php

/**
 * @file
 * @brief Holds the interface to be implemented by objects responsible for logging
 * messages and data.
 */

namespace SprayFire\Logging;

/**
 * @brief An interface for implementing objects that should be responsible for
 * logging various messages to disk.
 */
interface Logger {

    /**
     * @param $message The string message that should be appended to the end
     *        of the log
     * @param $options Data that might need to be used as an option or flag for
     *        the underlying implementation of a logger.
     * @return true if the message was logged, false if it didn't
     */
    public function log($message, $options = null);

}