<?php

/**
 * Check to ensure that a string value length passes a Check injected at construction
 * time.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */
namespace SprayFire\Validation\Check\FireCheck;

use \SprayFire\Validation\Check as SFCheck;

/**
 * @package SprayFire
 * @subpackage Validation.Check.FireCheck
 */
class StringLength extends ComparisonCheck {

    /**
     * @property \SprayFire\Validation\Check\Check
     */
    protected $Check;

    /**
     * @param \SprayFire\Validation\Check\Check $Check
     */
    public function __construct(SFCheck\Check $Check) {
        $this->Check = $Check;
    }

    /**
     * Will take the string length and pass to the Check injected at construction
     * time.
     *
     * @param mixed $value
     * @return integer
     */
    public function passesCheck($value) {
        $checkValue = \strlen((string) $value);
        parent::passesCheck($checkValue);
        return $this->Check->passesCheck($checkValue);
    }

    /**
     * @return string
     */
    protected function getCheckName() {
        return 'StringLength';
    }

}
