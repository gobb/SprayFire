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


}