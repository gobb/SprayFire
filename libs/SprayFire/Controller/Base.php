<?php

/**
 * The base framework controller, by default inherited by framework provided controllers
 * and is a prime choice for the parent controller in your app controllers.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Controller;

class Base extends \SprayFire\Util\CoreObject implements \SprayFire\Controller\Controller {

    protected $dirtyData = array();

    public function __construct() {

    }

    public function getCleanData() {

    }

    public function getDirtyData() {
        return $this->dirtyData;
    }

    public function giveCleanData(array $data) {

    }

    public function giveDirtyData(array $data) {
        $this->dirtyData = \array_merge($data);
    }

    public function getModels() {

    }

    public function getResponderName() {

    }

}