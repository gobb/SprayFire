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

class StringLengthTest extends PHPUnitTestCase {

    public function testStringLengthEqualToThree() {
        $Equal = new FireCheck\Equal(3);
        $StringLength = new FireCheck\StringLength($Equal);

        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $StringLength->passesCheck('foo'));
    }

}
