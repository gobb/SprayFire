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
class MessageParserTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {

    }

    public function testParsingSingleTokenMessage() {
        $message = 'This is a test of {foo}';
        $Parser = new FireCheck\MessageParser();
        $this->assertSame(
            'This is a test of SprayFire',
            $Parser->parseMessage($message, array('foo' => 'SprayFire'))
        );

    }

    public function tearDown() {

    }

}
