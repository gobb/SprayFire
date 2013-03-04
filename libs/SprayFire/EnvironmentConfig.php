<?php

/**
 * Object representing SprayFire's environment configuration.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */
namespace SprayFire;

use \SprayFire\Dispatcher as SFDispatcher,
    \SprayFire\StdLib as SFStdLib;

/**
 * Object representing SprayFire's environment configuration and controls how
 * the framework operates at runtime.
 *
 * @package SprayFire
 */
class EnvironmentConfig extends SFStdLib\CoreObject {

    /**
     * The current version of the framework.
     */
    const VERSION = '0.1.0alpha';

    /**
     * Default environment config if none are provided at time of construction.
     *
     * @property array
     */
    protected $defaultOptions = [
        'developmentMode' => false,
        'defaultCharset' => 'UTF-8',
        'registeredEvents' => [
            \SprayFire\Events::AFTER_CONTROLLER_INVOKED => '',
            \SprayFire\Events::AFTER_RESPONSE_SENT => '',
            \SprayFire\Events::BEFORE_CONTROLLER_INVOKED => '',
            \SprayFire\Events::BEFORE_RESPONSE_SENT => ''
        ],
        'virtualHost' => true
    ];

    /**
     * The actual options used to return appropriate configuration values
     *
     * @property array
     */
    protected $options;

    /**
     * If no $options are passed the $defaultOptions are used; please see chart
     * in details for more information on the keys available in options.
     *
     * Options:
     *
     * Key                | Value
     * -----------------------------------------------
     * developmentMode    | boolean (default: true)
     * -----------------------------------------------
     * defaultCharset     | string (default: utf-8)
     * -----------------------------------------------
     * registeredEvents   | array (default: constants in Dispatcher\Events)
     * -----------------------------------------------
     * virtualHost        | boolean (default: true)
     * -----------------------------------------------
     *
     * @param array $options
     */
    public function __construct(array $options = []) {
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
