<?php

/**
 * Ensures that a JSON responder returns appropriate response
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFireTest\Responder\FireResponder;

use \SprayFire\Responder\FireResponder as FireResponder;

/**
 *
 * @package SprayFireTest
 * @subpackage Responder.FireResponder
 */
class JsonTest extends \PHPUnit_Framework_TestCase {

    /**
     * @return \SprayFire\Responder\FireResponder\Json
     */
    protected function getJsonResponder() {
        $Builder = $this->getMock('\SprayFire\Service\Builder');
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $Responder = new FireResponder\Json($Builder);
        $Responder->giveService('Escaper', $Escaper);
        return $Responder;
    }

    /**
     * Ensures that a Controller with no data passed to it will return the appropriate
     * response
     */
    public function testJsonResponseWithNoDataAndNotTemplates() {
        $Response = $this->getMock('\SprayFire\Http\Response');
        $Response->expects($this->once())
                 ->method('setBody')
                 ->with(\json_encode([]));

        $Responder = $this->getJsonResponder();
        $Responder->giveService('Response', $Response);

        $Controller = $this->getMock('\SprayFire\Controller\Controller');
        $ActualResponse = $Responder->generateResponse($Controller);
        $this->assertInstanceOf('\SprayFire\Http\Response', $ActualResponse);
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

        $Response = $this->getMock('\SprayFire\Http\Response');
        $Response->expects($this->once())
                 ->method('setBody')
                 ->with(\json_encode($data));

        $Responder = $this->getJsonResponder();
        $Responder->giveService('Response', $Response);

        $ActualResponse  = $Responder->generateResponse($Controller);
        $this->assertInstanceOf('\SprayFire\Http\Response', $ActualResponse);
    }

}
