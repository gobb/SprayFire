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
class EqualTest extends \PHPUnit_Framework_TestCase {

    public function testEqualCheckReturnsProperErrorCodeForFailedCheck() {
        $Equal = new FireCheck\Equal('other frameworks');
        $this->assertSame(FireCheck\ErrorCodes::NOT_EQUAL_TO_ERROR, $Equal->passesCheck('SprayFire'));
    }

    public function testEqualCheckReturnsProperNoErrorCodeForPassedCheck() {
        $Equal = new FireCheck\Equal('SprayFire');
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $Equal->passesCheck('SprayFire'));
    }

    public function testGettingCheckNameReturnsProperValue() {
        $Check = new FireCheck\Equal(1);
        $this->assertSame('Equal', (string) $Check);
    }

}
