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

        $LogDelegator->logEmergency('This is an emergency message.');
        $LogDelegator->logError('This is an error message.');
        $LogDelegator->logDebug('This is a debug message.');
        $LogDelegator->logInfo('This is an info message.');


        $ReflectedDelegator = new \ReflectionObject($LogDelegator);
        $EmergencyLogProperty = $ReflectedDelegator->getProperty('EmergencyLogger');
        $EmergencyLogProperty->setAccessible(true);
        $EmergencyLogger = $EmergencyLogProperty->getValue($LogDelegator);
        $this->assertTrue($EmergencyLogger instanceof \SprayFire\Test\Helpers\TestDelegatorLogger);
        $emergencyLoggerVal = $EmergencyLogger->getOptions();
        $this->assertSame('emergency', $emergencyLoggerVal);
        $expectedEmergencyMessages = array();
        $expectedEmergencyMessages[0] = array();
        $expectedEmergencyMessages[0]['message'] = 'This is an emergency message.';
        $expectedEmergencyMessages[0]['options'] = array();
        $this->assertSame($expectedEmergencyMessages, $EmergencyLogger->getLoggedMessages());

        $ErrorLogProperty = $ReflectedDelegator->getProperty('ErrorLogger');
        $ErrorLogProperty->setAccessible(true);
        $ErrorLogger = $ErrorLogProperty->getValue($LogDelegator);
        $this->assertTrue($ErrorLogger instanceof \SprayFire\Test\Helpers\TestDelegatorLogger);
        $errorLoggerVal = $ErrorLogger->getOptions();
        $this->assertSame('error', $errorLoggerVal);
        $expectedErrorMessages = array();
        $expectedErrorMessages[0] = array();
        $expectedErrorMessages[0]['message'] = 'This is an error message.';
        $expectedErrorMessages[0]['options'] = array();
        $this->assertSame($expectedErrorMessages, $ErrorLogger->getLoggedMessages());

        $InfoLogProperty = $ReflectedDelegator->getProperty('InfoLogger');
        $InfoLogProperty->setAccessible(true);
        $InfoLogger = $InfoLogProperty->getValue($LogDelegator);
        $this->assertTrue($InfoLogger instanceof \SprayFire\Test\Helpers\TestDelegatorLogger);
        $infoLoggerVal = $InfoLogger->getOptions();
        $this->assertSame('info', $infoLoggerVal);
        $expectedInfoMessages = array();
        $expectedInfoMessages[0] = array();
        $expectedInfoMessages[0]['message'] = 'This is an info message.';
        $expectedInfoMessages[0]['options'] = array();
        $this->assertSame($expectedInfoMessages, $InfoLogger->getLoggedMessages());

        $DebugLogProperty = $ReflectedDelegator->getProperty('DebugLogger');
        $DebugLogProperty->setAccessible(true);
        $DebugLogger = $DebugLogProperty->getValue($LogDelegator);
        $this->assertTrue($DebugLogger instanceof \SprayFire\Test\Helpers\TestDelegatorLogger);
        $debugLoggerVal = $DebugLogger->getOptions();
        $this->assertSame('debug', $debugLoggerVal);
        $expectedDebugMessages = array();
        $expectedDebugMessages[0] = array();
        $expectedDebugMessages[0]['message'] = 'This is a debug message.';
        $expectedDebugMessages[0]['options'] = array();
        $this->assertSame($expectedDebugMessages, $DebugLogger->getLoggedMessages());
    }


}