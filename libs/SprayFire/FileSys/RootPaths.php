<?php

/**
 * A value object for root directory paths for the creation of a SprayFire.FileSys.PathGenerator
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\FileSys;

class RootPaths extends \SprayFire\ValueObject {

    protected $install;

    protected $libs;

    protected $app;

    protected $web;

    protected $config;

    protected $logs;

    public function __construct($install, $libs = null, $app = null, $web = null, $config = null, $logs = null) {
        $paths = \compact('libs', 'app', 'web', 'config', 'logs');
        $install = (string) $install;
        $this->replaceNullWithDefault($paths, $install);
        $paths['install'] = $install;
        parent::__construct($paths);
    }

    protected function replaceNullWithDefault(array &$paths, $install) {
        foreach ($paths as $key => $value) {
            if (\is_null($value)) {
                $paths[$key] = $install . '/' . $key;
            }
        }
    }

    public function toArray() {
        $array = array();
        $array['install'] = $this->install;
        $array['libs'] = $this->libs;
        $array['app'] = $this->app;
        $array['web'] = $this->web;
        $array['config'] = $this->config;
        $array['logs'] = $this->logs;
    }

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