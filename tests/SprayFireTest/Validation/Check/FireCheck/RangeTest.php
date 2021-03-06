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
namespace SprayFireTest\Validation\Check\FireCheck;

use \SprayFire\Validation\Check\FireCheck as FireCheck,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @package SprayFireTest
 * @subpackage Validation.Check.FireCheck
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

    /**
     * Ensures that with no third parameter passed to the constructor the check
     * on the maximum side is exclusive.
     */
    public function testRangeCheckingExclusivityOnMaximumLimit() {
        $Check = new FireCheck\Range(5, 10);
        $code = $Check->passesCheck(10);
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $code);
    }

    /**
     * Ensures that an inclusive check properly returns NO_ERROR for value within
     * the range.
     */
    public function testRangeCheckingInclusivityWithinRange() {
        $Check = new FireCheck\Range(5, 10, FireCheck\Range::INCLUSIVE_CHECK);
        $code = $Check->passesCheck(6);
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $code);
    }

    /**
     * Ensures that an inclusive check properly returns MINIMUM_LIMIT_ERROR for
     * a value equal to the minimum limit.
     */
    public function testRangeCheckInclusivityOnMinimumLimit() {
        $Check = new FireCheck\Range(3, 7, FireCheck\Range::INCLUSIVE_CHECK);
        $code = $Check->passesCheck(3);
        $this->assertSame(FireCheck\ErrorCodes::MINIMUM_LIMIT_ERROR, $code);
    }

    /**
     * Ensures that an inclusive check properly returns MAXIMUM_LIMIT_ERROR for
     * a value equal to the maximum limit.
     */
    public function testRangeCheckInclusivityOnMaximumLimit() {
        $Check = new FireCheck\Range(0, 14, FireCheck\Range::INCLUSIVE_CHECK);
        $code = $Check->passesCheck(14);
        $this->assertSame(FireCheck\ErrorCodes::MAXIMUM_LIMIT_ERROR, $code);
    }

    /**
     * Ensures that if an invalid third parameter is passed to constructor that
     * we trigger an appropriate error with the expected message.
     */
    public function testRangeTriggeringErrorWhenInvalidThirdParameterPassed() {
        $expectedMessage = 'Invalid check type passed to SprayFire\Validation\Check\FireCheck\Range. Exclusive check used by default.';
        $this->setExpectedException('PHPUnit_Framework_Error_Notice', $expectedMessage);
        $Check = new FireCheck\Range(0, 14, 'notvalid');
    }

    /**
     * Ensures that we are doing a proper exclusive check when an invalid type is
     * passed.
     */
    public function testRangeCheckingExlcusiveWhenInvalidThirdParameterPassed() {
        $Check = @new FireCheck\Range(0, 14, 'notvalid');
        $code = $Check->passesCheck(14);
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $code);
    }

    public function testGettingCheckNameReturnsProperValue() {
        $Check = new FireCheck\Range(1, 1);
        $this->assertSame('Range', (string) $Check);
    }

}
