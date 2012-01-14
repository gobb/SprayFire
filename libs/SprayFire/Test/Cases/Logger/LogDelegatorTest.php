<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Logger;

/**
 * @brief
 */
class LogDelegatorTest extends \PHPUnit_Framework_TestCase {

    public function testLogDelegator() {
        $LogObject = 'SprayFire.Test.Helpers.TestDelegatorLogger';
        $emergencyOptions = array('emergency');
        $errorOptions = array('error');
        $debugOptions = array('debug');
        $infoOptions = array('info');

        $Factory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($Factory);
        $LogDelegator->setEmergencyLogger($LogObject, $emergencyOptions);
        $LogDelegator->setErrorLogger($LogObject, $errorOptions);
        $LogDelegator->setDebugLogger($LogObject, $debugOptions);
        $LogDelegator->setInfoLogger($LogObject, $infoOptions);

        $ReflectedDelegator = new \ReflectionObject($LogDelegator);
        $EmergencyLogProperty = $ReflectedDelegator->getProperty('EmergencyLogger');
        $EmergencyLogProperty->setAccessible(true);
        $EmergencyLogger = $EmergencyLogProperty->getValue($LogDelegator);
        $this->assertTrue($EmergencyLogger instanceof \SprayFire\Test\Helpers\TestDelegatorLogger);
    }


}