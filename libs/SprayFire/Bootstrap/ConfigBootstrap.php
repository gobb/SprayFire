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
 *  'config-object' = the fully namespaced class to create for the given configuration
 *  'config-data' = a string representing a complete path to the configuration file
 *  'map-key' = a string representing the key to use for the given
 * </pre>
 *
 * If the SprayFire.Config.Configuration interface could not be loaded in the constructor,
 * as necessary to make the SprayFire.Core.Structure.RestrictedMap, an exception
 * will be thrown.  Our rationale behind this is that if the configuration values
 * can't be read SprayFire can't really do anything.  Basic routing can't take place
 * and that means we don't ever really know what to do.
 *
 * @uses ReflectionClass
 * @uses SprayFire.Config.Configuration
 * @uses SprayFire.Logger.Log
 * @uses SprayFire.Bootstrap.Bootstrapper
 * @uses SprayFire.Logger.CoreObject
 * @uses SprayFire.Core.Structure.RestrictedMap
 */
class ConfigBootstrap extends \SprayFire\Logger\CoreObject implements \SprayFire\Bootstrap\Bootstrapper {

    /**
     * @brief A SprayFire.Core.Structure.RestrictedMap, restricted to SprayFire.Config.Configuration
     * objects, that will hold the objects created when the bootstrap is ran.
     *
     * @property $ConfigMap
     */
    protected $ConfigMap;

    /**
     * @brief An array, holding the configuration information, formatted according
     * to the details of the class documentation.
     *
     * @property $configInfo
     */
    protected $configInfo;

    /**
     * @param $Log SprayFire.Logger.Log To log various error messages that may occur
     * @param $configInfo An array of configuration information to create objects
     * @throws \SprayFire\Exception\FatalRuntimeException
     */
    public function __construct(\SprayFire\Logger\Log $Log, array $configInfo, $configInterface = '\\SprayFire\\Config\\Configuration') {
        parent::__construct($Log);
        try {
            $Map = new \SprayFire\Core\Structure\RestrictedMap($configInterface);
        } catch (\SprayFire\Exception\TypeNotFoundException $TypeExc) {
            $this->log('Unable to load ' . $configInterface . ', do not have resources to create appropriate configuration objects.');
            throw new \SprayFire\Exception\FatalRuntimeException('The Configuration interface was not found, please ensure this interface was loaded or is loadable.');
        }
        $this->ConfigMap = $Map;
        $this->configInfo = $configInfo;
    }

    public function runBootstrap() {
        $this->populateConfigMap();
    }

    /**
     * @brief Will create the appropriate objects and store them in \a $ConfigMap.
     *
     * @details
     * For now, if the value of 'config-data' is a string it is assumed that it is
     * an absolute path for a SplFileInfo object.  If the 'config-data' is an array
     * then it is assumed to be the actual data to be passed to the configuration
     * object.
     *
     * If the objects created do not implement SprayFire.Config.Configuration they
     * will be skipped in the creation.  If a 'map-key' is not appearing in the
     * returned map check your log, it may be that the class is simply not implementing
     * the proper interface.
     */
    protected function populateConfigMap() {
        $configInfo = $this->configInfo;
        foreach ($configInfo as $info) {
            try {
                $data = $info['config-data'];
                if (\is_array($data)) {
                    $argument = $data;
                } else {
                    if (!\file_exists($data)) {
                        throw new \InvalidArgumentException('The file path passed could not be found.');
                    }
                    $argument = new \SplFileInfo($data);
                }
                $object = $info['config-object'];
                $this->ConfigMap->setObject($info['map-key'], new $object($argument));
            } catch (\InvalidArgumentException $InvalArgExc) {
                $this->log('Unable to instantiate the Configuration object, ' . $info['map-key'] . ', or it does not implement Object interface.');
            }
        }
    }

    /**
     * @return SprayFire.Core.Structure.RestrictedMap Of SprayFire.Config.Configuration objects
     */
    public function getConfigs() {
        return $this->ConfigMap;
    }

}