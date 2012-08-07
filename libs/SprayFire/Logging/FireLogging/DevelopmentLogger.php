<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Logging\FireLogging;

use \SprayFire\Logging\Logger as Logger,
    \SprayFire\CoreObject as CoreObject;

/**
 * @brief
 */
class DevelopmentLogger extends CoreObject implements Logger {

    protected $loggedMessages = array();

    public function log($message, $options = null) {
        $index = \count($this->loggedMessages);
        $this->loggedMessages[$index] = array();
        $this->loggedMessages[$index]['message'] = $message;
        $this->loggedMessages[$index]['options'] = $options;
        return true;
    }

    public function getLoggedMessages() {
        return $this->loggedMessages;
    }

}
