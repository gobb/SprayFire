<?php

/**
 * @file
 * @brief Holds a class responsible for setting up a SprayFire.Core.PathGenerator
 * implementation used to work with the directory structure.
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire repository: http://www.github.com/cspray/SprayFire/
 *
 * SprayFire wiki: http://www.github.com/cspray/SprayFire/wiki/
 *
 * SprayFire API Documentation: http://www.cspray.github.com/SprayFire/
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011,2012 Charles Sprayberry
 */

namespace SprayFire\Bootstrap;

/**
 * @brief Will take an associative array that has the absolute paths needed to
 * successfully implement SprayFire.Core.PathGenerator; see the details on how to
 * setup the array needed for this.
 *
 * @details
 * The following table should be used to show the key that is expected for the
 * constructor injected array.
 *
 * <code>
 * -----------------------------------------------------------------------------
 * |    key             |           value                                      |
 * -----------------------------------------------------------------------------
 * |    installPath     |  The absolute path holding the libs, app, config, and|
 * |                    |  web directories.                                    |
 * -----------------------------------------------------------------------------
 * |    libsPath        |  The absolue path tholding the libs directory, to    |
 * |                    |  include SprayFire and third-party libs.             |
 * -----------------------------------------------------------------------------
 * |    appPath         |  The absolute path holding the app classes and files |
 * -----------------------------------------------------------------------------
 * |    configPath      |  The absolute path to the directory holding config   |
 * |                    |  files used by SprayFire.                            |
 * -----------------------------------------------------------------------------
 * |    logsPath        |  The absolute path for the directory holding error   |
 * |                    |  stat files generated by SprayFire or your app, this |
 * |                    |  directory, and sub-directories, should be writable  |
 * -----------------------------------------------------------------------------
 * |    webPath         |  The absolute path for the directory holding web     |
 * |                    |  accessible files.                                   |
 * -----------------------------------------------------------------------------
 * </code>
 *
 * @note
 * To change the implementation produced by this code simply adjust this bootstrap
 * to work with the new implementation.  As long as your implementation properly
 * returns a SprayFire.Core.PathGenerator object when getPathGenerator() is invoked
 * on this Bootstrap object you should feel free to implement whatever directory
 * system you want, as long as it returns the appropriate directories and the files
 * looking for exist in those directories.  Please also adjust code in the following
 * files if the constructor dependencies for this implementation change:
 *
 * - <code>/web/index.php</code>
 *
 * @uses SprayFire.Bootstrap.Bootstrapper
 * @uses SprayFire.Core.PathGenerator
 * @uses SprayFire.Core.Directory
 */
class PathGeneratorBootstrap implements \SprayFire\Bootstrap\Bootstrapper {

    /**
     * @brief The SprayFire.Core.PathGenerator object created.
     *
     * @details
     * By default the object returned will be a SprayFire.Core.Directory implementation
     * of the required interface.
     *
     * @property $Directory
     */
    protected $Directory;

    /**
     * @brief Holds the associative array, as described in class details.
     *
     * @property $paths
     */
    protected $paths = array();

    /**
     * @param $paths An associative array of paths to use for creating SrayFire.Core.PathGenerator
     * object
     */
    public function __construct(array $paths) {
        $this->paths = $paths;
    }

    /**
     * @brief Will ensure that the proper SprayFire.Core.PathGenerator object is
     * created
     */
    public function runBootstrap() {
        $this->createDirectory();
    }

    /**
     * @brief Will create a SprayFire.Core.PathGenerator object and assign it to
     * the \a $Directory property.
     */
    protected function createDirectory() {
        $Directory = new \SprayFire\Core\Directory();
        $Directory->setInstallPath($this->paths['installPath']);
        $Directory->setLibsPath($this->paths['libsPath']);
        $Directory->setAppPath($this->paths['appPath']);
        $Directory->setLogsPath($this->paths['logsPath']);
        $Directory->setConfigPath($this->paths['configPath']);
        $Directory->setWebPath($this->paths['webPath']);
        $this->Directory = $Directory;
    }

    /**
     * @return SprayFire.Core.PathGenerator object
     */
    public function getPathGenerator() {
        return $this->Directory;
    }

}