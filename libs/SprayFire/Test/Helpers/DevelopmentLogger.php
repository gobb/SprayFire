<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Helpers;

/**
 * @brief
 */
class DevelopmentLogger extends \SprayFire\Core\Util\CoreObject implements \SprayFire\Logging\Logger {

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
