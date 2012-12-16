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
class EqualTest extends \PHPUnit_Framework_TestCase {

    public function testEqualCheckReturnsProperErrorCodeForFailedCheck() {
        $Equal = new FireCheck\Equal('other frameworks');
        $this->assertSame(FireCheck\Equal::DEFAULT_ERROR_CODE, $Equal->passesCheck('SprayFire'));
        $this->assertSame(array('value' => 'SprayFire', 'comparison' => 'other frameworks'), $Equal->getTokenValues());
    }

    public function testEqualCheckReturnsProperNoErrorCodeForPassedCheck() {
        $Equal = new FireCheck\Equal('SprayFire');
        $this->assertSame(FireCheck\Equal::NO_ERROR, $Equal->passesCheck('SprayFire'));
        $this->assertSame(array('value' => 'SprayFire', 'comparison' => 'SprayFire'), $Equal->getTokenValues());
    }

}
