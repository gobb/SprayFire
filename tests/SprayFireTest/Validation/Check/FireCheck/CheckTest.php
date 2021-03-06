<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFireTest\Validation\Check\FireCheck;

use \SprayFire\Validation\Check\FireCheck as FireCheck;

/**
 *
 * @package SprayFireTest
 * @subpackage Validation.Check.FireCheck
 */
class CheckTest extends \PHPUnit_Framework_TestCase {

    public function testCheckGettingUnformattedMessagesForDefaultErrorCode() {
        $Check = new CheckHelper();

        $Check->setLogMessage('An unformatted message saying foo', 1);
        $Check->setDisplayMessage('Sorry, we could not get your foo.', 1);

        $expected = array(
            'log' => 'An unformatted message saying foo',
            'display' => 'Sorry, we could not get your foo.'
        );

        $this->assertSame($expected, $Check->getMessages(1));
        // expecting no token values because passedCheck wasn't called
        $this->assertSame(array(), $Check->getTokenValues());
    }

    public function testCheckGettingFormattedLogMessageAndBlankDisplayForDefaultErrorCode() {
        $Check = new CheckHelper();

        $Check->setLogMessage('The value being checked is {value}', 1);

        $expected = array(
            'log' => 'The value being checked is {value}',
            'display' => ''
        );

        $Check->passesCheck('SprayFire');
        $this->assertSame($expected, $Check->getMessages(1));
        $this->assertSame(array('value' => 'SprayFire'), $Check->getTokenValues());
    }

    public function testCheckGettingFormattedDisplayMessageAndBlankLogForDefaultErrorCode() {
        $MessageParser = new FireCheck\MessageParser();
        $Check = new CheckHelper($MessageParser);

        $Check->setDisplayMessage('{value} === {value}', 1);

        $expected = array(
            'log' => '',
            'display' => '{value} === {value}'
        );

        $Check->passesCheck('SprayFire');
        $this->assertSame($expected, $Check->getMessages(1));
        $this->assertSame(array('value' => 'SprayFire'), $Check->getTokenValues());
    }

    public function testCheckGettingFormattedDisplayMessageAndLogForDefaultErrorCodeMultipleTokens() {
        $Check = new CheckHelper();

        $Check->setLogMessage('Logging {value} to {foo}', 1);
        $Check->setDisplayMessage('{value} === {foo}', 1);

        $expected = array(
            'log' => 'Logging {value} to {foo}',
            'display' => '{value} === {foo}'
        );

        $Check->passesCheck('SprayFire');
        $Check->setToken('foo', 'unit tested');
        $this->assertSame($expected, $Check->getMessages(1));
        $this->assertSame(array('value' => 'SprayFire', 'foo' => 'unit tested'), $Check->getTokenValues());
    }

}


class CheckHelper extends FireCheck\Check {

    public function setToken($name, $value) {
        $this->setTokenValue($name, $value);
    }

    protected function getCheckName() {
        return 'CheckHelper';
    }

}
