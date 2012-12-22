<?php

/**
 * Default implementation of \SprayFire\Validation\Validator provided by the
 * framework.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Validation\FireValidation;

use \SprayFire\Validation as SFValidation,
    \SprayFire\Validation\Check\FireCheck as FireCheck,
    \SprayFire\Validation\Result\FireResult as FireResult,
    \SprayFire\CoreObject as SFCoreObject;

/**
 * Validates a set of data, passed as an associative array, checks against the
 * Checks stored against the field for those Rules.
 *
 * @package SprayFire
 * @subpackage Validation.FireValidation
 */
class Validator extends SFCoreObject implements SFValidation\Validator {

    /**
     * @param array $data
     * @param \SprayFire\Validation\Rules $Rules
     * @return \SprayFire\Validation\Result\Set
     */
    public function validate(array $data, SFValidation\Rules $Rules) {
        $ResultSet = new FireResult\Set();
        foreach ($data as $field => $value) {
            $Checks = $Rules->getChecks($field);
            foreach ($Checks as $Check) {
                $errorCode = $Check->passesCheck($value);
                $breakOnFailure = false;
                if ($errorCode === FireCheck\ErrorCodes::NO_ERROR) {
                    $passedCheck = true;
                    $messages = array(
                        'log' => '',
                        'display' => ''
                    );
                } else {
                    $passedCheck = false;
                    $messages = $Check->getMessages($errorCode);
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
