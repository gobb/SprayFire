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
        $Controller->expects($this->once())
                   ->method('getResponderData')
                   ->will($this->returnValue(array()));
        $Responder = new FireResponder\Json();
        \ob_start();
        $Responder->generateDynamicResponse($Controller);
        $actual = \ob_get_contents();
        \ob_end_clean();
        $this->assertSame(\json_encode(array()), $actual);
    }

}
