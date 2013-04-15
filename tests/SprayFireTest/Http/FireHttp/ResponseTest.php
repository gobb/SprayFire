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

    public function testSettingToNotFoundStatus() {
        $Response = new FireHttp\Response();
        $Response->setStatusCode($Response::STATUS_404);
        $this->assertSame(404, $Response->getStatusCode());
    }

    public function testSettingStatusCodeForcesInteger() {
        $Response = new FireHttp\Response();
        $this->assertSame($Response, $Response->setStatusCode('404'));
        $this->assertSame(404, $Response->getStatusCode());
    }

    public function testGettingDefaultStatusReason() {
        $Response = new FireHttp\Response();
        $this->assertSame('OK', $Response->getStatusReason());
    }

    public function testSettingStatusCodeSetsAppropriateReason() {
        $Response = new FireHttp\Response();
        $Response->setStatusCode($Response::STATUS_404);
        $this->assertSame('Not Found', $Response->getStatusReason());
    }

    public function testSettingStatusCodePassingOptionDoesNotSetReason() {
        $Response = new FireHttp\Response();
        $this->assertSame('OK', $Response->getStatusReason());

        $Response->setStatusCode($Response::STATUS_404, $Response::DO_NOT_SET_REASON);
        $this->assertSame(404, $Response->getStatusCode());
        $this->assertSame('OK', $Response->getStatusReason());
    }

    public function test200CodeIsOk() {
        $Response = new FireHttp\Response();
        $Response->setStatusCode($Response::STATUS_200);
        $this->assertTrue($Response->isOk(), 'The Response was not seen as OK');
    }

}
