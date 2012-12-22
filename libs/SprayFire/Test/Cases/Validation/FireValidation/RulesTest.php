<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Validation\FireValidation;

use \SprayFire\Validation\FireValidation as FireValidation;

/**
 *
 * @package SprayFireTest
 * @subpackage Cases
 */
class RulesTest extends \PHPUnit_Framework_TestCase {

    public function testRulesAddingAndGettingCheck() {
        $MockCheck = $this->getMock('\SprayFire\Validation\Check\Check');
        $Rules = new FireValidation\Rules();
        $Rules->addCheck('foo', $MockCheck);

        $FooChecks = $Rules->getChecks('foo');
        $this->assertInstanceOf('SplObjectStorage', $FooChecks);
        $this->assertTrue($FooChecks->contains($MockCheck));
        $this->assertFalse($FooChecks[$MockCheck]);
    }

    public function testRulesGettingFieldChecksWhenNoneAdded() {
        $Rules = new FireValidation\Rules();

        $FooChecks = $Rules->getChecks('foo');
        $this->assertInstanceOf('SplObjectStorage', $FooChecks);
        $this->assertCount(0, $FooChecks);
    }

    public function testRulesAddingAndGettingChecksWithFluentApi() {
        $FirstCheck = $this->getMock('\SprayFire\Validation\Check\Check');
        $SecondCheck = $this->getMock('\SprayFire\Validation\Check\Check');
        $Rules = new FireValidation\Rules();
        $Rules->forField('foo')
              ->add($FirstCheck)
              ->add($SecondCheck, true);

        $FooChecks = $Rules->getChecks('foo');
        $this->assertTrue($FooChecks->contains($FirstCheck));
        $this->assertTrue($FooChecks->contains($SecondCheck));
        $this->assertTrue($FooChecks[$SecondCheck]);
    }

    public function testRulesAddingCheckWithoutSpecifyingFieldWithFluentApi() {
        $FirstCheck = $this->getMock('\SprayFire\Validation\Check\Check');
        $Rules = new FireValidation\Rules();

        $this->setExpectedException('\SprayFire\Validation\Exception\InvalidMethodChain', 'A field must be specified before calling SprayFire\Validation\FireValidation\Rules::add');
        $Rules->add($FirstCheck);
    }

}
