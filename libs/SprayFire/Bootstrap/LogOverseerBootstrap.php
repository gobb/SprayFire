<?php

/**
 * @file
 * @brief Holds a SprayFire.Bootstrap.Bootstrapper that will create a
 * SprayFire.Logging.Logifier.LogDelegator according to a configuration object
 * passed.
 */

namespace SprayFire\Bootstrap;

/**
 * @uses SprayFire.Bootstrap.Bootstrapper
 * @uses SprayFire.Config.Configuration
 * @uses SprayFire.Factory.Factory
 * @uses SprayFire.Logging.Logifier.LogDelegator
 */
class LogOverseerBootstrap extends \SprayFire\Util\CoreObject implements \SprayFire\Bootstrap\Bootstrapper {

    /**
     * @brief Should be an instance of SprayFire.Logging.Logifier.LoggerFactory
     *
     * @property $Factory
     */
    protected $Factory;

    /**
     * @brief An array of data holding the information needed to create logger
     * objects
     *
     * @property $config
     */
    protected $config;

    /**
     * @brief A SprayFire.Logging.Logifier.LogDelegator used to handle logging
     * messages.
     *
     * @property $LogDelegator
     */
    protected $LogDelegator;

    /**
     * @brief Will create a series of loggers needed for a SprayFire.Log.LogOverseer
     * implementation and return an implementation when runBootstrap() is invoked.
     *
     * @details
     *
     *
     * @param $LoggerFactory \SprayFire\Factory\Factory
     * @param $config array
     */
    public function __construct(\SprayFire\Factory\Factory $LoggerFactory, array $config) {
        $this->config = $config;
        $this->Factory = $LoggerFactory;
    }

    /**
     * @brief Will set the appropriate SprayFire.Logging.Logger for a LogOverseer
     * implementation and return that object.
     *
     * @return SprayFire.Logging.Logifier.LogDelegator
     */
    public function runBootstrap() {
        $this->createLogDelegator();
        $this->setEmergencyLogger();
        $this->setErrorLogger();
        $this->setDebugLogger();
        $this->setInfoLogger();
        return $this->LogDelegator;
    }

    protected function createLogDelegator() {
        if (!isset($this->LogDelegator)) {
            $this->LogDelegator = new \SprayFire\Logging\Logifier\LogDelegator($this->Factory);
        }
    }

    protected function setEmergencyLogger() {
        $config = isset($this->config['emergency']) ? $this->config['emergency'] : array();
        $object = $this->getObjectName($config);
        $blueprint = $this->getObjectBlueprint($config);
        $this->LogDelegator->setEmergencyLogger($object, $blueprint);
    }

    protected function setErrorLogger() {
        $config = isset($this->config['error']) ? $this->config['error'] : array();
        $object = $this->getObjectName($config);
        $blueprint = $this->getObjectBlueprint($config);
        $this->LogDelegator->setErrorLogger($object, $blueprint);
    }

    protected function setDebugLogger() {
        $config = isset($this->config['debug']) ? $this->config['debug'] : array();
        $object = $this->getObjectName($config);
        $blueprint = $this->getObjectBlueprint($config);
        $this->LogDelegator->setDebugLogger($object, $blueprint);
    }

    protected function setInfoLogger() {
        $config = isset($this->config['info']) ? $this->config['info'] : array();
        $object = $this->getObjectName($config);
        $blueprint = $this->getObjectBlueprint($config);
        $this->LogDelegator->setInfoLogger($object, $blueprint);
    }

    /**
     * @param $configSection A section of \a $Config that holds an 'object' key
     * @return mixed Object string if given or null
     */
    protected function getObjectName($configSection) {
        if (isset($configSection['object'])) {
            return $configSection['object'];
        }
        return null;
    }

    /**
     * @brief This method guarantees that an array will be returned.
     *
     * @details
     * If the blueprint stored in the configuration is an object it will be iterated
     * over and turned into an array, if the blueprint stored is not an array or
     * an object it will be cast into an array and finally if the blueprint is an
     * array it will be returned unchanged.
     *
     * @param $configSection A section of \a $Config that holds a 'blueprint' key
     * @return array
     */
    protected function getObjectBlueprint($configSection) {
        if (isset($configSection['blueprint'])) {
            $blueprint = $configSection['blueprint'];

            // this is here to ensure that chainable Configuration objects are
            // accounted for in a way to pass arguments to ReflectionClass::newInstanceArgs()
            if (\is_object($blueprint)) {
                return $this->convertObjectToArray($blueprint);
            }
            if (!\is_array($blueprint)) {
                return (array) $blueprint;
            }
            return $configSection['blueprint'];
        }
        return array();
    }

    /**
     * @brief We are doing this because SprayFire.Structure.Storage.DataStorage
     * may not be cast into arrays but they may be iterated over and manually
     * turned into an array.
     *
     * @param $Object An object to iterate over and turn into an array
     * @return array
     */
    protected function convertObjectToArray($Object) {
        $array = array();
        foreach ($Object as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }

}