<?php

/**
 *
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
 *
 *
 * @package SprayFireTest
 * @subpackage Validation.Check.FireCheck
 */
class AlphabeticTest extends PHPUnitTestCase {

    public function testAlphabeticStringIsValid() {
        $Check = new FireCheck\Alphabetic();
        $code = $Check->passesCheck('thisisanalphabeticstring');
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $code);
    }

    public function testAlphabeticStringIsValidWithSpaces() {
        $Check = new FireCheck\Alphabetic(FireCheck\Alphabetic::IGNORE_SPACES);
        $code = $Check->passesCheck('This is an alphabetic string with spaces but no tabs or new lines');
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $code);
    }

    public function testNonAlphabeticStringReturnsProperErrorCode() {
        $Check = new FireCheck\Alphabetic();
        $code = $Check->passesCheck('notstrictly4lph4b3tic');
        $this->assertSame(FireCheck\ErrorCodes::NOT_ALPHABETIC_ERROR, $code);
    }

    public function testGettingCheckNameReturnsProperValue() {
        $Check = new FireCheck\Alphabetic();
        $this->assertSame('Alphabetic', (string) $Check);
    }

}
