<?php

/**
 * @file
 * @brief
 */

namespace SprayFireTest\Helpers;

use \SprayFire\Logging as SFLogging,
    \SprayFire\CoreObject as SFCoreObject;

/**
 * @package SprayFireTest
 * @subpackage Helpers
 */
class DevelopmentLogger extends SFCoreObject implements SFLogging\Logger {

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
