<?php

/**
 *
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFireTest\Http\FireHttp;

use \SprayFire\Http\FireHttp as FireHttp,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 *
 *
 * @package SprayFireTest
 * @subpackage Http.FireHttp
 */
class RequestTest extends PHPUnitTestCase {

    public function testGetUriForRequest() {
        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Headers = $this->getMock('\SprayFire\Http\RequestHeaders');
        $_server = array();

        $Request = new FireHttp\Request($Uri, $Headers, $_server);
        $this->assertSame($Uri, $Request->getUri());
    }

    public function testGetHeadersForRequest() {
        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Headers = $this->getMock('\SprayFire\Http\RequestHeaders');
        $_server = array();

        $Request = new FireHttp\Request($Uri, $Headers, $_server);
        $this->assertSame($Headers, $Request->getHeaders());
    }

    public function testGetMethodForRequest() {
        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Headers = $this->getMock('\SprayFire\Http\RequestHeaders');
        $_server = array(
            'REQUEST_METHOD' => FireHttp\Request::METHOD_POST
        );

        $Request = new FireHttp\Request($Uri, $Headers, $_server);
        $this->assertSame(FireHttp\Request::METHOD_POST, $Request->getMethod());
    }

    public function testGetVersionForRequest() {
        $Uri = $this->getMock('\SprayFire\Http\Uri');
        $Headers = $this->getMock('\SprayFire\Http\RequestHeaders');
        $_server = array(
            'SERVER_PROTOCOL' => 'HTTP/1.1'
        );

        $Request = new FireHttp\Request($Uri, $Headers, $_server);
        $this->assertSame('1.1', $Request->getVersion());
    }

}
