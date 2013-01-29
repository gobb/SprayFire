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
namespace SprayFire\Test\Cases\Session\FireSession;

use \SprayFire\Session\FireSession as FireSession,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 *
 *
 * @package SprayFireTest
 * @subpackage Cases.Session.FireSession
 */
class ManagerTest extends PHPUnitTestCase {

    public function run(\PHPUnit_Framework_TestResult $result = null) {
        $runtimeArgs = $_SERVER['argv'];
        $config = '';
        foreach($runtimeArgs as $arg) {
            if (\preg_match('/configuration\.xml$/', $arg)) {
                $config = $arg;
                break;
            }
        }
        $phpUnitConfig = \PHPUnit_Util_Configuration::getInstance($config)->getPHPUnitConfiguration();
        require_once $phpUnitConfig['bootstrap'];
        $this->setPreserveGlobalState(false);
        return parent::run($result);
    }

    /**
     * Ensures that if the session has not been started yet the sessionExists
     * method returns false.
     *
     * @runInSeparateProcess
     */
    public function testSessionExistsWhenSessionHasNotBeenStarted() {
        $Manager = new FireSession\Manager();
        $this->assertSame(false, $Manager->sessionExists());
    }

    public function tearDown() {
        $this->setPreserveGlobalState(true);
    }

}
