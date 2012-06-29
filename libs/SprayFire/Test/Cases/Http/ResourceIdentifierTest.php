<?php

/**
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Test\Cases\Http;

class ResourceIdentifierTest extends \PHPUnit_Framework_TestCase {

    public function testHttpUriWithNoPathOrQueryNoWww() {
        $_server = array();
        $_server['HTTP_HOST'] = 'sprayfire-framework.local';
        $_server['REMOTE_PORT'] = 800;
        $ResourceIdentifier = new \SprayFire\Http\ResourceIdentifier($_server);

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
        $Resource = new \SprayFire\Http\ResourceIdentifier($_server);
        $this->assertSame('http://www.example.com:80/path/to/your/resource/?foo=bar&bar=baz', (string) $Resource);
    }

    public function testTwoHttpUriObjectsEqual() {
        $_server = array();
        $_server['HTTP_HOST'] = 'sprayfire-framework.local';
        $_server['REMOTE_PORT'] = 800;
        $ResourceOne = new \SprayFire\Http\ResourceIdentifier($_server);
        $ResourceTwo = new \SprayFire\Http\ResourceIdentifier($_server);

        $this->assertTrue($ResourceOne->equals($ResourceTwo));
    }

    public function testTwoHttpUriObjectsNotEqual() {
        $_server1 = array();
        $_server1['HTTP_HOST'] = 'sprayfire-framework.local';
        $_server1['REMOTE_PORT'] = 800;

        $_server2 = array();
        $_server2['HTTP_HOST'] = 'www.example.com';
        $ResourceOne = new \SprayFire\Http\ResourceIdentifier($_server1);
        $ResourceTwo = new \SprayFire\Http\ResourceIdentifier($_server2);
        $this->assertFalse($ResourceOne->equals($ResourceTwo));
    }


}