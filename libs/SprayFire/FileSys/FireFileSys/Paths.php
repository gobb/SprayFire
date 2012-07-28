<?php

/**
 * Class to ease the creation of absolute paths for directories and files used by
 * apps and the framework.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\FileSys\FireFileSys;

use \SprayFire\FileSys\PathGenerator as PathGenerator,
    \SprayFire\CoreObject as CoreObject,
    \SprayFire\FileSys\FireFileSys\RootPaths as RootPaths;

/**
 * All of the getter methods in this class allow a variable number of parameters
 * passed to it.
 *
 * Method 1 - parameters
 * -----------------------------------------------------------------------------
 * Paths::getInstallPath('path', 'to', 'your', 'file');
 *
 * Method 2 - array
 * -----------------------------------------------------------------------------
 * Paths::getInstallPath(array('path', 'to', 'your', 'file'));
 */
class Paths extends CoreObject implements PathGenerator {

    /**
     * @property string
     */
    protected $installPath;

    /**
     * @property string
     */
    protected $libsPath;

    /**
     * @property string
     */
    protected $appPath;

    /**
     * @property string
     */
    protected $configPath;

    /**
     * @property string
     */
    protected $logsPath;

    /**
     * @property string
     */
    protected $webPath;

    /**
     * @param SprayFire.FileSys.RootPaths
     */
    public function __construct(RootPaths $RootPaths) {
        $this->installPath = $RootPaths->install;
        $this->libsPath = $RootPaths->libs;
        $this->appPath = $RootPaths->app;
        $this->configPath = $RootPaths->config;
        $this->logsPath = $RootPaths->logs;
        $this->webPath = $RootPaths->web;
    }

    /**
     * @return string
     */
    public function getInstallPath() {
        return $this->generateFullPath('installPath', \func_get_args());
    }

    /**
     * @return string
     */
    public function getLibsPath() {
        return $this->generateFullPath('libsPath', \func_get_args());
    }

    /**
     * @return string
     */
    public function getAppPath() {
        return $this->generateFullPath('appPath', \func_get_args());
    }

    /**
     * @return string
     */
    public function getConfigPath() {
        return $this->generateFullPath('configPath', \func_get_args());
    }

    /**
     * @return string
     */
    public function getLogsPath() {
        return $this->generateFullPath('logsPath', \func_get_args());
    }

    /**
     * Path suitable for back-end file system access to the web directory
     *
     * @return string
     */
    public function getWebPath() {
        return $this->generateFullPath('webPath', \func_get_args());
    }

    /**
     * Path suitable for front-end HTML linking to files in the web directory
     *
     * @return string
     */
    public function getUrlPath() {
        $webRoot = \basename($this->getWebPath());
        $installRoot = \basename($this->getInstallPath());
        $urlPath = '/' . $installRoot . '/' . $webRoot;
        $subDir = \func_get_args();
        if (\count($subDir) === 0) {
            return $urlPath;
        }
        return $urlPath . '/' . $this->generateSubDirectoryPath($subDir);
    }

    /**
     * @param string $property
     * @param array $subDir
     * @return string
     */
    protected function generateFullPath($property, array $subDir) {
        if (\count($subDir) === 0) {
            return $this->$property;
        }
        return $this->$property . '/' . $this->generateSubDirectoryPath($subDir);
    }

    /**
     * @param array $subDir
     * @return string
     */
    protected function generateSubDirectoryPath(array $subDir) {
        $subDirPath = '';
        if (\is_array($subDir[0])) {
            $subDir = $subDir[0];
        }
        foreach ($subDir as $dir) {
            $subDirPath .= \trim($dir) . '/';
        }
        $subDirPath = \rtrim($subDirPath, '/');
        return $subDirPath;
    }

}