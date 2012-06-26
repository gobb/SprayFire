<?php

/**
 * A value object for data to be used by a SprayFire.Routing.Routifier.Request
 * to generate the appropriate controller, action and parameters.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Routing\Routifier;

class UriData extends \SprayFire\ValueObject {

    /**
     * The actual request URI sent from the user
     *
     * @property string
     */
    protected $uri;

    /**
     * The name of the directory SprayFire is installed in
     *
     * @property string
     */
    protected $installDir;

    /**
     * The controller fragment returned if none was given in the request URI
     *
     * @property string
     */
    protected $defaultController = 'page';

    /**
     * The action fragment returned if none was given in the request URI
     *
     * @property string
     */
    protected $defaultAction = 'index';

    /**
     * The array of parameter fragments if none was given in the request URI
     *
     * @property array
     */
    protected $defaultParameters = array();

    /**
     * @param $uri string
     * @param $installDir string
     * @param $defaultController mixed String or null to use default
     * @param $defaultAction mixed String or null to use default
     * @param $defaultParameters mixed Array or null to use default
     */
    public function __construct($uri, $installDir, $defaultController = null, $defaultAction = null, $defaultParameters = null) {
        $data = \compact('defaultController', 'defaultAction', 'defaultParameters');
        $this->removeNullValues($data);
        $data['uri'] = $uri;
        $data['installDir'] = $installDir;
        parent::__construct($data);
    }

    /**
     * @param &$array array
     */
    protected function removeNullValues(array &$array) {
        foreach ($array as $key => $value) {
            if (is_null($value)) {
                unset($array[$key]);
            }
        }
    }

    /**
     * @return array
     */
    protected function getAccessibleProperties() {
        $properties = array();
        $properties['uri'] = 'string';
        $properties['installDir'] = 'string';
        $properties['defaultController'] = 'string';
        $properties['defaultAction'] = 'string';
        $properties['defaultParameters'] = 'array';
        return $properties;
    }

    /**
     * @return array
     */
    public function toArray() {
        $properties = array();
        $properties['uri'] = $this->uri;
        $properties['installDir'] = $this->installDir;
        $properties['defaultController'] = $this->defaultController;
        $properties['defaultAction'] = $this->defaultAction;
        $properties['defaultParameters'] = $this->defaultParameters;
        return $properties;
    }
}