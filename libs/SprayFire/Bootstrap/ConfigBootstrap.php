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
 * @uses SprayFire.Logging.Logger
 * @uses SprayFire.Bootstrap.Bootstrapper
 * @uses SprayFire.Core.Util.CoreObject
 * @uses SprayFire.Structure.Map.RestrictedMap
 */
class ConfigBootstrap extends \SprayFire\Core\Util\CoreObject implements \SprayFire\Bootstrap\Bootstrapper {

    /**
     * @brief A SprayFire.Structure.Map.RestrictedMap, restricted to SprayFire.Config.Configuration
     * objects, that will hold the objects created when the bootstrap is ran.
     *
     * @property $ConfigMap
     */
    protected $ConfigMap;

    /**
     * @brief A SprayFire.Logging.Logger used to store messages.
     *
     * @property $Logger
     */
    protected $Logger;

    /**
     * @brief A SprayFire.Config.Configuration object holding data needed by this
     * bootstrap.
     *
     * @property $Config
     */
    protected $Config;

    /**
     * @param $Log SprayFire.Logger.Log To log various error messages that may occur
     * @param $Config An object holding data needed by this bootstrap
     * @throws SprayFire.Exception.FatalRuntimeException
     * @see /config/primary-configuration.php \a $configData
     */
    public function __construct(\SprayFire\Logging\Logger $Logger, \SprayFire\Config\Configuration $Config) {
        $this->Logger = $Logger;
        $this->Config = $Config;
    }

    public function runBootstrap() {
        $this->populateConfigMap();
        return $this->ConfigMap;
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
        $configInfo = $this->Config;
        $this->createConfigMap();
        foreach ($configInfo as $info) {
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
                $object = $this->replaceDotsWithBackSlashes($info['object']);
                $this->ConfigMap->setObject($info['map-key'], new $object($argument));
            } catch (\InvalidArgumentException $InvalArgExc) {
                $this->Logger->log('Unable to instantiate the Configuration object, ' . $info['map-key'] . ', or it does not implement Object interface.');
            }
        }
    }

    protected function createConfigMap() {
        try {
            $configInterface = $this->replaceDotsWithBackSlashes($this->Config['interface']);
            $this->ConfigMap = new \SprayFire\Structure\Map\RestrictedMap($configInterface);
        } catch (\SprayFire\Exception\TypeNotFoundException $InvalArgExc) {
            $this->Logger->log($InvalArgExc->getMessage());
            $this->ConfigMap = new \SprayFire\Structure\Map\GenericMap();
        }
    }

    /**
     * @param $className A Java-style namespaced class
     * @return A PHP-style namespaced class
     */
    protected function replaceDotsWithBackSlashes($className) {
        $backSlash = '\\';
        $dot = '.';
        if (\strpos($className, $dot) !== false) {
            $className = \str_replace($dot, $backSlash, $className);
        }
        return $backSlash . \trim($className, '\\ ');
    }

}