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
class GreaterThanEqualTest extends \PHPUnit_Framework_TestCase {

    public function testGreaterThanEqualNoErrorCodeWithValueGreaterThan() {
        $GreaterThanEqual = new FireCheck\GreaterThanEqual(9);
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $GreaterThanEqual->passesCheck(10));
    }

    public function testGreaterThanEqualNoErrorCodeWithValueEqualTo() {
        $GreaterThanEqual = new FireCheck\GreaterThanEqual(9);
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $GreaterThanEqual->passesCheck(9));
    }

    public function testGreaterThanErrorCodeWithLessThanValue() {
        $GreaterThanEqual = new FireCheck\GreaterThanEqual(10);
        $this->assertSame(FireCheck\ErrorCodes::LESS_THAN_ERROR, $GreaterThanEqual->passesCheck(9));
    }

    public function testGettingCheckNameReturnsProperValue() {
        $Check = new FireCheck\GreaterThanEqual(1);
        $this->assertSame('GreaterThanEqual', (string) $Check);
    }

}
