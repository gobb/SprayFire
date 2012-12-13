<?php

/**
 * Ensures that a SprayFire.Validation.FireValidation.FireResult.Set implementation
 * will properly handle and return appropriate SprayFire.Validation.Result.Result
 * objects.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Validation\FireValidation\FireResult;

use \SprayFire\Validation\Result as SFValidationResult,
    \SprayFire\Validation\FireValidation\FireResult as FireResult;

/**
 *
 * @package SprayFireTest
 * @subpackage Cases.Validation.FireValidation.FireResult
 */
class SetTest extends \PHPUnit_Framework_TestCase {

    /**
     * Ensures that we can add a successful result to a Set and then retrieve
     * the appropriate result from Set::getSuccessfulResults
     */
    public function testAddingAResultAndRetrievingItFromGetSuccessfulResults() {
        $Result = $this->getMockResult(true, 'foo');
        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($Result);
        $expected = array('foo' => array($Result));
        $actual = $ResultSet->getSuccessfulResults();
        $this->assertSame($expected, $actual);
    }

    /**
     * Ensures that we can add a failed result to a Set and then retrieve the
     * appropriate result from Set::getFailedResults
     */
    public function testAddingAResultAndRetrievingItFromGetFailedResults() {
        $Result = $this->getMockResult(false, 'foo');
        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($Result);
        $expected = array('foo' => array($Result));
        $actual = $ResultSet->getFailedResults();
        $this->assertSame($expected, $actual);
    }

    /**
     * Ensures that appropriate successful and failed results are returned from
     * the appropriate Set getter methods.
     */
    public function testAddingMultipleSuccessfulAndFailedResultsAndGettingAppropriateReturnValues() {
        $FirstResult = $this->getMockResult(true, 'firstResult');
        $SecondResult = $this->getMockResult(true, 'secondResult');
        $ThirdResult = $this->getMockResult(false, 'thirdResult');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);
        $ResultSet->addResult($ThirdResult);

        $expectedSuccessful = array(
            'firstResult' => array($FirstResult),
            'secondResult' => array($SecondResult)
        );
        $expectedFailure = array(
            'thirdResult' => array($ThirdResult)
        );

