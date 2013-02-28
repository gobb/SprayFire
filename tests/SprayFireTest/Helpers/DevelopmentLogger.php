<?php

/**
 * @file
 * @brief
 */

namespace SprayFireTest\Helpers;

use \SprayFire\Logging as SFLogging,
    \SprayFire\StdLib as SFStdLib;

/**
 * @package SprayFireTest
 * @subpackage Helpers
 */
class DevelopmentLogger extends SFStdLib\CoreObject implements SFLogging\Logger {

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
