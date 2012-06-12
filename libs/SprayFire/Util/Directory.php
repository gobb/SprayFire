<?php

/**
 * Holds a class to ease the creation of absolute paths for directories and files
 * used by the app and framework.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Util;

/**
 * Used as a utility class to easily create absolute paths for various directories
 * in the app and framework.
 *
 * All of the getter methods in this class allow a variable number of parameters
 * passed to it.  You can either pass these directly as parameters to the function
 * or an array of strings.
 *
 * @uses SprayFire.PathGenerator
 * @uses SprayFire.Util.CoreObject
 */
class Directory extends \SprayFire\Util\CoreObject implements \SprayFire\PathGenerator {

    /**
     * Path holding the primary directories used by the app and framework.
     *
     * @property string
     */
    protected $installPath;

    /**
     * Path holding framework and third-party classes used by the app and framework.
     *
     * @property string
     */
    protected $libsPath;

    /**
     * Path holding app classes
     *
     * @property string
     */
    protected $appPath;

    /**
     * Path holding configuration files
     *
     * @property string
     */
    protected $configPath;

    /**
     * Path holding log files that are written to by the framework or app
     *
     * @property string
     */
    protected $logsPath;

    /**
     * Path holding files that are accessible via the web
     *
     * @property string
     */
    protected $webPath;

    /**
     * Accepts an array of paths that will be used to generate absolute paths.
     *
     * @param $paths array
     */
    public function __construct(array $paths) {
        $this->installPath = isset($paths['install']) ? $paths['install'] : '';
        $this->libsPath = isset($paths['libs']) ? $paths['libs'] : '';
        $this->appPath = isset($paths['app']) ? $paths['app'] : '';
        $this->configPath = isset($paths['config']) ? $paths['config'] : '';
        $this->logsPath = isset($paths['logs']) ? $paths['logs'] : '';
        $this->webPath = isset($paths['web']) ? $paths['web'] : '';
    }

    /**
     * @return string Path holding the app and framework directories
     */
    public function getInstallPath() {
        return $this->generateFullPath('installPath', \func_get_args());
    }

    /**
     * @return string Path holding the framework and 3rd party libraries
     */
    public function getLibsPath() {
        return $this->generateFullPath('libsPath', \func_get_args());
    }

    /**
     * @return string Path holding the apps driven by the framework
     */
    public function getAppPath() {
        return $this->generateFullPath('appPath', \func_get_args());
    }

    /**
     * @return string Absolute path to the configuration file
     */
    public function getConfigPath() {
        return $this->generateFullPath('configPath', \func_get_args());
    }

    /**
     * @return string Absolute path to directories where logs should be kept
     */
    public function getLogsPath() {
        return $this->generateFullPath('logsPath', \func_get_args());
    }

    /**
     * @return string Absolute path, for backend code, to web accessible files
     */
    public function getWebPath() {
        return $this->generateFullPath('webPath', \func_get_args());
    }

    /**
     * @return string A relative path, suitable for HTML, to web accessible files
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
     * @param $property string The class property to use for the primary path
     * @param $subDir array A list of possible sub directories to add to primary path
     * @return string Absolute path for the given class \a $property and \a $subDir
     */
    protected function generateFullPath($property, array $subDir) {
        if (\count($subDir) === 0) {
            return $this->$property;
        }
        return $this->$property . '/' . $this->generateSubDirectoryPath($subDir);
    }

    /**
     * @param $subDir array A list of possible sub directories
     * @return string A sub directory path, with n otrailing separator
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