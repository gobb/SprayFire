<?php

/**
 * A value object for root directory paths for the creation of a SprayFire.FileSys.PathGenerator
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\FileSys\FireFileSys;

use \SprayFire\ValueObject as ValueObject;

class RootPaths extends ValueObject {

    /**
     * @property string
     */
    protected $install;

    /**
     * @property string
     */
    protected $libs;

    /**
     * @property string
     */
    protected $app;

    /**
     * @property string
     */
    protected $web;

    /**
     * @property string
     */
    protected $config;

    /**
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
     * @param array $paths
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