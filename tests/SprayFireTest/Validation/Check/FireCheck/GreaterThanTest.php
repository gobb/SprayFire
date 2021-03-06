<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFireTest\Validation\Check\FireCheck;

use \SprayFire\Validation\Check\FireCheck as FireCheck;

/**
 *
 * @package SprayFireTest
 * @subpackage Validation.Check.FireCheck
 */
class GreaterThanTest extends \PHPUnit_Framework_TestCase {

    public function testGreaterThanReturningNoError() {
        $GreaterThan = new FireCheck\GreaterThan(4);
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $GreaterThan->passesCheck(5));
    }

    public function testGreaterThanReturningEqualToErrorCode() {
        $GreaterThan = new FireCheck\GreaterThan(4);
        $this->assertSame(FireCheck\ErrorCodes::EQUAL_TO_ERROR, $GreaterThan->passesCheck(4));
    }

    public function testGreaterThanReturningLessThanErrorCode() {
        $GreaterThan = new FireCheck\GreaterThan(4);
        $this->assertSame(FireCheck\ErrorCodes::LESS_THAN_ERROR, $GreaterThan->passesCheck('3'));
    }

    public function testGettingCheckNameReturnsProperValue() {
        $Check = new FireCheck\GreaterThan(1);
        $this->assertSame('GreaterThan', (string) $Check);
    }

}
