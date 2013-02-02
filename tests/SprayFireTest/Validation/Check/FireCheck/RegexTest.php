<?php

/**
 * A validation check that will ensure a string matches a regular expression
 * injected into the constructor.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Test\Cases\Validation\FireCheck;

use \SprayFire\Validation\Check\FireCheck as FireCheck,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @package SprayFireTest
 * @subpackage Validation.Check.FireCheck
 */
class RegexTest extends PHPUnitTestCase {

    public function testSimpleRegexReturnsNoError() {
        $Regex = new FireCheck\Regex('/^SprayFire$/');
        $code = $Regex->passesCheck('SprayFire');
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $code);
    }

    public function testRegexNotMatchingReturnsProperCode() {
        $Regex = new FireCheck\Regex('/^SprayFire$/');
        $code = $Regex->passesCheck('Not SprayFire');
        $this->assertSame(FireCheck\ErrorCodes::REGEX_NOT_MATCHED_ERROR, $code);
    }

    public function testGettingCheckNameReturnsProperValue() {
        $Check = new FireCheck\Regex('');
        $this->assertSame('Regex', (string) $Check);
    }

}
