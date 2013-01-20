<?php

/**
 *
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Test\Cases\Session\FireSession;

use \SprayFire\Session\FireSession as FireSession,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 *
 *
 * @package SprayFireTest
 * @subpackage Cases.Session.FireSession
 */
class StorageTest extends PHPUnitTestCase {

    /**
     * @property \SprayFire\Session\Storage
     */
    protected $Storage;

    public function setUp() {
        $_SESSION = array();
        $this->Storage = new FireSession\Storage();
    }

    public function tearDown() {
        $_SESSION = array();
    }

    public function testOffsetExistsWhenNoKeysSet() {
        $this->assertFalse($this->Storage->offsetExists('SprayFire'));
    }

    public function testOffsetExistsAfterKeySet() {
        $this->Storage['SprayFire'] = 'framework';
        $this->assertTrue($this->Storage->offsetExists('SprayFire'));
    }

    public function testOffsetGetWithNoSetKey() {
        $this->assertNull($this->Storage['SprayFire']);
    }

    public function testOffsetGetWithKeySet() {
        $this->Storage['foo'] = 'SprayFire';
        $this->assertSame('SprayFire', $this->Storage['foo']);
    }

    public function testOffsetUnsetAfterKeySet() {
        $this->Storage['foo'] = 'bar';
        $this->assertSame('bar', $this->Storage['foo']);
        unset($this->Storage['foo']);
        $this->assertNull($this->Storage['foo']);
    }

    public function testCountingWithNoElementsInStorage() {
        $this->assertSame(0, \count($this->Storage));
    }

    public function testCountingWithThreeElementsInStorage() {
        $this->Storage['foo'] = 0;
        $this->Storage['bar'] = 1;
        $this->Storage['foobar'] = 2;
        $this->assertSame(3, \count($this->Storage));
    }

    public function testClearSessionStorageDataAfterSet() {
        $this->Storage['foo'] = 0;
        $this->Storage['bar'] = 1;
        $this->Storage['foobar'] = 2;
        $this->assertSame(3, \count($this->Storage));
        $this->Storage->clear();
        $this->assertSame(0, \count($this->Storage));
    }

    public function testClearingSpecificKeyFromStorage() {
        $this->Storage['SprayFire'] = 'foo';
        $this->Storage['foo'] = 'bar';
        $this->assertSame('foo', $this->Storage['SprayFire']);
        $this->assertSame('bar', $this->Storage['foo']);
        $this->Storage->clearKey('SprayFire');
        $this->assertSame(null, $this->Storage['SprayFire']);
        $this->assertSame('bar', $this->Storage['foo']);
    }

    public function testIsImmutableNotSetIfNotExplicitlySet() {
        $this->assertFalse($this->Storage->isImmutable());
    }

    public function testIsImmutableAfterExplicitlySet() {
        $this->Storage->makeImmutable();
        $this->assertTrue($this->Storage->isImmutable());
    }

    public function testSettingDataAfterMakeImmutableThrowsException() {
        $this->Storage->makeImmutable();
        $this->asserTrue($this->Storage->isImmutable());
        $this->setExpectedException('\SprayFire\Session\Exception\WritingToImmutableStorage');
    }


}