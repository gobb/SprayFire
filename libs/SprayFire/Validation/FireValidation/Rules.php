<?php

/**
 * Default implementation of SprayFire.Validation.Rules provided by SprayFire
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Validation\FireValidation;

use \SprayFire\Validation as SFValidation,
    \SprayFire\Validation\Check as SFValidationCheck,
    \SprayFire\Validation\Exception as SFValidationException,
    \SprayFire\CoreObject as SFCoreObject,
    \SplObjectStorage as SplObjectStorage;

/**
 * This implementation allows for the use of a Fluent API to add SprayFire.Validation.Check.Check
 * objects against a given field; this Fluent API is completely optional and the
 * same actions can be performed in a non-fluent manner.
 *
 * @package SprayFire
 * @subpackage Validaiton.FireValidation
 */
class Rules extends SFCoreObject implements SFValidation\Rules {

    /**
     * Array of [field => SplObjectStorage[Check => breakOnFailure]]
     *
     * @property array
     */
    protected $fieldRules = array();

    /**
     * For the Fluent API section of this implementation determines what field
     * to associate calls to Rules::add()
     *
     * @property string
     */
    protected $activeField;

    /**
     * Will add the passed $Check as the next in order to be ran for $field.
     *
     * @param string $field
     * @param SprayFire.Validation.Check.Check $Check
     * @param boolean $breakOnFailure
     */
    public function addCheck($field, SFValidationCheck\Check $Check, $breakOnFailure = false) {
        $field = (string) $field;
        $this->createFieldStorage($field);
        $this->fieldRules[$field][$Check] = (boolean) $breakOnFailure;
    }

    /**
     * If the $field does not have a SplObjectStorage created for it one will be
     * added to $fieldRules
     *
     * @param string $field
     */
    protected function createFieldStorage($field) {
        if (!isset($this->fieldRules[$field])) {
            $this->fieldRules[$field] = new SplObjectStorage();
        }
    }

    /**
     * Return the storage of $Check added against $field
     *
     * @param string $field
     * @return SplObjectStorage
     */
    public function getChecks($field) {
        // We are creating field storage just in case a collection of checks for
        // $field is requested before any are added to the collection
        $this->createFieldStorage($field);
        return $this->fieldRules[$field];
    }

    // Fluent API not part of implemented interface is below

    /**
     * Sets the field that all successive add() calls will store the $Check against.
     *
     * @param string $field
     * @return SprayFire.Validation.FireValidation.Rules
     */
    public function forField($field) {
        $this->activeField = (string) $field;
        return $this;
    }

    /**
     * Adds a $Check to the storage for the field set by the last call to Rules::forField
     *
     * @param SprayFire.Validation.Check $Check
     * @param boolean $breakOnFailure
     * @return SprayFire.Validation.FireValidation.Rules
     */
    public function add(SFValidationCheck\Check $Check, $breakOnFailure = false) {
        if (!isset($this->activeField)) {
            throw new SFValidationException\InvalidMethodChain('A field must be specified before calling ' . __METHOD__);
        }
        $this->addCheck($this->activeField, $Check, $breakOnFailure);
        return $this;
    }

}