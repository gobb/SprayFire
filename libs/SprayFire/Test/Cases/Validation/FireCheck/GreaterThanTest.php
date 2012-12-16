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
class GreaterThanTest extends \PHPUnit_Framework_TestCase {

    public function testGreaterThanReturningNoError() {
        $GreaterThan = new FireCheck\GreaterThan(4);
        $this->assertSame(FireCheck\GreaterThan::NO_ERROR, $GreaterThan->passesCheck(5));
        $this->assertSame(array('value' => '5', 'comparison' => '4'), $GreaterThan->getTokenValues());
    }

    public function testGreaterThanReturningEqualToErrorCode() {
        $GreaterThan = new FireCheck\GreaterThan(4);
        $this->assertSame(FireCheck\GreaterThan::EQUAL_TO_ERROR, $GreaterThan->passesCheck(4));
        $this->assertSame(array('value' => '4', 'comparison' => '4'), $GreaterThan->getTokenValues());
    }

    public function testGreaterThanReturningLessThanErrorCode() {
        $GreaterThan = new FireCheck\GreaterThan(4);
        $this->assertSame(FireCheck\GreaterThan::LESS_THAN_ERROR, $GreaterThan->passesCheck('3'));
        $this->assertSame(array('value' => '3', 'comparison' => '4'), $GreaterThan->getTokenValues());
    }

}
