<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Validation\FireCheck;

use \SprayFire\Validation\Check\FireCheck as FireCheck;

/**
 *
 * @package SprayFireTest
 * @subpackage Cases
 */
class LessThanTest extends \PHPUnit_Framework_TestCase {

    public function testLessThanReturnsNoErrorCodeWithValidValue() {
        $LessThan = new FireCheck\LessThan(4);
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $LessThan->passesCheck(3));
    }

    public function testLessThanReturnsEqualToErrorCodeWithSameValue() {
        $LessThan = new FireCheck\LessThan(4);
        $this->assertSame(FireCheck\ErrorCodes::EQUAL_TO_ERROR, $LessThan->passesCheck(4));
    }

    public function testLessThanReturnsGreaterThanErrorCodeWithBiggerValue() {
        $LessThan = new FireCheck\LessThan(4);
        $this->assertSame(FireCheck\ErrorCodes::GREATER_THAN_ERROR, $LessThan->passesCheck(5));
    }

    public function testGettingCheckNameReturnsProperValue() {
        $Check = new FireCheck\LessThan(1);
        $this->assertSame('LessThan', (string) $Check);
    }

}
