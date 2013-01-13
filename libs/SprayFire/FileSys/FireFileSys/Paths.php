<?php

/**
 * Implementation of SprayFire.FileSys.Paths to create absolute paths to various
 * directories in SprayFire.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\FileSys\FireFileSys;

use \SprayFire\FileSys as SFFileSys,
    \SprayFire\CoreObject as SFCoreObject;

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
 *
 * @package SprayFire
 * @subpackage FileSys.FireFileSys
 */
class Paths extends SFCoreObject implements SFFileSys\PathGenerator {

    /**
     * The full, absolute path to the directory the SprayFire source was installed
     * in.
     *
     * Relative to this directory the framework API and implementations should
     * be in ./libs/SprayFire.
     *
     * @property string
     */
    protected $installPath;

    /**
     * The full, absolute path to the directory the SprayFire API and implementations
     * are stored in.
     *
     * @property string
     */
    protected $libsPath;

    /**
     * The full absolute path to the directory holding application API and implementations.
     *
     * @property string
     */
    protected $appPath;

    /**
     * The full, absolute path to the directory holding configuration values for
     * SprayFire and your applications.
     *
     * @property string
     */
    protected $configPath;

    /**
     * The full, absolute path to store logs written to files.
     *
     * @property string
     */
    protected $logsPath;

    /**
     * The full, absolute path to the web accessible folder for the framework
     * and your applications.
     *
     * @property string
     */
    protected $webPath;

    /**
     * Determines whether or not the SprayFire installation is setup to use VirtualHosts.
     *
     * @property boolean
     */
    protected $virtualHost;

    /**
     * @param \SprayFire\FileSys\FireFileSys\RootPaths
     */
    public function __construct(RootPaths $RootPaths, $virtualHost = false) {
        $this->installPath = $RootPaths->install;
        $this->libsPath = $RootPaths->libs;
        $this->appPath = $RootPaths->app;
        $this->configPath = $RootPaths->config;
        $this->logsPath = $RootPaths->logs;
        $this->webPath = $RootPaths->web;
        $this->virtualHost = (boolean) $virtualHost;
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
     * Path suitable for front-end HTML linking to files in the web directory.
     *
     * @return string
     */
    public function getUrlPath() {
        $webRoot = \basename($this->getWebPath());
        if ($this->virtualHost) {
            $urlPath = '/' . $webRoot;
        } else {
            $installRoot = \basename($this->getInstallPath());
            $urlPath = '/' . $installRoot . '/' . $webRoot;
        }
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
        return \rtrim($subDirPath, '/');
    }

}
