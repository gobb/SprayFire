<?php

namespace SprayFire\Test\Cases\Util;

class PathsTest extends \PHPUnit_Framework_TestCase {

    public function testPathWithSubDirectoriesAsParameters() {
        $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
        $RootPaths = new \SprayFire\FileSys\RootPaths($install);
        $Directory = new \SprayFire\FileSys\Paths($RootPaths);
        $expected = $install . '/some/path/to/the/file.php';
        $actual = $Directory->getInstallPath('some', 'path', 'to', 'the', 'file.php');
        $this->assertSame($expected, $actual);
    }

    public function testPathWithSubDirectoriesAsArray() {
        $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
        $RootPaths = new \SprayFire\FileSys\RootPaths($install);
        $Directory = new \SprayFire\FileSys\Paths($RootPaths);
        $expected = $install . '/libs/SprayFire/checking/this/directory';
        $actual = $Directory->getInstallPath(array('libs', 'SprayFire', 'checking', 'this', 'directory'));
        $this->assertSame($expected, $actual);
    }

    public function testUrlPathWithSubDirectoriesAsParameters() {
        $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
        $RootPaths = new \SprayFire\FileSys\RootPaths($install);
        $Directory = new \SprayFire\FileSys\Paths($RootPaths);
        $expected = '/mockframework/web/css/main.css';
        $actual = $Directory->getUrlPath('css', 'main.css');
        $this->assertSame($expected, $actual);
    }

}