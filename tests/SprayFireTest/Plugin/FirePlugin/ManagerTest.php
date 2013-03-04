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
        $Initializer = $this->getPluginInitializer($Loader);
        $Manager = $this->getManager($Initializer, $Loader);

        $this->assertSame([], $Manager->getRegisteredPlugins(), 'When no plugins registered an empty array is not returned');
    }

    /**
     * Ensures that after a plugin is registered it is returned appropriately in
     * the collection of registered plugins.
     */
    public function testGettingRegisteredPluginsAfterSomeRegisteredReturnsRightCollection() {
        $Loader = $this->getClassLoader();
        $Initializer = $this->getPluginInitializer($Loader);
        $Manager = $this->getManager($Initializer, $Loader);

        $Foo = new FirePlugin\PluginSignature('foo', '/');
        $Bar = new FirePlugin\PluginSignature('bar', '/');
        $Baz = new FirePlugin\PluginSignature('baz', '/');

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
        $Initializer = $this->getPluginInitializer($Loader);
        $Manager = $this->getManager($Initializer, $Loader);

        $SprayFire = new FirePlugin\PluginSignature('SprayFire', '/');

        $Manager->registerPlugin($SprayFire);
    }

    /**
     * Ensures that we can register multiple plugins in an array.
     */
    public function testRegisteringMultiplePluginsPassedAsArray() {
        $Loader = $this->getClassLoader();
        $Initializer = $this->getPluginInitializer($Loader);
        $Manager = $this->getManager($Initializer, $Loader);

        $Foo = new FirePlugin\PluginSignature('foo', '/');
        $Bar = new FirePlugin\PluginSignature('bar', '/');
        $Baz = new FirePlugin\PluginSignature('baz', '/');

        $expected = [$Foo, $Bar, $Baz];

        $Manager->registerPlugins($expected);

        // we're only checking if in Manager::getRegisteredPlugins to reduce
        // testing code needed. we assume that if you add it to the collection
        // returned from this method that you actually, well, registered it.
        $this->assertCount(3, $Manager->getRegisteredPlugins());
    }

    /**
     * Ensures that we are initializing when the plugin has been set to initialize.
     */
    public function testManagerIsInitializingWhenSetToTrue() {
        $Loader = $this->getClassLoader();
        $Initializer = $this->getPluginInitializer($Loader);
        $Manager = $this->getManager($Initializer, $Loader);

        $DoInit = new FirePlugin\PluginSignature('foo', '/', true);
        $DontDoInit = new FirePlugin\PluginSignature('bar', '/', false);

        $Initializer->expects($this->once())
                    ->method('initializePlugin')
                    ->with('foo');

        $Manager->registerPlugin($DoInit)->registerPlugin($DontDoInit);
    }

    /**
     * Ensures that we are initializing when the plugin has been set to initialize.
     */
    public function testManagerIsInitializingWhenSetToFalse() {
        $Loader = $this->getClassLoader();
        $Initializer = $this->getPluginInitializer($Loader);
        $Manager = $this->getManager($Initializer, $Loader);

        $DoInit = new FirePlugin\PluginSignature('foo', '/', false);
        $DontDoInit = new FirePlugin\PluginSignature('bar', '/', false);

        $Initializer->expects($this->never())
            ->method('initializePlugin');

        $Manager->registerPlugin($DoInit)->registerPlugin($DontDoInit);
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
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getPluginInitializer(ClassLoader $Loader) {
        return $this->getMock(
            '\SprayFire\Plugin\FirePlugin\PluginInitializer',
            ['initializePlugin'],
            [],
            '',
            false
        );

    }

    /**
     * @param \SprayFire\Plugin\FirePlugin\PluginInitializer
     * @param \ClassLoader\Loader $Loader
     * @return \SprayFire\Plugin\FirePlugin\Manager
     */
    protected function getManager(FirePlugin\PluginInitializer $Initializer, ClassLoader $Loader) {
        return new FirePlugin\Manager($Initializer, $Loader);
    }

}
