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
class BootstrapData extends \SprayFire\Config\ArrayConfig {

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
        $convertDeep = false;
        parent::__construct($this->gatherMasterData(), $convertDeep);
    }

    /**
     * @return An array of data to be used by various bootstrap objects
     */
    protected function gatherMasterData() {
        $masterData = array();
        $masterData['PathGenBootstrap'] = $this->directoryPaths;
        if (!empty($this->primaryConfig)) {
            $masterData['ConfigBootstrap'] = $this->primaryConfig['configData'];
            $masterData['IniBootstrap'] = $this->primaryConfig['iniSettings'];
            $masterData['LoggingBootstrap'] = $this->primaryConfig['loggerData'];
        } else {
            $masterData['ConfigBootstrap'] = array();
            $masterData['IniBootstrap'] = array();
            $masterData['LoggingBootstrap'] = array();
        }
        return $masterData;
    }

}