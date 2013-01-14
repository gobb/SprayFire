<?php

/**
 * Object representing SprayFire's environment configuration.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire;

use \SprayFire\Dispatcher as SFDispatcher,
    \SprayFire\CoreObject as SFCoreObject;

/**
 * @package SprayFire
 */
class EnvironmentConfig extends SFCoreObject {

    /**
     * The current version of the framework.
     */
    const VERSION = '0.1.0alpha';

    /**
     * Default environment config if none are provided at time of construction.
     *
     * @property array
     */
    protected $defaultOptions = array(
        'developmentMode' => false,
        'defaultCharset' => 'UTF-8',
        'registeredEvents' => array(
            SFDispatcher\Events::AFTER_CONTROLLER_INVOKED => '',
            SFDispatcher\Events::AFTER_RESPONSE_SENT => '',
            SFDispatcher\Events::AFTER_ROUTING => '',
            SFDispatcher\Events::BEFORE_CONTROLLER_INVOKED => '',
            SFDispatcher\Events::BEFORE_RESPONSE_SENT => '',
            SFDispatcher\Events::BEFORE_ROUTING => ''
        ),
        'virtualHost' => true
    );

    /**
     * The actual options used to return appropriate configuration values
     *
     * @property array
     */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct(array $options = array()) {
        $this->options = \array_merge($this->defaultOptions, $options);
    }

    /**
     * @return string
     */
    public function getDefaultCharset() {
        return (string) $this->options['defaultCharset'];
    }

    /**
     * @return array
     */
    public function getRegisteredEvents() {
        return (array) $this->options['registeredEvents'];
    }

    /**
     * @return boolean
     */
    public function isDevelopmentMode() {
        return (boolean) $this->options['developmentMode'];
    }

    /**
     * @return boolean
     */
    public function useVirtualHost() {
        return (boolean) $this->options['virtualHost'];
    }

}
