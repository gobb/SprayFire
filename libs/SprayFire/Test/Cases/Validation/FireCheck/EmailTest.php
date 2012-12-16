<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Validation\FireCheck;

use SprayFire\Validation\Check\FireCheck as FireCheck;

/**
 *
 * @package SprayFireTest
 * @subpackage Cases
 */
class EmailTest extends \PHPUnit_Framework_TestCase {

    public function testEmailPassingNoErrorCodeWithValidEmail() {
        $Email = new FireCheck\Email();
        $this->assertSame(FireCheck\ErrorCodes::NO_ERROR, $Email->passesCheck('something@example.com'));
    }

}
