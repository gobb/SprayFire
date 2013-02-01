<?php

namespace SprayFireTest\FileSys\FireFileSys;

use \SprayFire\FileSys\FireFileSys as FireFileSys,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @package SprayFireTest
 * @subpackage FileSys.FireFileSys
 */
class PathsTest extends PHPUnitTestCase {

    public function testPathWithSubDirectoriesAsParameters() {
        $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
        $RootPaths = new FireFileSys\RootPaths($install);
        $Directory = new FireFileSys\Paths($RootPaths);
        $expected = $install . '/some/path/to/the/file.php';
        $actual = $Directory->getInstallPath('some', 'path', 'to', 'the', 'file.php');
        $this->assertSame($expected, $actual);
    }

    public function testPathWithSubDirectoriesAsArray() {
        $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
        $RootPaths = new FireFileSys\RootPaths($install);
        $Directory = new FireFileSys\Paths($RootPaths);
        $expected = $install . '/libs/SprayFire/checking/this/directory';
        $actual = $Directory->getInstallPath(array('libs', 'SprayFire', 'checking', 'this', 'directory'));
        $this->assertSame($expected, $actual);
    }

    public function testUrlPathWithSubDirectoriesAsParameters() {
        $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
        $RootPaths = new FireFileSys\RootPaths($install);
        $Directory = new FireFileSys\Paths($RootPaths);
        $expected = '/mockframework/web/css/main.css';
        $actual = $Directory->getUrlPath('css', 'main.css');
        $this->assertSame($expected, $actual);
    }

    public function testUrlPathWithVirtualHost() {
        $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
        $RootPaths = new FireFileSys\RootPaths($install);
        $Directory = new FireFileSys\Paths($RootPaths, true);
        $expected = '/web/css/main.css';
        $actual = $Directory->getUrlPath('css', 'main.css');
        $this->assertSame($expected, $actual);
    }

}
