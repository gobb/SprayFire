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
     * @brief Should be a SprayFire.Config.Configuration holding four keys: emergency,
     * error, debug and info with those keys holding two keys themselves: object and
     * blueprint.
     *
     * @property $Config
     */
    protected $Config;

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
     * While it is possible to pass a chainable SprayFire.Config.Configuration object
     * it should be noted that this implementation will not recursively convert the
     * inner objects into arrays.  Meaning, it is guaranteed that if a chainable
     * Configuration object is passed the outer object will be converted into an
     * array so it may be passed to ReflectionClass::newInstanceArgs(), but not
     * necessarily that the SprayFire.Structure.Storage.ImmutableStorage objects
     * associated with keys of that Configuration have been converted into arrays.
     * Right now this is considered status-by-design.  If this becomes a problem
     * raise an issue with unit tests showing your proposed changes, do not pass
     * a chainable configuration object and/or change your implementation to accept
     * a DataStorage object instead of an array.
     *
     * @param $LogOverseerConfig SprayFire.Config.Configuration
     * @param $LoggerFactory \SprayFire\Factory\Factory
     */
    public function __construct(\SprayFire\Config\Configuration $LogOverseerConfig, \SprayFire\Factory\Factory $LoggerFactory) {
        $this->Config = $LogOverseerConfig;
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
        $object = $this->getObjectName($this->Config->emergency);
        $blueprint = $this->getObjectBlueprint($this->Config->emergency);
        $this->LogDelegator->setEmergencyLogger($object, $blueprint);
    }

    protected function setErrorLogger() {
        $object = $this->getObjectName($this->Config->error);
        $blueprint = $this->getObjectBlueprint($this->Config->error);
        $this->LogDelegator->setErrorLogger($object, $blueprint);
    }

    protected function setDebugLogger() {
        $object = $this->getObjectName($this->Config->debug);
        $blueprint = $this->getObjectBlueprint($this->Config->debug);
        $this->LogDelegator->setDebugLogger($object, $blueprint);
    }

    protected function setInfoLogger() {
        $object = $this->getObjectName($this->Config->info);
        $blueprint = $this->getObjectBlueprint($this->Config->info);
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