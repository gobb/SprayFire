<?php

/**
 * SprayFire is a custom built framework intended to ease the development
 * of websites with PHP 5.3.
 *
 * SprayFire makes use of namespaces, a custom-built ORM layer, a completely
 * object oriented approach and minimal invasiveness so you can make the framework
 * do what YOU want to do.  Some things we take seriously over here at SprayFire
 * includes clean, readable source, completely unit tested implementations and
 * not polluting the global scope.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 *
 * @author Charles Sprayberry <cspray at gmail dot com>
 * @license OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace SprayFire\Test\Cases\Config;

/**
 *
 */
class JsonConfigTest extends \PHPUnit_Framework_TestCase {

    public function testJsonConfigObject() {
        $filePath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/config/json/test-config.json';
        $File = new \SplFileInfo($filePath);
        $Config = new \SprayFire\Config\JsonConfig($File);

        $expectedNoExist = null;
        $actualNoExist = $Config->{'no-exist'};
        $this->assertSame($expectedNoExist, $actualNoExist);

        $noExistIsSet = isset($Config['no-exist']);
        $this->assertFalse($noExistIsSet);

        $doesExistIsSet = isset($Config->app->version);
        $this->assertTrue($doesExistIsSet);

        $expectedFrameworkVersion = '0.1.0-gold-rc';
        $actualFrameworkVersion = $Config->framework['version'];
        $this->assertSame($expectedFrameworkVersion, $actualFrameworkVersion);

        $exceptionThrownWhenSet = false;
        try {
            $Config->app->version = 'some new version';
        } catch (\SprayFire\Exception\UnsupportedOperationException $UnsupportedOp) {
            $exceptionThrownWhenSet = true;
        }

        $this->assertTrue($exceptionThrownWhenSet);

        $exceptionThrownWhenUnset = false;
        try {
            unset($Config->app->version);
        } catch (\SprayFire\Exception\UnsupportedOperationException $UnsupportedOp) {
            $exceptionThrownWhenUnset = true;
        }

        $this->assertTrue($exceptionThrownWhenUnset);

        $expectedAppVersion = '0.0.1-beta';
        $actualAppVersion = $Config->app->version;
        $this->assertSame($expectedAppVersion, $actualAppVersion);

        $expectedToString = 'SprayFire\Config\JsonConfig::' . \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/config/json/test-config.json';
        $actualToString = $Config->__toString();
        $this->assertSame($expectedToString, $actualToString);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNonExistentFile() {
        $File = new \SplFileInfo('this/file/path/does/not/exist/config.json');
        $Config = new \SprayFire\Config\JsonConfig($File);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidJsonFile() {
        $filePath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/libs/SprayFire/Config/json/test-invalid-config.json';
        $File = new \SplFileInfo($filePath);
        $Config = new \SprayFire\Config\JsonConfig($File);
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testCrappyExtension() {
        $File = new \SplFileInfo(\SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/config/json/test-config.json');
        $Config = new \SprayFire\Test\Helpers\CrappyJsonConfig($File);
    }

}

// End JsonConfigTest
