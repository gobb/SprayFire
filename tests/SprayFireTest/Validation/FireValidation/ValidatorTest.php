<?php

/**
 * Test to ensure that \SprayFire\Validation\FireValidation\Validator is properly
 * implemented and integrated with other \SprayFire\Validation modules.
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

    /**
     * @property \SprayFire\Validation\FireValidation\Validator
     */
    protected $Validator;

    public function setUp() {
        $MessageParser = new FireCheck\MessageParser();
        $Validator = new FireValidation\Validator($MessageParser);
        $this->Validator = $Validator;
    }

    /**
     * Ensures that one field with one rule returns the appropriate
     * \SprayFire\Validation\Result\FireResult\Set with the correct successful
     * results.
     */
    public function testValidatorCheckingOneFieldToOneRule() {
        $data = array(
            'foo' => 1
        );
        $Equal = new FireCheck\Equal(1);
        $Rules = new FireValidation\Rules();
        $Rules->forField('foo')->add($Equal);

        $ResultSet = $this->Validator->validate($data, $Rules);

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

        $ResultSet = $this->Validator->validate($data, $Rules);

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

        $ResultSet = $this->Validator->validate($data, $Rules);

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

        $ResultSet = $this->Validator->validate($data, $Rules);

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

        $Rules = new FireValidation\Rules();
        $Rules->forField('foo')->add($Equal)->add($GreaterThan, true)->add($LessThan);

        $ResultSet = $this->Validator->validate($data, $Rules);

        $successfulResults = $ResultSet->getResultsByFieldName('foo', $ResultSet::SUCCESSFUL_RESULTS);
        $failureResults = $ResultSet->getResultsByFieldName('foo', $ResultSet::FAILURE_RESULTS);
        $expectedSuccessful = array(
            'Equal'
        );
        $expectedFailure = array(
            'GreaterThan'
        );

        $counter = 0;
        foreach ($successfulResults as $Result) {
            /* @var \SprayFire\Validation\Result\Result $Result */
            $this->assertSame($expectedSuccessful[$counter], $Result->getCheckName());
            $counter++;
        }

        $counter = 0;
        foreach ($failureResults as $Result) {
            $this->assertSame($expectedFailure[$counter], $Result->getCheckName());
            $counter++;
        }
    }

    public function testValidatorTestingMultipleFieldsAndMultipleRulesAllPassed() {
        $data = array(
            'foo' => 1,
            'bar' => 2,
            'foobar' => 3
        );

        $EqualOne = new FireCheck\Equal(1);
        $EqualTwo = new FireCheck\Equal(2);
        $EqualThree = new FireCheck\Equal(3);
        $LessThanFour = new FireCheck\LessThan(4);
        $GreaterThanZero = new FireCheck\GreaterThan(0);

        $Rules = new FireValidation\Rules();
        $Rules->forField('foo')->add($EqualOne)->add($LessThanFour)->add($GreaterThanZero);
        $Rules->forField('bar')->add($EqualTwo)->add($LessThanFour)->add($GreaterThanZero);
        $Rules->forField('foobar')->add($EqualThree)->add($LessThanFour)->add($GreaterThanZero);

        $ResultSet = $this->Validator->validate($data, $Rules);

        $this->assertSame(9, $ResultSet->count($ResultSet::SUCCESSFUL_RESULTS));
        $this->assertSame(0, $ResultSet->count($ResultSet::FAILURE_RESULTS));
        $this->assertCount(3, $ResultSet->getResultsByFieldName('foo', $ResultSet::SUCCESSFUL_RESULTS));
        $this->assertCount(3, $ResultSet->getResultsByFieldName('bar', $ResultSet::SUCCESSFUL_RESULTS));
        $this->assertCount(3, $ResultSet->getResultsByFieldName('foobar', $ResultSet::SUCCESSFUL_RESULTS));
    }

    public function testValidatorParsingCheckMessages() {
        $data = array(
            'foo' => 1
        );

        $EqualCheck = new FireCheck\Equal(2);
        $EqualCheck->setLogMessage('{value} !== {comparison}', FireCheck\ErrorCodes::NOT_EQUAL_TO_ERROR);
        $EqualCheck->setDisplayMessage('{comparison} !== {value}', FireCheck\ErrorCodes::NOT_EQUAL_TO_ERROR);
        $Rules = new FireValidation\Rules();
        $Rules->forField('foo')->add($EqualCheck);

        $ResultSet = $this->Validator->validate($data, $Rules);
        $Results = $ResultSet->getResultsByFieldName('foo', $ResultSet::FAILURE_RESULTS);
        /** @var \SprayFire\Validation\Result\Result $Result */
        $Result = $Results[0];

        $this->assertSame('1 !== 2', $Result->getLogMessage());
        $this->assertSame('2 !== 1', $Result->getDisplayMessage());
    }

}
