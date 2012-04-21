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

    public function testIniConfigBootstrap() {
        $arrayConfig = array();
        $arrayConfig['allow_url_fopen'] = 1;
        $arrayConfig['allow_url_include'] = 1;
        $arrayConfig['asp_tags'] = 0;
        $arrayConfig['date.timezone'] = 'America/Los_Angeles';
        $arrayConfig['default_charset'] = 'UTF-8';
        $arrayConfig['default_mimetype'] = 'text/plain';
        $arrayConfig['assert.active'] = 1;
        $arrayConfig['magic_quotes_gpc'] = 0;
        $arrayConfig['magic_quotes_runtime'] = 0;
        $arrayConfig['expose_php'] = 1;

        $Bootstrap = new \SprayFire\Bootstrap\IniConfigBootstrap($arrayConfig);
        $Bootstrap->runBootstrap();

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

        $this->assertSame(1, $allowUrlFopen, 'allow_url_fopen was not set properly');
        $this->assertSame(1, $allowUrlInclude, 'allow_url_include was not set properly');
        $this->assertSame(0, $aspTags, 'asp_tags was not set properly');
        $this->assertSame('America/Los_Angeles', $dateTimezone, 'date.timezone was not set properly');
        $this->assertSame('UTF-8', $defaultCharset, 'default_charset was not set properly');
        $this->assertSame('text/plain', $defaultMimetype, 'default_mimetype was not set properly');
        $this->assertSame(1, $assertActive, 'assert.active was not set properly');
        $this->assertSame(0, $magicQuotesGpc, 'magic_quotes_gpc was not set properly');
        $this->assertSame(0, $magicQuotesRuntime, 'magic_quotes_runtime was not set properly');
        $this->assertSame(1, $exposePhp, 'expose_php was not set properly');

    }

    public function tearDown() {
        foreach ($this->defaultGlobalIniSettings as $key => $value) {
            \ini_set($key, $value);
        }
    }



}
