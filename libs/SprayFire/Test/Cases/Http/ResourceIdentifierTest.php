<?php

/**
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Test\Cases\Http;

class ResourceIdentifierTest extends \PHPUnit_Framework_TestCase {

    public function testBasicHttpUriWithNoPathOrQuery() {
        $_server = array();
        $_server['HTTP_HOST'] = 'sprayfire-framework.local';
        $_server['REMOTE_PORT'] = 800;
        $ResourceIdentifer = new \SprayFire\Http\ResourceIdentifier($_server);

        $this->assertSame('http://sprayfire-framework.local:800', (string) $ResourceIdentifer);
        $this->assertSame('http', $ResourceIdentifer->getScheme());
        $this->assertSame('sprayfire-framework.local:800', $ResourceIdentifer->getAuthority());
        $this->assertSame('', $ResourceIdentifer->getPath());
        $this->assertSame('', $ResourceIdentifer->getQuery());
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