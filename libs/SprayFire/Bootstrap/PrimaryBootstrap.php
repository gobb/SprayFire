<?php

/**
 * @file
 * @brief Will instantiate and run other bootstraps, creating a DI container
 * for SprayFire components.
 */

namespace SprayFire\Bootstrap;

/**
 * @brief
 */
class PrimaryBootstrap extends \SprayFire\Util\CoreObject implements \SprayFire\Bootstrap\Bootstrapper {

    /**
     * @brief SprayFire.Bootstrap.BootstrapData object passed from constructor
     * holding the information needed to run secondary bootstraps
     *
     * @var $Data
     */
    protected $Data;

    protected $PathGenerator;

    protected $LogOverseer;

    /**
     * @brief
     *
     * @param $Data SprayFire.Bootstrap.BootstrapData
     */
    public function __construct(\SprayFire\Bootstrap\BootstrapData $Data) {
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
        $data = $this->Data->PathGenBootstrap;
        $Bootstrap = new \SprayFire\Bootstrap\PathGeneratorBootstrap($data);
        $this->PathGenerator = $Bootstrap->runBootstrap();
    }

    protected function runLogOverseerBootstrap() {
        $data = $this->Data->LoggingBootstrap;
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