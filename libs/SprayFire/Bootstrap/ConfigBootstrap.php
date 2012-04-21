<?php

/**
 * @file
 * @brief Holds a class designed to create the SprayFire.Config.Configuration objects
 * needed by SprayFire.
 */

namespace SprayFire\Bootstrap;

/**
 * @brief This class takes an array of associative arrays holding three keys:
 * config-data, config-object, and map-key.
 *
 * @details
 * This Bootstrapper object will dynamically create a series of SprayFire.Config.Configuration
 * objects, as detailed in the associative arrays passed at class construction.
 * Each associative array in the passed argument should have the following keys:
 *
 * <pre>
 *  'object' = the fully namespaced class to create for the given configuration
 *  'data' = a string representing a complete path to the configuration file
 *  'map-key' = a string representing the key to use for the given
 * </pre>
 *
 *
 * @uses ReflectionClass
 * @uses SprayFire.Config.Configuration
 * @uses SprayFire.Bootstrap.Bootstrapper
 * @uses SprayFire.Logging.LogOverseer
 * @uses SprayFire.Core.Util.UtilObject
 */
class ConfigBootstrap extends \SprayFire\Util\UtilObject implements \SprayFire\Bootstrap\Bootstrapper {

    /**
     * @brief Used as a key in the configuration passed in constructor
     */
    const OBJECT_INDEX = '';

    /**
     * @brief Used as a key in the configuration passed in constructor
     */
    const DATA_INDEX = '';

    /**
     * @brief Used as a key in the configuration passed in constructor
     */
    const MAP_KEY_INDEX = '';

    /**
     * @brief A SprayFire.Logging.LogOverseer used to store messages.
     *
     * @property $Logger
     */
    protected $Logger;

    /**
     * @brief A SprayFire.Config.Configuration object holding data needed by this
     * bootstrap.
     *
     * @property $Config
     * @see /config/primary-configuration.php
     */
    protected $Config;

    /**
     * @brief An array holding the objects that will be returned from runBootstrap()
     *
     * @property $configObjects
     */
    protected $configObjects = array();

    /**
     * @param $Logger SprayFire.Logger.LogOverseer To log various error messages that may occur
     * @param $Config An object holding data needed by this bootstrap
     * @see /config/primary-configuration.php \a $configData
     */
    public function __construct(\SprayFire\Logging\LogOverseer $Logger, \SprayFire\Config\Configuration $Config) {
        $this->Logger = $Logger;
        $this->Config = $Config;
    }

    /**
     * @return SprayFire.Structure.Map.ObjectMap holding the config objects requested
     * by the configuration passed
     */
    public function runBootstrap() {
        $this->populateConfigObjects();
        return $this->configObjects;
    }

    /**
     * @brief Will create the appropriate objects and store them in \a $configObjects.
     *
     * @details
     * For now, if the value of 'data' is a string it is assumed that it is
     * an absolute path for a SplFileInfo object.  If the 'data' is an array
     * then it is assumed to be the actual data to be passed to the configuration
     * object.
     */
    protected function populateConfigObjects() {
        $configInfo = $this->Config;
        foreach ($configInfo as $info) {
            if (!\is_array($info)) {
                continue;
            }
            try {
                $data = $info['data'];
                if (\is_array($data)) {
                    $argument = $data;
                } else {
                    if (!\file_exists($data)) {
                        throw new \InvalidArgumentException('The file path passed could not be found.');
                    }
                    $argument = new \SplFileInfo($data);
                }
                $object = $this->convertJavaClassToPhpClass($info['object']);
                $this->configObjects[$info['map-key']] = new $object($argument);
            } catch (\InvalidArgumentException $InvalArgExc) {
                $this->Logger->logError('Unable to instantiate the Configuration object, ' . $info['map-key'] . ', or it does not implement Object interface.');
            }
        }
    }

}