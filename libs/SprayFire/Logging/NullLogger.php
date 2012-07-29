<?php

/**
 * SprayFire.Logger.Logger implementation that acts as a Null logger
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Logging;

use \SprayFire\Logging\Logger as Logger,
    \SprayFire\CoreObject as CoreObject;

class NullLogger extends CoreObject implements Logger {

    /**
     * @param $message string The message to log
     * @param $options null This parameter is not used in this implementation
     * @return boolean Always returns true
     */
    public function log($message, $options = null) {
        return true;
    }

}
