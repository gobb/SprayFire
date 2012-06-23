<?php

namespace SprayFire\Test\Cases\Util;

class PathsTest extends \PHPUnit_Framework_TestCase {

    public function testPathWithSubDirectoriesAsParameters() {
        $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
        $paths = \compact('install');
        $Directory = new \SprayFire\FileSys\Paths($paths);
        $expected = $install . '/some/path/to/the/file.php';
        $actual = $Directory->getInstallPath('some', 'path', 'to', 'the', 'file.php');
        $this->assertSame($expected, $actual);
    }

    public function testPathWithSubDirectoriesAsArray() {
        $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
        $paths = \compact('install');
        $Directory = new \SprayFire\FileSys\Paths($paths);
        $expected = $install . '/libs/SprayFire/checking/this/directory';
        $actual = $Directory->getInstallPath(array('libs', 'SprayFire', 'checking', 'this', 'directory'));
        $this->assertSame($expected, $actual);
    }

    public function testUrlPathWithSubDirectoriesAsParameters() {
        $install = \SPRAYFIRE_ROOT . '/tests/mockframework';
        $web = $install . '/web';
        $paths = \compact('install', 'web');
        $Directory = new \SprayFire\FileSys\Paths($paths);
        $expected = '/mockframework/web/css/main.css';
        $actual = $Directory->getUrlPath('css', 'main.css');
        $this->assertSame($expected, $actual);
    }

}