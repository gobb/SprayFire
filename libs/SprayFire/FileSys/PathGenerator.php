<?php

/**
 * Interface to create absolute paths to various SprayFire directories.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\FileSys;

use \SprayFire\Object as SFObject;

/**
 * It is expected that implementations of this interface will accept a variety of
 * arguments for each method; it is intended that implementations will determine
 * the appropriate way to allow the passing of sub-directory paths needed to
 * each method.
 *
 * @package SprayFire
 * @package FileSys
 */
interface PathGenerator extends SFObject {

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