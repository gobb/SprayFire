<?php

/**
 * A test to ensure the \SprayFire\Plugin\Manager implementation provied by the
 * framework takes care of all the responsibilities assigned to it.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFireTest\Plugin\FirePlugin;

use \SprayFire\Plugin\FirePlugin as FirePlugin,
    \SprayFire\Mediator as SFMediator,
    \ClassLoader\Loader as ClassLoader,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 *
 *
 * @package SprayFireTest
 * @subpackage Plugin.FirePlugin
 */
class ManagerTest extends PHPUnitTestCase {

    /**
     *
     */
    public function testGettingRegisteredPluginsBeforeAnyRegisteredReturnsEmptyArray() {
        $Loader = $this->getClassLoader();
        $Mediator = $this->getMediator();
        $Manager = $this->getManager($Loader, $Mediator);

        $this->assertSame([], $Manager->getRegisteredPlugins(), 'When no plugins registered an empty array is not returned');
    }

    /**
     * @return \ClassLoader\Loader
     */
    protected function getClassLoader() {
        return $this->getMock('\ClassLoader\Loader');
    }

    /**
     * @return \SprayFire\Mediator\Mediator
     */
    protected function getMediator() {
        return $this->getMock('\SprayFire\Mediator\Mediator');
    }

    /**
     * @param \ClassLoader\Loader $Loader
     * @param \SprayFire\Mediator\Mediator $Mediator
     * @return \SprayFire\Plugin\FirePlugin\Manager
     */
    protected function getManager(ClassLoader $Loader, SFMediator\Mediator $Mediator) {
        return new FirePlugin\Manager($Loader, $Mediator);
    }


}
