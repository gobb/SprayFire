<?php

/**
 * Default implementation of \SprayFire\Validation\Validator provided by the
 * framework.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Validation\FireValidation;

use \SprayFire\Validation,
    \SprayFire\Validation\Check as SFCheck,
    \SprayFire\Validation\Check\FireCheck,
    \SprayFire\Validation\Result\FireResult,
    \SprayFire\StdLib;

/**
 * Validates a set of data, passed as an associative array, checks against the
 * Checks stored against the field for those Rules.
 *
 * @package SprayFire
 * @subpackage Validation.FireValidation
 */
class Validator extends StdLib\CoreObject implements Validation\Validator {

    /**
     * @property \SprayFire\Validation\Check\MessageParser
     */
    protected $MessageParser;

    /**
     * @param \SprayFire\Validation\Check\MessageParser $MessageParser
     */
    public function __construct(SFCheck\MessageParser $MessageParser) {
        $this->MessageParser = $MessageParser;
    }

    /**
     * @param array $data
     * @param \SprayFire\Validation\Rules $Rules
     * @return \SprayFire\Validation\Result\Set
     */
    public function validate(array $data, Validation\Rules $Rules) {
        $ResultSet = new FireResult\Set();
        foreach ($data as $field => $value) {
            $Checks = $Rules->getChecks($field);
            /** @var \SprayFire\Validation\Check\Check $Check */
            foreach ($Checks as $Check) {
                $errorCode = $Check->passesCheck($value);
                $breakOnFailure = false;
                if ($errorCode === FireCheck\ErrorCodes::NO_ERROR) {
                    $passedCheck = true;
                    $messages = [
                        'log' => '',
                        'display' => ''
                    ];
                } else {
                    $passedCheck = false;
                    $messages = $Check->getMessages($errorCode);
                    $tokenValues = $Check->getTokenValues();
                    $logMessage = $this->MessageParser->parseMessage($messages['log'], $tokenValues);
                    $displayMessage = $this->MessageParser->parseMessage($messages['display'], $tokenValues);
                    $messages = [
                        'log' => $logMessage,
                        'display' => $displayMessage
                    ];
                    $breakOnFailure = $Checks[$Check];
                }

                $Result = new FireResult\Result($field, $value, (string) $Check, $passedCheck, $messages);
                $ResultSet->addResult($Result);

                if ($breakOnFailure) {
                    break;
                }
            }
        }

        return $ResultSet;
    }

}
