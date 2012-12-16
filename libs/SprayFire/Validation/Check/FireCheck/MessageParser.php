<?php

/**
 * Default implementaiton of SprayFire.Validation.Check.MessageParser provided
 * by the framework.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Validation\Check\FireCheck;

use \SprayFire\Validation\Check as SFValidationCheck,
    \SprayFire\CoreObject as SFCoreObject;

/**
 *
 *
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
class MessageParser extends SFCoreObject implements SFValidationCheck\MessageParser {

    /**
     * The beginning delimiter to match tokens in $message being parsed
     *
     * @property string
     */
    protected $startDelimiter;

    /**
     * The ending delimiter to match tokens in $message being parsed
     *
     * @property string
     */
    protected $endDelimiter;

    /**
     * @param string $startDelimiter
     * @param string $endDelimiter
     */
    public function __construct($startDelimiter = '{', $endDelimiter = '}') {
        $this->startDelimiter = (string) $startDelimiter;
        $this->endDelimiter = (string) $endDelimiter;
    }

    /**
     * @param string $message
     * @param array $tokenValues
     * @return string
     */
    public function parseMessage($message, array $tokenValues) {
        foreach ($tokenValues as $token => $value) {
            $regex = $this->getTokenPattern($token);
            $message = \preg_replace($regex, $value, $message);
        }
        return $message;
    }

    /**
     * @param string $token
     * @return string
     */
    protected function getTokenPattern($token) {
        return \sprintf('/\%s%s\%s/', $this->startDelimiter, $token, $this->endDelimiter);
    }

}