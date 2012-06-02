<?php

/**
 * @file
 * @brief
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire repository: http://www.github.com/cspray/SprayFire/
 *
 * SprayFire wiki: http://www.github.com/cspray/SprayFire/wiki/
 *
 * SprayFire API Documentation: http://www.cspray.github.com/SprayFire/
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace SprayFire\Test\Cases\Bootstrap\Strapifier;

/**
 * @brief
 */
class PathGeneratorTest extends \PHPUnit_Framework_TestCase {

    public function testPathGeneratorBootstrap() {

        $installPath = '/install';
        $libsPath = $installPath . '/libs';
        $appPath = $installPath . '/app';
        $logsPath = $installPath . '/logs';
        $configPath = $installPath . '/config';
        $webPath = $installPath . '/web';
        $paths = \compact('installPath', 'libsPath', 'appPath', 'logsPath', 'configPath', 'webPath');
        $PathGen = new \SprayFire\Bootstrap\Strapifier\PathGenerator($paths);
        $Directory = $PathGen->runBootstrap();
        $this->assertSame($installPath, $Directory->getInstallPath());
        $this->assertSame($libsPath, $Directory->getLibsPath());
        $this->assertSame($appPath, $Directory->getAppPath());
        $this->assertSame($configPath, $Directory->getConfigPath());
        $this->assertSame($webPath, $Directory->getWebPath());
        $this->assertSame('/install/web', $Directory->getUrlPath());
    }

}