        $this->assertSame($expectedSuccessful, $ResultSet->getSuccessfulResults());
        $this->assertSame($expectedFailure, $ResultSet->getFailedResults());
    }

    /**
     * Ensures that if multiple results are added with the same field name they
     * are handled properly.
     */
    public function testMultipleResultWithSameFieldAndSameValidity() {
        $FirstResult = $this->getMockResult(true, 'foo');
        $SecondResult = $this->getMockResult(true, 'foo');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);

        $expected = array(
            'foo' => array(
                $FirstResult,
                $SecondResult
            )
        );

        $this->assertSame($expected, $ResultSet->getSuccessfulResults());
    }

    /**
     * Ensures that no parameter passed to Set::count is seen as passing
     * SprayFire.Validation.Result.Set::ALL_RESULTS and the appropriate count
     * is returned.
     */
    public function testCountAllResultsAddedToSet() {
        $FirstResult = $this->getMockResult(true, 'foo');
        $SecondResult = $this->getMockResult(true, 'bar');
        $ThirdResult = $this->getMockResult(false, 'baz');
        $FourthResult = $this->getMockResult(false, 'foo');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);
        $ResultSet->addResult($ThirdResult);
        $ResultSet->addResult($FourthResult);

        $this->assertSame(4, $ResultSet->count());
    }

    /**
     * Ensures that we still get right count back if all the fields are the same.
     */
    public function testCountAllResultsForSameField() {
        $FirstResult = $this->getMockResult(true, 'foo');
        $SecondResult = $this->getMockResult(true, 'foo');
        $ThirdResult = $this->getMockResult(true, 'foo');
        $FourthResult = $this->getMockResult(false, 'foo');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);
        $ResultSet->addResult($ThirdResult);
        $ResultSet->addResult($FourthResult);

        $this->assertSame(4, $ResultSet->count());
    }

    /**
     * Ensures that we get count for only successful objects with all same fields
     */
    public function testCountOnlySuccessfulResults() {
        $FirstResult = $this->getMockResult(true, 'foo');
        $SecondResult = $this->getMockResult(true, 'foo');
        $ThirdResult = $this->getMockResult(false, 'foo');
        $FourthResult = $this->getMockResult(false, 'foo');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);
        $ResultSet->addResult($ThirdResult);
        $ResultSet->addResult($FourthResult);

        $this->assertSame(2, $ResultSet->count(SFValidationResult\Set::SUCCESSFUL_RESULTS));
    }

    /**
     * Ensures that we get count for only failure objects with all same fields
     */
    public function testCountOnlyFailureResults() {
        $FirstResult = $this->getMockResult(true, 'foo');
        $SecondResult = $this->getMockResult(false, 'foo');
        $ThirdResult = $this->getMockResult(false, 'foo');
        $FourthResult = $this->getMockResult(false, 'foo');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);
        $ResultSet->addResult($ThirdResult);
        $ResultSet->addResult($FourthResult);

        $this->assertSame(3, $ResultSet->count(SFValidationResult\Set::FAILURE_RESULTS));
    }

    /**
     * Ensures that we trigger the appropriate error if an invalid second parameter
     * is passed to Set::count
     */
    public function testCountTriggersErrorOnInvalidParameter() {
        $FirstResult = $this->getMockResult(true, 'foo');
        $SecondResult = $this->getMockResult(false, 'foo');
        $ThirdResult = $this->getMockResult(false, 'foo');
        $FourthResult = $this->getMockResult(false, 'foo');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);
        $ResultSet->addResult($ThirdResult);
        $ResultSet->addResult($FourthResult);

        $expectedMessage = 'An invalid argument, gobbledygook, was passed to SprayFire\Validation\FireValidation\FireResult\Set::count, please use defined constants';

        $this->setExpectedException('PHPUnit_Framework_Error_Notice', $expectedMessage);
        $ResultSet->count('gobbledygook');
    }

    /**
     * Ensures that we get an expected result from an invalid call to Set::count
     */
    public function testGettingResultsByFieldNameForAll() {
        $FirstResult = $this->getMockResult(true, 'foo');
        $SecondResult = $this->getMockResult(false, 'foo');
        $ThirdResult = $this->getMockResult(false, 'foo');
        $FourthResult = $this->getMockResult(false, 'foo');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);
        $ResultSet->addResult($ThirdResult);
        $ResultSet->addResult($FourthResult);

        $this->assertSame(-1, @$ResultSet->count('gobbledygook'));
    }

    /**
     * Ensures that we return true when we do have failed results stored
     */
    public function testHavingFailedResultsWithFailedResults() {
        $FirstResult = $this->getMockResult(false, 'foo');
        $SecondResult = $this->getMockResult(false, 'foo');
        $ThirdResult = $this->getMockResult(false, 'foo');
        $FourthResult = $this->getMockResult(false, 'foo');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);
        $ResultSet->addResult($ThirdResult);
        $ResultSet->addResult($FourthResult);

        $this->assertTrue($ResultSet->hasFailedResults());
    }

    /**
     * Ensures that we return false if there are no failed results stored
     */
    public function testHavingFailedResultsWithNoFailedResults() {
        $FirstResult = $this->getMockResult(true, 'foo');
        $SecondResult = $this->getMockResult(true, 'foo');
        $ThirdResult = $this->getMockResult(true, 'foo');
        $FourthResult = $this->getMockResult(true, 'foo');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);
        $ResultSet->addResult($ThirdResult);
        $ResultSet->addResult($FourthResult);

        $this->assertFalse($ResultSet->hasFailedResults());
    }

    /**
     * Ensures that we get successful and failed results specific to a field
     * when calling Set::getResultsByFieldName with no second parameter
     */
    public function testGettingResultsByFieldNameAllResults() {
        $FirstResult = $this->getMockResult(true, 'foo');
        $SecondResult = $this->getMockResult(true, 'bar');
        $ThirdResult = $this->getMockResult(false, 'foo');
        $FourthResult = $this->getMockResult(false, 'bar');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);
        $ResultSet->addResult($ThirdResult);
        $ResultSet->addResult($FourthResult);

        $expected = array(
            $FirstResult,
            $ThirdResult
        );

        $this->assertSame($expected, $ResultSet->getResultsByFieldName('foo'));
    }

    /**
     * Ensures that we only get successful results specific to a field name when
     * the appropriate 2nd parameter is passed to Set::getResultsByFieldName
     */
    public function testGettingResultsByFieldNameOnlySuccessfulResults() {
        $FirstResult = $this->getMockResult(true, 'foo');
        $SecondResult = $this->getMockResult(true, 'bar');
        $ThirdResult = $this->getMockResult(false, 'foo');
        $FourthResult = $this->getMockResult(true, 'foo');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);
        $ResultSet->addResult($ThirdResult);
        $ResultSet->addResult($FourthResult);

        $expected = array(
            $FirstResult,
            $FourthResult
        );

        $this->assertSame($expected, $ResultSet->getResultsByFieldName('foo', SFValidationResult\Set::SUCCESSFUL_RESULTS));
    }

    /**
     * Ensures that we only get failed results specific to a field name when
     * the appropriate 2nd parameter is passed to Set::getResultsByFieldName
     */
    public function testGettingResultsByFieldNameOnlyFailureResults() {
        $FirstResult = $this->getMockResult(true, 'foo');
        $SecondResult = $this->getMockResult(true, 'bar');
        $ThirdResult = $this->getMockResult(false, 'foo');
        $FourthResult = $this->getMockResult(true, 'foo');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);
        $ResultSet->addResult($ThirdResult);
        $ResultSet->addResult($FourthResult);

        $expected = array(
            $ThirdResult
        );

        $this->assertSame($expected, $ResultSet->getResultsByFieldName('foo', SFValidationResult\Set::FAILURE_RESULTS));
    }

    /**
     * Ensures that we trigger an error with an invalid parameter passed to
     * Set::getResultsByFieldName.
     */
    public function testTriggeringErrorPassingInvalidArgumentToGetResultsByFieldName() {
        $FirstResult = $this->getMockResult(true, 'foo');
        $SecondResult = $this->getMockResult(true, 'bar');
        $ThirdResult = $this->getMockResult(false, 'foo');
        $FourthResult = $this->getMockResult(true, 'foo');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);
        $ResultSet->addResult($ThirdResult);
        $ResultSet->addResult($FourthResult);

        $expectedMessage = 'An invalid argument, more gobbledygook, was passed to SprayFire\Validation\FireValidation\FireResult\Set::getResultsByFieldName, please use defined constants';

        $this->setExpectedException('PHPUnit_Framework_Error_Notice', $expectedMessage);
        $ResultSet->getResultsByFieldName('foo', 'more gobbledygook');
    }

    /**
     * Ensures that we get back an empty array with invalid 2nd parameter passed
     * to Set::getResultsByFieldName
     */
    public function testReturningEmptyArrayWhenPassingInvalidArgumentToGetResultsByFieldName() {
        $FirstResult = $this->getMockResult(true, 'foo');
        $SecondResult = $this->getMockResult(true, 'bar');
        $ThirdResult = $this->getMockResult(false, 'foo');
        $FourthResult = $this->getMockResult(true, 'foo');

        $ResultSet = new FireResult\Set();
        $ResultSet->addResult($FirstResult);
        $ResultSet->addResult($SecondResult);
        $ResultSet->addResult($ThirdResult);
        $ResultSet->addResult($FourthResult);

        $expected = array();
        $this->assertSame($expected, @$ResultSet->getResultsByFieldName('foo', 'more gobbledygook'));
    }

    /**
     * The parameters passed will be return value for Result::isValid and
     * Result::getFieldName.
     *
     * @param boolean $isValid
     * @param string $fieldName
     * @return SprayFire.Validation.Result.Result
     */
    protected function getMockResult($isValid, $fieldName) {
        $Result = $this->getMock('\SprayFire\Validation\Result\Result');
        $Result->expects($this->once())
                    ->method('isValid')
                    ->will($this->returnValue($isValid));
        $Result->expects($this->once())
                    ->method('getFieldName')
                    ->will($this->returnValue($fieldName));
        return $Result;
    }

}
