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
        $this->assertSame(FireCheck\LessThan::NO_ERROR, $LessThan->passesCheck(3));
        $this->assertSame(array('value' => '3', 'comparison' => '4'), $LessThan->getTokenValues());
    }

    public function testLessThanReturnsEqualToErrorCodeWithSameValue() {
        $LessThan = new FireCheck\LessThan(4);
        $this->assertSame(FireCheck\LessThan::EQUAL_TO_ERROR, $LessThan->passesCheck(4));
        $this->assertSame(array('value' => '4', 'comparison' => '4'), $LessThan->getTokenValues());
    }

    public function testLessThanReturnsGreaterThanErrorCodeWithBiggerValue() {
        $LessThan = new FireCheck\LessThan(4);
        $this->assertSame(FireCheck\LessThan::GREATER_THAN_ERROR, $LessThan->passesCheck(5));
        $this->assertSame(array('value' => '5', 'comparison' => '4'), $LessThan->getTokenValues());
    }

}
