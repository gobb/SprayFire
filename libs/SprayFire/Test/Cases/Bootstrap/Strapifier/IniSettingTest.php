<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of IniConfigBootstrapTest
 */

namespace SprayFire\Test\Cases\Bootstrap\Strapifier;

class IniConfigTest extends \PHPUnit_Framework_TestCase {

    protected $defaultGlobalIniSettings = array();


    public function setUp() {
        $this->setGlobalIniSettings();
    }

    protected function setGlobalIniSettings() {
        $dateTimezone = \ini_get('date.timezone');
        $defaultCharset = \ini_get('default_charset');
        $defaultMimetype = \ini_get('default_mimetype');
        $assertActive = \ini_get('assert.active');
        $exposePhp = \ini_get('expose_php');

        $this->defaultGlobalIniSettings['date.timezone'] = $dateTimezone;
        $this->defaultGlobalIniSettings['default_charset'] = $defaultCharset;
        $this->defaultGlobalIniSettings['default_mimetype'] = $defaultMimetype;
        $this->defaultGlobalIniSettings['assert.active'] = $assertActive;
        $this->defaultGlobalIniSettings['expose_php'] = $exposePhp;
    }

    public function testIniConfigBootstrap() {
        $arrayConfig = array();
        $arrayConfig['date.timezone'] = 'America/Los_Angeles';
        $arrayConfig['default_charset'] = 'UTF-8';
        $arrayConfig['default_mimetype'] = 'text/plain';
        $arrayConfig['assert.active'] = 1;
        $arrayConfig['expose_php'] = 1;

        $Bootstrap = new \SprayFire\Bootstrap\Strapifier\IniSetting($arrayConfig);
        $Bootstrap->runBootstrap();

        $dateTimezone = \ini_get('date.timezone');
        $defaultCharset = \ini_get('default_charset');
        $defaultMimetype = \ini_get('default_mimetype');
        $assertActive = \ini_get('assert.active');
        $exposePhp = \ini_get('expose_php');

        $this->assertSame('America/Los_Angeles', $dateTimezone, 'date.timezone was not set properly');
        $this->assertSame('UTF-8', $defaultCharset, 'default_charset was not set properly');
        $this->assertSame('text/plain', $defaultMimetype, 'default_mimetype was not set properly');
        $this->assertSame('1', $assertActive, 'assert.active was not set properly');
        $this->assertSame('1', $exposePhp, 'expose_php was not set properly');

    }

    public function tearDown() {
        foreach ($this->defaultGlobalIniSettings as $key => $value) {
            \ini_set($key, $value);
        }
    }



}
