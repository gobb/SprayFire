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

    public function testParsingSingleTokenMessage() {
        $message = 'This is a test of {foo}';
        $Parser = new FireCheck\MessageParser();
        $this->assertSame(
            'This is a test of SprayFire',
            $Parser->parseMessage($message, array('foo' => 'SprayFire'))
        );
    }

    public function testParsingMultipleTokenMessageWithNewDelimiter() {
        $message = 'This is a test of [foo] and [bar]';
        $Parser = new FireCheck\MessageParser('[', ']');
        $this->assertSame(
            'This is a test of SprayFire and regex',
            $Parser->parseMessage($message, array('foo' => 'SprayFire', 'bar' => 'regex'))
        );
    }

}
