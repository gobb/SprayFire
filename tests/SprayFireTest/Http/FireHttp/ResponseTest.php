<?php

/**
 * 
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFireTest\Http\FireHttp;

use \SprayFire\Http\FireHttp;

use \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 *
 *
 * @package SprayFireTest
 * @subpackage 
 */
class ResponseTest extends PHPUnitTestCase {

    public function testGettingOkStatusCodeAsDefault() {
        $Response = new FireHttp\Response();
        $this->assertSame(200, $Response->getStatusCode());
    }

}
