<?php

/**
 * Testing a Range test to ensure appropriate error codes are returned and respecting
 * clusivity is honored.
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
class RangeTest extends PHPUnitTestCase {

    /**
     * Ensures that a value between the range is properly returned with NO_ERROR
     * with defaulting to an exclusive check.
     */
    public function testRangeMinAndMaxDefaultSettings() {
        $Check = new FireCheck\Range(1, 10);
        $code = $Check->passesCheck(2);
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $code);
    }

    /**
     * Ensures that a value between the range returns a MINIMUM_LIMIT_ERROR with
     * defaulting to an exclusive check.
     */
    public function testRangeBreakingMinError() {
        $Check = new FireCheck\Range(5,10);
        $code = $Check->passesCheck(4);
        $this->assertSame(FireCheck\ErrorCodes::MINIMUM_LIMIT_ERROR, $code);
    }

    /**
     * Ensures that a value between the range returns a MAXIMUM_LIMIT_ERROR with
     * defaulting to an exclusive check
     */
    public function testRangeBreakingMaxError() {
        $Check = new FireCheck\Range(5,10);
        $code = $Check->passesCheck(11);
        $this->assertSame(FireCheck\ErrorCodes::MAXIMUM_LIMIT_ERROR, $code);
    }

    /**
     * Ensures that with no third parameter passed to the constructor the check
     * on the minimum side is exclusive.
     */
    public function testRangeCheckingExclusivityOnMinimumLimit() {
        $Check = new FireCheck\Range(5, 10);
        $code = $Check->passesCheck(5);
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $code);
    }

}
