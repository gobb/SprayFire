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

    public function testOnlyCodesInRange200AreSuccessful() {
        $Response = new FireHttp\Response();
        $Response->setStatusCode($Response::STATUS_200);
        $this->assertTrue($Response->isSuccess(), 'The Response was not seen as Success with 200');

        $Response->setStatusCode($Response::STATUS_206);
        $this->assertTrue($Response->isSuccess(), 'The Response was not seen as Success with 206');

        $Response->setStatusCode($Response::STATUS_208);
        $this->assertTrue($Response->isSuccess(), 'The Response was not seen as Success with 208');

        $Response->setStatusCode($Response::STATUS_300);
        $this->assertFalse($Response->isSuccess(), 'The Response was seen as Success with 300');
    }

    public function testOnlyCodesInRange300AreRedirect() {
        $Response = new FireHttp\Response();

        $Response->setStatusCode($Response::STATUS_300);
        $this->assertTrue($Response->isRedirect(), 'The Response was not seen as Redirect with 300');

        $Response->setStatusCode($Response::STATUS_305);
        $this->assertTrue($Response->isRedirect(), 'The Response was not seen as Redirect with 305');

        $Response->setStatusCode($Response::STATUS_308);
        $this->assertTrue($Response->isRedirect(), 'The Response was not seen as Redirect with 308');

        $Response->setStatusCode($Response::STATUS_200);
        $this->assertFalse($Response->isRedirect(), 'The Response was seen as a Redirect with 200');
    }

    public function testOnlyCodesInRange400AreClientError() {
        $Response = new FireHttp\Response();

        $Response->setStatusCode($Response::STATUS_400);
        $this->assertTrue($Response->isClientError(), 'The Response was not seen as Client Error with 400');

        $Response->setStatusCode($Response::STATUS_425);
        $this->assertTrue($Response->isClientError(), 'The Response was not seen as Client Error with 425');

        $Response->setStatusCode($Response::STATUS_499);
        $this->assertTrue($Response->isClientError(), 'The Response was not seen as Client Error with 499');

        $Response->setStatusCode($Response::STATUS_500);
        $this->assertFalse($Response->isClientError(), 'The Response was seen as Client Error with 500');
    }

    public function testOnlyCodesInRange500AreServerError() {
        $Response = new FireHttp\Response();

        $Response->setStatusCode($Response::STATUS_500);
        $this->assertTrue($Response->isServerError(), 'The Response was not seen as Server Error with 500');

        $Response->setStatusCode($Response::STATUS_510);
        $this->assertTrue($Response->isServerError(), 'The Response was not seen as Server Error with 510');

        $Response->setStatusCode($Response::STATUS_599);
        $this->assertTrue($Response->isServerError(), 'The Response was not seen as Server Error with 599');

        $Response->setStatusCode($Response::STATUS_400);
        $this->assertFalse($Response->isServerError(), 'The Response was seen as Server Error with 400');
    }

    public function test404CodeIsNotFound() {
        $Response = new FireHttp\Response();
        $Response->setStatusCode($Response::STATUS_404);
        $this->assertTrue($Response->isNotFound(), 'The Response was not Not Found with 404');
    }

}
