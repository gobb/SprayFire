<?php

/**
 * Default implementation of SprayFire.Validation.Validator provided by the
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
 *
 *
 * @package SprayFire
 * @subpackage Validation.FireValidation
 */
class Validator extends SFCoreObject implements SFValidation\Validator {

    /**
     * @param array $data
     * @param \SprayFire\Validation\Rules $Rules
     * @return \SprayFire\Validation\Result.Set
     */
    public function validate(array $data, SFValidation\Rules $Rules) {
        $ResultSet = new FireResult\Set();

        $fieldName = 'foo';
        $fieldValue = $data['foo'];
        foreach ($Rules->getChecks('foo') as $Check) {
            $errorCode = $Check->passesCheck($fieldValue);

            if ($errorCode === FireCheck\ErrorCodes::NO_ERROR) {
                $passedCheck = true;
                $messages = array(
                    'log' => '',
                    'display' => ''
                );

            } else {
                $passedCheck = false;
                $messages = $Check->getMessages($errorCode);
            }

            $Result = new FireResult\Result($fieldName, $fieldValue, (string) $Check, $passedCheck, $messages);
            $ResultSet->addResult($Result);
        }

        return $ResultSet;
    }

}
