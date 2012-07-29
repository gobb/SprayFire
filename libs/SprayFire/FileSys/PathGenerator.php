<?php

/**
 * An interface used to get absolute and relative paths to directories and files
 * used by the framework and/or app.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\FileSys;

use \SprayFire\Object as Object;

interface PathGenerator extends Object {

    /**
     * Should return the root path that the app and framework is installed in;
     * should also accept either an array or a variable number of arguments to be
     * interpreted as the sub directories and/or files to append to the install
     * path.
     *
     * @return string
     */
    public function getInstallPath();

    /**
     * Should return the libs path that SprayFire and third-party libs are installed
     * in; should also accept either an array or a variable number of arguments
     * to be interpreted as the sub directories and/or files to append to the libs
     * path.
     *
     * @return string
     */
    public function getLibsPath();

    /**
     * Should return the app path that the app is installed in should also accept
     * either an array or a variable number of arguments to be interpreted as the
     * sub directories and/or files to append to the app path.
     *
     * @return string
     */
    public function getAppPath();

    /**
     * Should return the logs path that error and stats logs should be stored in;
     * should also accept either an array or a variable number of arguments to be
     * interpreted as the sub directories and/or files to append to the logs path.
     *
     * @return string
     */
    public function getLogsPath();

    /**
     * Should return the config path that the configuration files are stored in;
     * should also accept either an array or a variable number of arguments to be
     * interpreted as the sub directories and/or files to append to the config path.
     *
     * @return string
     */
    public function getConfigPath();

    /**
     * Should return the web path that the web accessible files are stored in;
     * should also accept either an array or a variable number of arguments to be
     * interpreted as the sub directories and/or files to append to the web path.
     *
     * @return string
     */
    public function getWebPath();

    /**
     * Should return a relative path suitable for use in HTML templates; should
     * also accept either an array or a variable number of arguments to be interpreted
     * as the sub directories and/or files to append to the web $path.
     *
     * Ultimately this means that this function will need to return the basename
     * of the install path appended to the web path without the root directory
     * attached.
     *
     * @return string
     */
    public function getUrlPath();

}