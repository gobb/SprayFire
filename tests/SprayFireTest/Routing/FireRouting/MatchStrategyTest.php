<?php

/**
 *
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFireTest\Routing\FireRouting;

use \SprayFire\Http,
    \SprayFire\Routing,
    \SprayFire\Routing\FireRouting,
    \PHPUnit_Framework_TestCase;

/**
 *
 *
 * @package SprayFireTest
 * @subpackage Http.Routing.FireRouting
 */
class MatchStrategyTest extends PHPUnit_Framework_TestCase {

    /**
     * Ensures that with no install directory passed to constructor no manipulation
     * occurs to the path.
     */
    public function testMatchStrategyReturningUnmanipulatedPath() {
        $Strategy = new MatchStrategyHelper();
        $expected = 'some/test';
        $this->assertSame($expected, $Strategy->removeDirectory('/some/test/'));
    }

    /**
     * Ensures that an install directory with no leading slash on the URI path is
     * properly removed.
     */
    public function testMatchStrategyRemovingInstallDirectoryFromPath() {
        $Strategy = new MatchStrategyHelper('install');
        $expected = 'another/test/for/this';
        $this->assertSame($expected, $Strategy->removeDirectory('install/another/test/for/this/'));
    }

    /**
     * Ensures that an install directory with a leading slash on the URI path is
     * properly removed.
     */
    public function testMatchStrategyRemovingInstallDirectoryFromPathWithSlashOnUri() {
        $Strategy = new MatchStrategyHelper('SprayFire');
        $expected = 'framework/test';
        $this->assertSame($expected, $Strategy->removeDirectory('/SprayFire/framework/test/'));
    }

    public function testMatchStrategyRemovingMultipleInstallDirectoryWithLeadingSlashOnDirectory() {
        $Strategy = new MatchStrategyHelper('/SprayFire/framework/');
        $expected = 'test';
        $this->assertSame($expected, $Strategy->removeDirectory('/SprayFire/framework/test/'));
    }

    public function testMatchRootDirectoryWithInstallDirectory() {
        $Strategy = new MatchStrategyHelper('SprayFire');
        $expected = '';
        $this->assertSame($expected, $Strategy->removeDirectory('/SprayFire'));
    }

}

class MatchStrategyHelper extends FireRouting\MatchStrategy {

    /**
     * Matches a $Request to a \SprayFire\Routing\Route stored in the $Bag
     * or otherwise creates a Route implementation to be used during routing.
     *
     * Please note that the array returned should have 2 keys:
     * - Route => The Route object matched
     * - parameters => an array of parameters to pass to the action
     *
     * @param \SprayFire\Routing\RouteBag $Bag
     * @param \SprayFire\Http\Request $Request
     * @return array
     */
    public function getRouteAndParameters(Routing\RouteBag $Bag, Http\Request $Request) {

    }

    public function removeDirectory($uriPath) {
        return $this->removeInstallDirectory($uriPath);
    }

}
