<?php

/**
 *
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
 *
 *
 * @package SprayFire
 * @subpackage Test.Cases.Validation.FireCheck
 */
class AlphanumericTest extends PHPUnitTestCase {

    public function testAlphanumericReturningNoErrorWithoutSpaces() {
        $Check = new FireCheck\Alphanumeric();
        $code = $Check->passesCheck('alph4b3ticstringwith4umbers');
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $code);
    }

}
