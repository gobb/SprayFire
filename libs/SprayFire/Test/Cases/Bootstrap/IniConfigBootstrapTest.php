<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of IniConfigBootstrapTest
 */

namespace SprayFire\Test\Cases\Bootstrap;

class IniConfigBootstrapTest extends \PHPUnit_Framework_TestCase {

    protected $defaultGlobalIniSettings = array();


    public function setUp() {
        $this->setGlobalIniSettings();
    }

    protected function setGlobalIniSettings() {
        $allowUrlFopen = \ini_get('allow_url_fopen');
        $allowUrlInclude = \ini_get('allow_url_include');
        $aspTags = \ini_get('asp_tags');
        $dateTimezone = \ini_get('date.timezone');
        $defaultCharset = \ini_get('default_charset');
        $defaultMimetype = \ini_get('default_mimetype');
        $assertActive = \ini_get('assert.active');
        $magicQuotesGpc = \ini_get('magic_quotes_gpc');
        $magicQuotesRuntime = \ini_get('magic_quotes_runtime');
        $exposePhp = \ini_get('expose_php');

        $this->defaultGlobalIniSettings['allow_url_fopen'] = $allowUrlFopen;
        $this->defaultGlobalIniSettings['allow_url_include'] = $allowUrlInclude;
        $this->defaultGlobalIniSettings['asp_tags'] = $aspTags;
        $this->defaultGlobalIniSettings['date.timezone'] = $dateTimezone;
        $this->defaultGlobalIniSettings['default_charset'] = $defaultCharset;
        $this->defaultGlobalIniSettings['default_mimetype'] = $defaultMimetype;
        $this->defaultGlobalIniSettings['assert.active'] = $assertActive;
        $this->defaultGlobalIniSettings['magic_quotes_gpc'] = $magicQuotesGpc;
        $this->defaultGlobalIniSettings['magic_quotes_runtime'] = $magicQuotesRuntime;
        $this->defaultGlobalIniSettings['expose_php'] = $exposePhp;
    }

    public function testIniConfigBootstrapGlobalIniSettingsOnly() {
        $arrayConfig = array();
        $arrayConfig['developmentMode'] = false;
        $arrayConfig['globalIniSettings'] = array();
        $arrayConfig['globalIniSettings']['allow_url_fopen'] = 1;
        $arrayConfig['globalIniSettings']['allow_url_include'] = 1;
        $arrayConfig['globalIniSettings']['asp_tags'] = 0;
        $arrayConfig['globalIniSettings']['date.timezone'] = 'America/Los_Angeles';
        $arrayConfig['globalIniSettings']['default_charset'] = '';
        $arrayConfig['globalIniSettings']['default_mimetype'] = 'text/plain';
        $arrayConfig['globalIniSettings']['assert.active'] = 1;
        $arrayConfig['magic_quotes_gpc'] = 0;
        $arrayConfig['magic_quotes_runtime'] = 0;
        $arrayConfig['expose_php'] = 1;
        $Config = new \SprayFire\Config\ArrayConfig($arrayConfig);
        $Bootstrap = new \SprayFire\Bootstrap\IniConfigBootstrap($Config);
        $trueOrFalse = $Bootstrap->runBootstrap();
        $this->assertTrue($trueOrFalse);
    }

    public function tearDown() {
        foreach ($this->defaultGlobalIniSettings as $key => $value) {
            \ini_set($key, $value);
        }
    }



}
