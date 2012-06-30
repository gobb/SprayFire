<?php

/**
 * Implemented by objects responsible for logging messages and data.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Logging;

interface Logger {

    /**
     * @param string $message
     * @param mixed $options
     * @return mixed
     */
    public function log($message, $options = null);

}