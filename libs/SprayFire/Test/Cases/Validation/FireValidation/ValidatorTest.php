<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Validation\FireValidation;

use \SprayFire\Validation\FireValidation as FireValidation,
    \SprayFire\Validation\Check\FireCheck as FireCheck;

/**
 *
 * @package SprayFireTest
 * @subpackage Cases.Validation.FireValidation
 */
class ValidatorTest extends \PHPUnit_Framework_TestCase {

    public function testValidatorCheckingOneFieldToOneRule() {
        $data = array(
            'foo' => 1
        );
        $Equal = new FireCheck\Equal(1);
        $Rules = new FireValidation\Rules();
        $Rules->forField('foo')
              ->add($Equal);

        $Validator = new FireValidation\Validator();
        $ResultSet = $Validator->validate($data, $Rules);

        $this->assertInstanceOf('\SprayFire\Validation\Result\Set', $ResultSet);

        $Results = $ResultSet->getResultsByFieldName('foo');

        $this->assertCount(1, $Results);
        $this->assertSame('foo', $Results[0]->getFieldName());
        $this->assertSame(1, $Results[0]->getFieldValue());
        $this->assertSame((string) $Equal, $Results[0]->getCheckName());
        $this->assertTrue($Results[0]->passedCheck());
        $this->assertSame('', $Results[0]->getLogMessage());
        $this->assertSame('', $Results[0]->getDisplayMessage());
    }

    public function testValidatorCheckingOneFieldOneRuleUnformattedLogAndDisplayMessages() {
        $data = array(
            'foo' => 1
        );
        $Equal = new FireCheck\Equal(2);
        $logMessage = 'The unformatted log message from not equal to error';
        $displayMessage = 'The unformatted display message from not equal to error';
        $Equal->setLogMessage($logMessage, FireCheck\ErrorCodes::NOT_EQUAL_TO_ERROR);
        $Equal->setDisplayMessage($displayMessage, FireCheck\ErrorCodes::NOT_EQUAL_TO_ERROR);
        $Rules = new FireValidation\Rules();
        $Rules->addCheck('foo', $Equal);

        $Validator = new FireValidation\Validator();
        $ResultSet = $Validator->validate($data, $Rules);

        $Results = $ResultSet->getResultsByFieldName('foo', $ResultSet::FAILURE_RESULTS);
        $this->assertSame($logMessage, $Results[0]->getLogMessage());
        $this->assertSame($displayMessage, $Results[0]->getDisplayMessage());
    }

    public function testValidatorCheckingOneFieldMultiplePassedChecks() {
        $data = array(
            'foo' => 1
        );
        $Equal = new FireCheck\Equal(1);
        $GreaterThan = new FireCheck\GreaterThan(0);
        $LessThan = new FireCheck\LessThan(2);

        $Rules = new FireValidation\Rules();
        $Rules->forField('foo')->add($Equal)->add($GreaterThan)->add($LessThan);

        $Validator = new FireValidation\Validator();
        $ResultSet = $Validator->validate($data, $Rules);

        $this->assertCount(3, $ResultSet->getResultsByFieldName('foo', $ResultSet::SUCCESSFUL_RESULTS));
        $this->assertCount(0, $ResultSet->getResultsByFieldName('foo', $ResultSet::FAILURE_RESULTS));
    }

    public function testValidatorCheckingOneFieldMultipleTimesSomePassedSomeFailed() {
        $data = array(
            'foo' => 1
        );
        $Equal = new FireCheck\Equal(1);
        $GreaterThan = new FireCheck\GreaterThan(0);
        $LessThan = new FireCheck\LessThan(1);

        $Rules = new FireValidation\Rules();
        $Rules->forField('foo')->add($Equal)->add($GreaterThan)->add($LessThan);

        $Validator = new FireValidation\Validator();
        $ResultSet = $Validator->validate($data, $Rules);

        $successfulResults = $ResultSet->getResultsByFieldName('foo', $ResultSet::SUCCESSFUL_RESULTS);
        $failureResults = $ResultSet->getResultsByFieldName('foo', $ResultSet::FAILURE_RESULTS);

        $this->assertCount(2, $successfulResults);
        $this->assertCount(1, $failureResults);

        $expectedSuccessful = array(
            'Equal',
            'GreaterThan'
        );
        $expectedFailures = array(
            'LessThan'
        );

        $counter = 0;
        foreach ($successfulResults as $Result) {
            $this->assertSame($expectedSuccessful[$counter], $Result->getCheckName());
            $counter++;
        }

        $counter = 0;
        foreach ($failureResults as $Result) {
            $this->assertSame($expectedFailures[$counter], $Result->getCheckName());
            $counter++;
        }
    }

    public function testValidatorCheckingOneFieldMultipleTimesBreakingOnFailure() {
        $data = array(
            'foo' => 1
        );
        $Equal = new FireCheck\Equal(1);
        $GreaterThan = new FireCheck\GreaterThan(1);
        $LessThan = new FireCheck\LessThan(2);

        
    }



}
