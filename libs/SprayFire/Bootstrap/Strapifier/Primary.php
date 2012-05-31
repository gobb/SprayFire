<?php

/**
 * @file
 * @brief Will instantiate and run other bootstraps, creating a DI container
 * for SprayFire components.
 */

namespace SprayFire\Bootstrap\Strapifier;

/**
 * @brief
 */
class Primary extends \SprayFire\Util\CoreObject implements \SprayFire\Bootstrap\Bootstrapper {

    /**
     * @brief SprayFire.Bootstrap.Strapifier.Configuration object passed from constructor
     * holding the information needed to run secondary bootstraps
     *
     * @var $Data
     */
    protected $Data;

    /**
     * @brief SprayFire.PathGenerator implemenation created by the PathGeneratorBootstrap
     *
     * @var $PathGenerator
     */
    protected $PathGenerator;

    /**
     * @brief SprayFire.Logging.LogOverseer implementation created by the LogOverseerBootstrap
     *
     * @var $LogOverseer
     */
    protected $LogOverseer;

    /**
     * @brief
     *
     * @param $Data SprayFire.Bootstrap.BootstrapData
     */
    public function __construct(\SprayFire\Bootstrap\Strapifier\Configuration $Data) {
        $this->Data = $Data;
    }

    public function runBootstrap() {
        $Container = new \SprayFire\Structure\Map\GenericMap();
        $this->runPathGeneratorBootstrap();
        $this->runLogOverseerBootstrap();
        $Container->setObject('PathGenerator', $this->PathGenerator);
        $Container->setObject('LogOverseer', $this->LogOverseer);
        return $Container;
    }

    protected function runPathGeneratorBootstrap() {
        $data = $this->Data->PathGeneratorBootstrap;
        $Bootstrap = new \SprayFire\Bootstrap\PathGeneratorBootstrap($data);
        $this->PathGenerator = $Bootstrap->runBootstrap();
    }

    protected function runLogOverseerBootstrap() {
        $data = $this->Data->LogOverseerBootstrap;
        $data['debug']['blueprint'] = array($this->getFileInfoObjectForDebugLogger($data));
        $data['info']['blueprint'] = array($this->getFileInfoObjectForInfoLogger($data));
        $Factory = new \SprayFire\Logging\Logifier\LoggerFactory();
        $Bootstrap = new \SprayFire\Bootstrap\LogOverseerBootstrap($Factory, $data);
        $this->LogOverseer = $Bootstrap->runBootstrap();
    }

    protected function getFileInfoObjectForDebugLogger(array $data) {
        $fileName = $data['debug']['blueprint'];
        $path = $this->PathGenerator->getLogsPath($fileName);
        return new \SplFileInfo($path);
    }

    protected function getFileInfoObjectForInfoLogger(array $data) {
        $fileName = $data['info']['blueprint'];
        $path = $this->PathGenerator->getLogsPath($fileName);
        return new \SplFileInfo($path);
    }

}