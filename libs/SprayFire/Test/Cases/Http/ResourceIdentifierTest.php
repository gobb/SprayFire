<?php

/**
 * Ensures that the ResourceIdentifier can properly parse the $_server array
 * passed to it
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Test\Cases\Http;

use \SprayFire\Http\FireHttp\ResourceIdentifier as ResourceId;

class ResourceIdentifierTest extends \PHPUnit_Framework_TestCase {

    public function testHttpUriWithNoPathOrQueryNoWww() {
        $_server = array();
        $_server['HTTP_HOST'] = 'sprayfire-framework.local';
        $_server['REMOTE_PORT'] = 800;
        $ResourceIdentifier = new ResourceId($_server);

        $this->assertSame('http://sprayfire-framework.local:800', (string) $ResourceIdentifier);
        $this->assertSame('http', $ResourceIdentifier->getScheme());
        $this->assertSame('sprayfire-framework.local:800', $ResourceIdentifier->getAuthority());
        $this->assertSame('', $ResourceIdentifier->getPath());
        $this->assertSame('', $ResourceIdentifier->getQuery());
    }

    public function testHttpUriWithPathAndQueryWithWww() {
        $_server = array();
        $_server['HTTP_HOST'] = 'www.example.com';
        $_server['QUERY_STRING'] = 'foo=bar&bar=baz';
        $_server['REQUEST_URI'] = '/path/to/your/resource/';
        $Resource = new ResourceId($_server);
        $this->assertSame('http://www.example.com:80/path/to/your/resource/?foo=bar&bar=baz', (string) $Resource);
    }

    public function testTwoHttpUriObjectsEqual() {
        $_server = array();
        $_server['HTTP_HOST'] = 'sprayfire-framework.local';
        $_server['REMOTE_PORT'] = 800;
        $ResourceOne = new ResourceId($_server);
        $ResourceTwo = new ResourceId($_server);

        $this->assertTrue($ResourceOne->equals($ResourceTwo));
    }

    public function testTwoHttpUriObjectsNotEqual() {
        $_server1 = array();
        $_server1['HTTP_HOST'] = 'sprayfire-framework.local';
        $_server1['REMOTE_PORT'] = 800;

        $_server2 = array();
        $_server2['HTTP_HOST'] = 'www.example.com';
        $ResourceOne = new ResourceId($_server1);
        $ResourceTwo = new ResourceId($_server2);
        $this->assertFalse($ResourceOne->equals($ResourceTwo));
    }


}