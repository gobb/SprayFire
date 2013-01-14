<?php

/**
 * Ensures that a JSON responder returns appropriate response
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Responder\FireResponder;

use \SprayFire\Responder\FireResponder as FireResponder;

/**
 *
 * @package SprayFireTest
 * @subpackage Cases.Responder.FireResponder
 */
class JsonTest extends \PHPUnit_Framework_TestCase {

    /**
     * Ensures that a Controller with no data passed to it will return the appropriate
     * response
     */
    public function testJsonResponseWithNoDataAndNotTemplates() {
        $Controller = $this->getMock('\SprayFire\Controller\Controller');
        $Responder = new FireResponder\Json();
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $Responder->giveService('Escaper', $Escaper);

        $actual = $Responder->generateDynamicResponse($Controller);

        $this->assertSame(\json_encode(array()), $actual);
    }

    /**
     * Ensures that data from a controller is echoed as JSON appropriately.
     */
    public function testJsonResponseWithOnlyControllerDataAndNoTemplates() {
        $data = array(
            'foo' => 'bar',
            'bar' => '1',
            'baz' => array()
        );
        $Controller = $this->getMock('\SprayFire\Controller\Controller');
        $Controller->expects($this->at(1))
                   ->method('getResponderData')
                   ->will($this->returnValue($data));
        $Responder = new FireResponder\Json();
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $Responder->giveService('Escaper', $Escaper);

        $actual  = $Responder->generateDynamicResponse($Controller);

        $this->assertSame(\json_encode($data), $actual);
    }

}
