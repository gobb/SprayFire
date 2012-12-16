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
        $MessageParser = new FireCheck\MessageParser();
        $GreaterThan = new FireCheck\GreaterThan($MessageParser);
        $GreaterThan->setParameter(FireCheck\GreaterThan::COMPARISON_PARAMETER, '4');

        $this->assertSame(FireCheck\GreaterThan::NO_ERROR, $GreaterThan->passesCheck(5));
    }

    public function testGreaterThanReturningEqualToErrorCode() {
        $MessageParser = new FireCheck\MessageParser();
        $GreaterThan = new FireCheck\GreaterThan($MessageParser);
        $GreaterThan->setParameter(FireCheck\GreaterThan::COMPARISON_PARAMETER, '4');

        $this->assertSame(FireCheck\GreaterThan::EQUAL_TO_ERROR, $GreaterThan->passesCheck(4));
    }

    public function testGreaterThanReturningLessThanErrorCode() {
        $MessageParser = new FireCheck\MessageParser();
        $GreaterThan = new FireCheck\GreaterThan($MessageParser);
        $GreaterThan->setParameter(FireCheck\GreaterThan::COMPARISON_PARAMETER, '4');

        $this->assertSame(FireCheck\GreaterThan::LESS_THAN_ERROR, $GreaterThan->passesCheck('3'));
    }

}
