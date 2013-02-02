<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFireTest\Validation\Check\FireCheck;

use SprayFire\Validation\Check\FireCheck as FireCheck;

/**
 *
 * @package SprayFireTest
 * @subpackage Validation.Check.FireCheck
 */
class EmailTest extends \PHPUnit_Framework_TestCase {

    public function testEmailPassingNoErrorCodeWithValidEmail() {
        $Email = new FireCheck\Email();
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $Email->passesCheck('something@example.com'));
    }

    public function testEmailReturningProperErrorCodeWithInvalidEmail() {
        $Email = new FireCheck\Email();
        $this->assertsame(FireCheck\ErrorCodes::INVALID_EMAIL_ERROR, $Email->passesCheck('obviously not an email address'));
    }

    public function testGettingCheckNameReturnsProperValue() {
        $Check = new FireCheck\Email();
        $this->assertSame('Email', (string) $Check);
    }

}
