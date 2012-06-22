<?php

/**
 * The base framework controller, by default inherited by framework provided controllers
 * and is a prime choice for the parent controller in your app controllers.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Controller;

class Base extends \SprayFire\Util\CoreObject implements \SprayFire\Controller\Controller {

    /**
     * An array of data that would need to be sanitized by the Responder before
     * the response is sent to the user.
     *
     * @property array
     */
    protected $dirtyData = array();

    /**
     * An array of data that does not necessarily need to be sanitized by the
     * Responder before the response is sent to the user.
     *
     * @property array
     */
    protected $cleanData = array();

    public function __construct() {

    }

    public function getCleanData() {
        return $this->cleanData;
    }

    public function getDirtyData() {
        return $this->dirtyData;
    }

    public function giveCleanData(array $data) {
        $this->cleanData = \array_merge($this->cleanData, $data);
    }

    public function giveDirtyData(array $data) {
        $this->dirtyData = \array_merge($this->dirtyData, $data);
    }

    public function getModels() {

    }

    public function getResponderName() {

    }

}