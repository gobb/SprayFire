<?php

/**
 * Implementation of SprayFire.Logging.Logger designed to perform no operations.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Logging;

use \SprayFire\CoreObject as SFCoreObject;

/**
 * Primary use case for this object is as a return value to an implementation of
 * a Logger factory.
 *
 * @package SprayFire
 * @subpackage Logging
 *
 * @codeCoverageIgnore
 */
class NullLogger extends SFCoreObject implements Logger {

    /**
     * Performs no operation, always returns true.
     *
     * @param string $message
     * @param mixed $options
     * @return boolean
     */
    public function log($message, $options = null) {
        return true;
    }

}
