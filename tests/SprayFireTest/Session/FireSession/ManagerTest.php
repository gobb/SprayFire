<?php

/**
 * Tests that the SprayFire implementation of the session module works managing
 * sessions.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFireTest\Session\FireSession;

use \SprayFire\Session\FireSession as FireSession,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 *
 *
 * @package SprayFireTest
 * @subpackage Session.FireSession
 */
class ManagerTest extends PHPUnitTestCase {

    public function run(\PHPUnit_Framework_TestResult $result = null) {
        $this->setPreserveGlobalState(false);
        return parent::run($result);
    }

    /**
     * Ensures that if the session has not been started yet the sessionExists
     * method returns false.
     *
     */
    public function testSessionExistsWhenSessionHasNotBeenStarted() {
        $this->markTestIncomplete('We need to get bootstrap in separate process fix in our PHPUnit install');
    }

    public function tearDown() {
        $this->setPreserveGlobalState(true);
    }

}
