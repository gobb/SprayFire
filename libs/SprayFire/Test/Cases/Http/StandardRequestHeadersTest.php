<?php

/**
 * Testing that appropriate headers can be parsed from a $_server array
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Test\Cases\Http;

class StandardRequestHeadersTest extends \PHPUnit_Framework_TestCase {

    public function testParsingServerArrayForCommonHeaders() {
        $headers = array();
        $headers['HTTP_HOST'] = 'www.example.com';
        $headers['HTTP_CONNECTION'] = 'keep-alive';
        $headers['HTTP_CACHE_CONTROL'] = 'max-age=0';
        $headers['HTTP_USER_AGENT'] = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_4)';
        $headers['HTTP_ACCEPT'] = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
        $headers['HTTP_ACCEPT_ENCODING'] = 'gzip,deflate,sdch';
        $headers['HTTP_ACCEPT_LANGUAGE'] = 'en-US,en;q=0.8';
        $headers['HTTP_ACCEPT_CHARSET'] = 'ISO-8859-1,utf-8;q=0.7,*;q=0.3';
        $headers['HTTP_REFERER'] = 'http://www.example.com';
        $headers['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

        $RequestHeaders = new \SprayFire\Http\FireHttp\RequestHeaders($headers);
        $this->assertSame('www.example.com', $RequestHeaders->getHost());
        $this->assertSame('keep-alive', $RequestHeaders->getConnectionType());
        $this->assertSame('max-age=0', $RequestHeaders->getCacheControl());
        $this->assertSame('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_4)', $RequestHeaders->getUserAgent());
        $this->assertSame('text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8', $RequestHeaders->getAcceptType());
        $this->assertSame('gzip,deflate,sdch', $RequestHeaders->getAcceptEncoding());
        $this->assertSame('en-US,en;q=0.8', $RequestHeaders->getAcceptLanguage());
        $this->assertSame('ISO-8859-1,utf-8;q=0.7,*;q=0.3', $RequestHeaders->getAcceptCharset());
        $this->assertSame('http://www.example.com', $RequestHeaders->getReferer());
        $this->assertTrue($RequestHeaders->isAjaxRequest());
    }

    public function testThatRequestIsNotAjax() {
        $_server = array();
        $RequestHeaders = new \SprayFire\Http\FireHttp\RequestHeaders($_server);
        $this->assertFalse($RequestHeaders->isAjaxRequest());
    }

}
