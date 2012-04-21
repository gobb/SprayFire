<?php

/**
 * @file
 * @brief A configuration object used by the SprayFire.Bootstrap.PrimaryBootstrap
 * to properly startup the system.
 */

namespace SprayFire\Bootstrap;

/**
 * @uses SprayFire.Config.ArrayConfig
 */
class BootstrapData {

    /**
     * @brief An associative array of directory paths to set in a PathGenerator
     * implementation.
     *
     * @var $directoryPaths
     */
    protected $directoryPaths;

    /**
     * @brief The array of data returned from /config/primary-configuration.php
     *
     * @var $primaryConfig
     */
    protected $primaryConfig;

    /**
     * @brief An array of configuration data to be used by the PrimaryBootstrap
     * and the Bootstrapper objects it instantiates.
     *
     * @var $masterData
     */
    protected $masterData;

    /**
     * @param $primaryConfigPath The complete path to the primary configuraiton file
     * @param $directoryPaths An array of complete paths to set in a SprayFire.Core.PathGenerator
     *        implementation
     */
    public function __construct($primaryConfigPath, array $directoryPaths) {
        $this->primaryConfig = array();
        if (\file_exists($primaryConfigPath)) {
            $this->primaryConfig = include $primaryConfigPath;
        }
        $this->directoryPaths = $directoryPaths;
        $this->setMasterData();
    }

    /**
     * @param $property string
     */
    public function __get($property) {
        if ($this->__isset($property)) {
            return $this->masterData[$property];
        }
        return null;
    }

    /**
     * @param $property string
     */
    public function __isset($property) {
        if (!array_key_exists($property, $this->masterData)) {
            return false;
        }
        return isset($this->masterData[$property]);
    }

    /**
     * @param $property string
     * @param $value mixed
     * @throws SprayFire.Exception.UnsupportedOperationException
     */
    public function __set($property, $value) {
        throw new \SprayFire\Exception\UnsupportedOperationException('This method cannot be invoked on this object');
    }

    public function __unset($property) {
        throw new \SprayFire\Exception\UnsupportedOperationException('This method cannot be invoked on this object');
    }

    /**
     * @return An array of data to be used by various bootstrap objects
     */
    protected function setMasterData() {
        $masterData = array();
        $masterData['PathGenBootstrap'] = $this->directoryPaths;
        if (!empty($this->primaryConfig)) {
            $masterData['ConfigData'] = $this->primaryConfig['configData'];
            $masterData['IniBootstrap'] = $this->primaryConfig['iniSettings'];
            $masterData['LoggingBootstrap'] = $this->primaryConfig['loggerData'];
        } else {
            $masterData['ConfigData'] = array();
            $masterData['IniBootstrap'] = array();
            $masterData['LoggingBootstrap'] = array();
        }
        $this->masterData = $masterData;
    }

}