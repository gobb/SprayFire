<?php

/**
 * @file
 * @brief Holds the interface to be implemented by objects responsible for logging
 * messages and data.
 */

namespace SprayFire\Logger;

/**
 * @brief An interface for implementing objects that should be responsible for
 * logging various messages to disk.
 */
interface Logger {

    /**
     * @brief We have left the parameters for this method blank on purpose; the
     * implementations should be responsible for passing the appropriate arguments.
     *
     * @details
     * It should be noted that if your implementation defines the method signature
     * with parameters you may assign a default value to that parameter and your
     * implementation will satisfy this method signature.
     *
     * @return true if log opened, false if didn't
     */
    public function openLog();

    /**
     * @param $timestamp the timestamp for the given message
     * @param $message The string message that should be appended to the end
     *        of the log
     * @return true if the message was logged, false if it didn't
     */
    public function log($timestamp, $message);

    /**
     * @return true if the log was closed, false if it didn't.
     */
    public function closeLog();

}