<?php

/**
 * Ensures that the Uri can properly parse the $_server array
 * passed to it
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFireTest\Http\FireHttp;

use \SprayFire\Http\FireHttp as FireHttp;

class UriTest extends \PHPUnit_Framework_TestCase {

    public function testHttpUriWithNoPathOrQueryNoWww() {
        $_server = array();
        $_server['HTTP_HOST'] = 'sprayfire-framework.local';
        $_server['REMOTE_PORT'] = 800;
        $Uri = new FireHttp\Uri($_server);

        $this->assertSame('http://sprayfire-framework.local:800', (string) $Uri);
        $this->assertSame('http', $Uri->getScheme());
        $this->assertSame('sprayfire-framework.local:800', $Uri->getAuthority());
        $this->assertSame('', $Uri->getPath());
        $this->assertSame('', $Uri->getQuery());
        $this->assertSame(800, $Uri->getPort());
    }

    public function testHttpUriWithPathAndQueryWithWww() {
        $_server = array();
        $_server['HTTP_HOST'] = 'www.example.com';
        $_server['QUERY_STRING'] = 'foo=bar&bar=baz';
        $_server['REQUEST_URI'] = '/path/to/your/resource/';
        $Resource = new FireHttp\Uri($_server);
        $this->assertSame('http://www.example.com:80/path/to/your/resource/?foo=bar&bar=baz', (string) $Resource);
    }

    public function testTwoHttpUriObjectsEqual() {
        $_server = array();
        $_server['HTTP_HOST'] = 'sprayfire-framework.local';
        $_server['REMOTE_PORT'] = 800;
        $ResourceOne = new FireHttp\Uri($_server);
        $ResourceTwo = new FireHttp\Uri($_server);

        $this->assertTrue($ResourceOne->equals($ResourceTwo));
    }

    public function testTwoHttpUriObjectsNotEqual() {
        $_server1 = array();
        $_server1['HTTP_HOST'] = 'sprayfire-framework.local';
        $_server1['REMOTE_PORT'] = 800;

        $_server2 = array();
        $_server2['HTTP_HOST'] = 'www.example.com';
        $ResourceOne = new FireHttp\Uri($_server1);
        $ResourceTwo = new FireHttp\Uri($_server2);
        $this->assertFalse($ResourceOne->equals($ResourceTwo));
    }

    public function testGettingHttpsScheme() {
        $_server = array(
            'HTTPS' => true
        );
        $Resource = new FireHttp\Uri($_server);

        $this->assertSame('https', $Resource->getScheme());
    }

    public function testReturningFalseWhenNonUriObjectPassedToEquals() {
        $Resource = new FireHttp\Uri();
        $NotResource = $this->getMock('\SprayFire\Object');

        $this->assertFalse($Resource->equals($NotResource));
    }

    public function testEnsureWeOnlyGetRequestUriWithoutGetParameters() {
        $_server = array(
            'REQUEST_URI' => '/sprayfire/?foo=bar'
        );
        $Resource = new FireHttp\Uri($_server);

        $this->assertSame('/sprayfire/', $Resource->getPath());
    }

}
