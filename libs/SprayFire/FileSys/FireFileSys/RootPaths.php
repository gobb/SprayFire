<?php

/**
 * Implementation of \SprayFire\ValueObject that allows the storing of paths used
 * by \SprayFire\FileSys\FireFileSys\Paths
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\FileSys\FireFileSys;

use \SprayFire\StdLib as SFStdLib;

/**
 * This is a package private implementation and is intended to be used by
 * \SprayFire\FileSys\FireFileSys module.
 *
 * @package SprayFire
 * @subpackage FileSys.FireFileSys
 */
class RootPaths extends SFStdLib\ValueObject {

    /**
     * The full, absolute path to the directory holding SprayFire source.
     *
     * @property string
     */
    protected $install;

    /**
     * The full, absolute path to the directory holding SprayFire API and implementations.
     *
     * @property string
     */
    protected $libs;

    /**
     * The full, absolute path to the directory holding SprayFire driven applications.
     *
     * @property string
     */
    protected $app;

    /**
     * The full, absolute path to the directory holding web accessible files.
     *
     * @property string
     */
    protected $web;

    /**
     * The full, absolute path to the directory holding framework and app config files.
     *
     * @property string
     */
    protected $config;

    /**
     * The full, absolute path to the directory holding logs written to file.
     *
     * @property string
     */
    protected $logs;

    /**
     * @param string $install
     * @param string $libs
     * @param string $app
     * @param string $web
     * @param string $config
     * @param string $logs
     */
    public function __construct($install, $libs = null, $app = null, $web = null, $config = null, $logs = null) {
        $paths = \compact('libs', 'app', 'web', 'config', 'logs');
        $install = (string) $install;
        $this->replaceNullWithDefault($paths, $install);
        $paths['install'] = $install;
        parent::__construct($paths);
    }

    /**
     * Will replace any paths that aren't provided with the default path being
     * $install/path_name.
     *
     * @param array &$paths
     * @param string $install
     */
    protected function replaceNullWithDefault(array &$paths, $install) {
        foreach ($paths as $key => $value) {
            if (\is_null($value)) {
                $paths[$key] = $install . '/' . $key;
            }
        }
    }

    /**
     * Required from \SprayFire\ValueObject, required in case PHP array functions
     * are needed to be performed on the value object.
     *
     * @return array
     */
    public function toArray() {
        $array = array();
        $array['install'] = $this->install;
        $array['libs'] = $this->libs;
        $array['app'] = $this->app;
        $array['web'] = $this->web;
        $array['config'] = $this->config;
        $array['logs'] = $this->logs;
        return $array;
    }

    /**
     * Required from \SprayFire\ValueObject, provides the parent implementation
     * with details about what properties are allowed to be accessed by the
     * outside world.
     *
     * @return array
     */
    protected function getAccessibleProperties() {
        $properties = array();
        $properties['install'] = 'string';
        $properties['libs'] = 'string';
        $properties['app'] = 'string';
        $properties['web'] = 'string';
        $properties['config'] = 'string';
        $properties['logs'] = 'string';
        return $properties;
    }

}
