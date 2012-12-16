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

    public function setUp() {

    }

    public function testEqualCheckReturnsProperErrorCodeForFailedCheck() {
        $MessageParser = new FireCheck\MessageParser();
        $Equal = new FireCheck\Equal($MessageParser);
        $Equal->setParameter(FireCheck\Equal::COMPARISON_PARAMETER, 'other frameworks');

        $this->assertSame(FireCheck\Equal::DEFAULT_ERROR_CODE, $Equal->passesCheck('SprayFire'));
    }

    public function testEqualCheckReturnsProperNoErrorCodeForPassedCheck() {
        $MessageParser = new FireCheck\MessageParser();
        $Equal = new FireCheck\Equal($MessageParser);
        $Equal->setParameter(FireCheck\Equal::COMPARISON_PARAMETER, 'SprayFire');

        $this->assertSame(FireCheck\Equal::NO_ERROR, $Equal->passesCheck('SprayFire'));
    }

    public function testEqualCheckGetsAppropriateMessage() {
        $MessageParser = new FireCheck\MessageParser();
        $Equal = new FireCheck\Equal($MessageParser);
        $Equal->setParameter(FireCheck\Equal::COMPARISON_PARAMETER, 'other frameworks');

        $comparisonToken = FireCheck\Equal::COMPARISON_TOKEN;
        $Equal->setLogMessage('{value} does not equal {' . $comparisonToken . '}');

        $expected = array(
            'log' => 'SprayFire does not equal other frameworks',
            'display' => ''
        );

        $Equal->passesCheck('SprayFire');
        $this->assertSame($expected, $Equal->getMessages());
    }

    public function testEqualCheckWithNoComparisonParameter() {
        $MessageParser = new FireCheck\MessageParser();
        $Equal = new FireCheck\Equal($MessageParser);

        $this->assertSame(FireCheck\Equal::NO_ERROR, $Equal->passesCheck(''));
    }

    public function tearDown() {

    }

}
