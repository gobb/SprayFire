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
class GreaterThanEqualTest extends \PHPUnit_Framework_TestCase {

    public function testGreaterThanEqualNoErrorCodeWithValueGreaterThan() {
        $GreaterThanEqual = new FireCheck\GreaterThanEqual(9);
        $this->assertSame(FireCheck\GreaterThanEqual::NO_ERROR, $GreaterThanEqual->passesCheck(10));
    }

    public function testGreaterThanEqualNoErrorCodeWithValueEqualTo() {
        $GreaterThanEqual = new FireCheck\GreaterThanEqual(9);
        $this->assertSame(FireCheck\GreaterThanEqual::NO_ERROR, $GreaterThanEqual->passesCheck(9));
    }

    public function testGreaterThanErrorCodeWithLessThanValue() {
        $GreaterThanEqual = new FireCheck\GreaterThanEqual(10);
        $this->assertSame(FireCheck\GreaterThanEqual::LESS_THAN_ERROR, $GreaterThanEqual->passesCheck(9));
    }

}
