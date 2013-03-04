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
     * Ensures that we get back an empty array from Manager::getRegisteredPlugins()
     * if no plugins have been registered.
     */
    public function testGettingRegisteredPluginsBeforeAnyRegisteredReturnsEmptyArray() {
        $Loader = $this->getClassLoader();
        $Mediator = $this->getMediator();
        $Manager = $this->getManager($Loader, $Mediator);

        $this->assertSame([], $Manager->getRegisteredPlugins(), 'When no plugins registered an empty array is not returned');
    }

    /**
     * Ensures that after a plugin is registered it is returned appropriately in
     * the collection of registered plugins.
     */
    public function testGettingRegisteredPluginsAfterSomeRegisteredReturnsRightCollection() {
        $Loader = $this->getClassLoader();
        $Mediator = $this->getMediator();
        $Manager = $this->getManager($Loader, $Mediator);

        $noCallbacks = function() { return []; };

        $Foo = new FirePlugin\PluginSignature('foo', '/', $noCallbacks);
        $Bar = new FirePlugin\PluginSignature('bar', '/', $noCallbacks);
        $Baz = new FirePlugin\PluginSignature('baz', '/', $noCallbacks);

        $Manager->registerPlugin($Foo)->registerPlugin($Bar)->registerPlugin($Baz);

        $this->assertSame([$Foo, $Bar, $Baz], $Manager->getRegisteredPlugins());
    }

    /**
     * Ensures that if a plugin is registered the name of the plugin and directory
     * for that plugin are properly registered with the framework's autoloading
     * solution.
     */
    public function testRegisteringPluginCausesNameAndDirToBeAddedToClassLoader() {
        $Loader = $this->getClassLoader();
        $Loader->expects($this->once())
               ->method('registerNamespaceDirectory')
               ->with('SprayFire', '/');
        $Mediator = $this->getMediator();
        $Manager = new FirePlugin\Manager($Loader, $Mediator);

        $SprayFire = new FirePlugin\PluginSignature('SprayFire', '/', function() { return []; });

        $Manager->registerPlugin($SprayFire);
    }

    /**
     * Ensures that plugins can't be registered attempting to add Callbacks that
     * aren't actually callbacks.
     */
    public function testRegisteringPluginWithInvalidCallbacksThrowsException() {
        $Loader = $this->getClassLoader();
        $Mediator = $this->getMediator();
        $Manager = new FirePlugin\Manager($Loader, $Mediator);

        $SprayFire = new FirePlugin\PluginSignature('SprayFire', '/', function() { return [new \stdClass()]; });

        $this->setExpectedException('\InvalidArgumentException');

        $Manager->registerPlugin($SprayFire);
    }

    /**
     * Ensures that plugins returning valid callbacks are properly added to
     * Mediator.
     */
    public function testRegisterValidPluginCallbacksActuallyCallsMediator() {
        $Loader = $this->getClassLoader();
        $Mediator = $this->getMediator();
        $Manager = new FirePlugin\Manager($Loader, $Mediator);

        $fooCallback = $this->getMock('\SprayFire\Mediator\Callback');
        $barCallback = $this->getMock('\SprayFire\Mediator\Callback');
        $bazCallback = $this->getMock('\SprayFire\Mediator\Callback');

        $Foo = new FirePlugin\PluginSignature('foo', '/', function() use($fooCallback) { return [$fooCallback]; });
        $Bar = new FirePlugin\PluginSignature('bar', '/', function() use($barCallback) { return [$barCallback]; });
        $Baz = new FirePlugin\PluginSignature('baz', '/', function() use($bazCallback) { return [$bazCallback]; });

        $Mediator->expects($this->at(0))
                 ->method('addCallback')
                 ->with($fooCallback);
        $Mediator->expects($this->at(1))
                 ->method('addCallback')
                 ->with($barCallback);
        $Mediator->expects($this->at(2))
                 ->method('addCallback')
                 ->with($bazCallback);

        $Manager->registerPlugin($Foo)->registerPlugin($Bar)->registerPlugin($Baz);
    }

    public function testRegisteringMultiplePluginsPassedAsArray() {
        $Loader = $this->getClassLoader();
        $Mediator = $this->getMediator();
        $Manager = $this->getManager($Loader, $Mediator);

        $noCallbacks = function() { return []; };

        $Foo = new FirePlugin\PluginSignature('foo', '/', $noCallbacks);
        $Bar = new FirePlugin\PluginSignature('bar', '/', $noCallbacks);
        $Baz = new FirePlugin\PluginSignature('baz', '/', $noCallbacks);

        $expected = [$Foo, $Bar, $Baz];

        $Manager->registerPlugins($expected);
        $this->assertCount(3, $Manager->getRegisteredPlugins());
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getClassLoader() {
        return $this->getMock('\ClassLoader\Loader');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
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
