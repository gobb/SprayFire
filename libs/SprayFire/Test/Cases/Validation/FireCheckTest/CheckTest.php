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

    public function setUp() {

    }

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

    public function tearDown() {

    }

}


class CheckHelper extends FireCheck\Check {

    protected function getTokenValues() {
        return array();
    }

    public function passesCheck($value) {
        return false;
    }
}