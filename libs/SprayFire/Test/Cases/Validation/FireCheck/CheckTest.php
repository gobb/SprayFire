<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases;

use \SprayFire\Validation\Check\FireCheck as FireCheck;

/**
 *
 * @package SprayFireTest
 * @subpackage Cases
 */
class CheckTest extends \PHPUnit_Framework_TestCase {

    public function testCheckGettingUnformattedMessagesForDefaultErrorCode() {
        $MessageParser = new FireCheck\MessageParser();
        $Check = new CheckHelper($MessageParser);

        $Check->setLogMessage('An unformatted message saying foo');
        $Check->setDisplayMessage('Sorry, we could not get your foo.');

        $expected = array(
            'log' => 'An unformatted message saying foo',
            'display' => 'Sorry, we could not get your foo.'
        );

        $this->assertSame($expected, $Check->getMessages());
    }

    public function testCheckGettingFormattedLogMessageAndBlankDisplayForDefaultErrorCode() {
        $MessageParser = new FireCheck\MessageParser();
        $Check = new CheckHelper($MessageParser);

        $Check->setLogMessage('The value being checked is {value}');

        $expected = array(
            'log' => 'The value being checked is SprayFire',
            'display' => ''
        );

        $Check->passesCheck('SprayFire');
        $this->assertSame($expected, $Check->getMessages());
    }

    public function testCheckGettingFormattedDisplayMessageAndBlankLogForDefaultErrorCode() {
        $MessageParser = new FireCheck\MessageParser();
        $Check = new CheckHelper($MessageParser);

        $Check->setDisplayMessage('{value} === {value}');

        $expected = array(
            'log' => '',
            'display' => 'SprayFire === SprayFire'
        );

        $Check->passesCheck('SprayFire');
        $this->assertSame($expected, $Check->getMessages());
    }

    public function testCheckGettingFormattedDisplayMessageAndLogForDefaultErrorCodeMultipleTokens() {
        $MessageParser = new FireCheck\MessageParser();
        $Check = new CheckHelper($MessageParser);

        $Check->setLogMessage('Logging {value} to {foo}');
        $Check->setDisplayMessage('{value} === {foo}');

        $expected = array(
            'log' => 'Logging SprayFire to unit tested',
            'display' => 'SprayFire === unit tested'
        );

        $Check->passesCheck('SprayFire');
        $Check->setToken('foo', 'unit tested');
        $this->assertSame($expected, $Check->getMessages());
    }

}


class CheckHelper extends FireCheck\Check {

    protected function getTokenValues() {
        return $this->tokenValues;
    }

    public function setToken($name, $value) {
        $this->setTokenValue($name, $value);
    }
}