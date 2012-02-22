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

    public function testReadingBasicJsonConfigObject() {
        $filePath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/config/json/test-config.json';
        $File = new \SplFileInfo($filePath);
        $Config = new \SprayFire\Config\JsonConfig($File);

        $this->assertNull($Config->{'no-exist'});
        $this->assertFalse(isset($Config->{'no-exist'}));
        $this->assertTrue(isset($Config->app->version));

        $expectedFrameworkVersion = '0.1.0-gold-rc';
        $actualFrameworkVersion = $Config->framework['version'];
        $this->assertSame($expectedFrameworkVersion, $actualFrameworkVersion);

        $expectedAppVersion = '0.0.1-beta';
        $actualAppVersion = $Config->app->version;
        $this->assertSame($expectedAppVersion, $actualAppVersion);

        $expectedToString = 'SprayFire\Config\JsonConfig::' . \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/config/json/test-config.json';
        $actualToString = $Config->__toString();
        $this->assertSame($expectedToString, $actualToString);
    }

    public function testWritingToJsonConfigObject() {
        $filePath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/config/json/test-config.json';
        $File = new \SplFileInfo($filePath);
        $Config = new \SprayFire\Config\JsonConfig($File);
        $exceptionThrown = false;
        try {
            $Config->app->version = "something I choose...NOT";
        } catch (\SprayFire\Exception\UnsupportedOperationException $Exception) {
            $this->assertSame("Attempting to set the value of an immutable object.", $Exception->getMessage());
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    public function testNonExistentFile() {
        $exceptionThrown = false;
        try {
            $File = new \SplFileInfo('this/file/path/does/not/exist/config.json');
            $Config = new \SprayFire\Config\JsonConfig($File);
        } catch (\InvalidArgumentException $Exception) {
            $this->assertSame("There is an error with the path to the configuration file, it does not appear to be a valid file or symlink.", $Exception->getMessage());
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    public function testInvalidJsonFile() {
        $exceptionThrown = false;
        try {
            $filePath = \SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/libs/SprayFire/Config/json/test-invalid-config.json';
            $File = new \SplFileInfo($filePath);
            $Config = new \SprayFire\Config\JsonConfig($File);
        } catch (\InvalidArgumentException $Exception) {
            $exceptionThrown = true;
            $this->assertSame("There was an error parsing the JSON configuration file passed.  JSON_error_code := 4", $Exception->getMessage());
        }
        $this->assertTrue($exceptionThrown);
    }

    public function testCrappyExtension() {
        $exceptionThrown = false;
        try {
            $File = new \SplFileInfo(\SPRAYFIRE_ROOT . '/libs/SprayFire/Test/mockframework/app/TestApp/config/json/test-config.json');
            $Config = new \SprayFire\Test\Helpers\CrappyJsonConfig($File);
        } catch (\UnexpectedValueException $Exception) {
            $exceptionThrown = true;
            $this->assertSame("The data returned from convertDataDeep must be an array.", $Exception->getMessage());
        }
        $this->assertTrue($exceptionThrown);
    }

}
// End JsonConfigTest
