<?php

/**
 * Testing the implementation of the LessThanEqual validation check to ensure
 * appropriate error codes are returned.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Test\Cases\Validation\FireCheck;

use \SprayFire\Validation\Check\FireCheck as FireCheck,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @package SprayFireTest
 * @subpackage Cases.Validation.FireCheck
 */
class LessThanEqualTest extends PHPUnitTestCase {

    /**
     * Ensures that NO_ERROR_CODE is returned for values that are less than the
     * passed constructor parameter.
     */
    public function testValueBeingLessThanEqualWithALessThanValue() {
        $Check = new FireCheck\LessThanEqual(3);
        $code = $Check->passesCheck(2);
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $code);
    }

    /**
     * Ensures that NO_ERROR_CODE is returned for values that are equal to the
     * passed constructor parameter.
     */
    public function testValueBeingLessThanEqualWithAnEqualValue() {
        $Check = new FireCheck\LessThanEqual(3);
        $code = $Check->passesCheck(3);
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $code);
    }

    /**
     * Ensures that a GREATER_THAN_ERROR code is returned for a value greater than
     * the injected comparison parameter.
     */
    public function testValueReturningGreaterThanErrorCodeForGreaterThanValue() {
        $Check = new FireCheck\LessThanEqual(3);
        $code = $Check->passesCheck(4);
        $this->assertSame(FireCheck\ErrorCodes::GREATER_THAN_ERROR, $code);
    }

}
