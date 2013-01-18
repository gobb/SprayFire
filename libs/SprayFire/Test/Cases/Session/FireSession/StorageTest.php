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
 * @package SprayFire
 * @subpackage
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

}
