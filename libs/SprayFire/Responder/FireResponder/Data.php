<?php

/**
 * Implementation of SprayFire.Responder.Data that is provided by the default SprayFire
 * install.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Responder\FireResponder;

use \SprayFire\Responder as SFResponder,
    \SprayFire\CoreObject as SFCoreObject;

/**
 * A very simple implementation designed to hold data designed for a specific
 * web context.
 *
 * @package SprayFire
 * @subpackage`Responder.FireResponder
 */
class Data extends SFCoreObject implements SFResponder\Data {

    /**
     * @property array
     */
    protected $cssData = array();

    /**
     * @property array
     */
    protected $htmlAttributeData = array();

    /**
     * @property array
     */
    protected $htmlContentData = array();

    /**
     * @property array
     */
    protected $javaScriptData = array();

    /**
     * @return array
     */
    public function getCssData() {
        return $this->cssData;
    }

    /**
     * @return array
     */
    public function getHtmlAttributeData() {
        return $this->htmlAttributeData;
    }

    /**
     * @return array
     */
    public function getHtmlContentData() {
        return $this->htmlContentData;
    }

    /**
     * @return array
     */
    public function getJavaScriptData() {
        return $this->javaScriptData;
    }

    /**
     * Will provide the $value to CSS data context, accessible from the array returned
     * by getCssData using $name as index.
     *
     * Any $name already set will be overwritten with $value.
     *
     * @param string $name
     * @param mixed $value
     */
    public function provideCssData($name, $value) {
        $this->cssData[$name] = $value;
    }

    /**
     * will provide the $value to JavaScript data context, accessible from the array
     * returned by getJavaScriptData using $name as index.
     *
     * Any $name already set will be overwritten with $value.
     *
     * @param string $name
     * @param mixed $value
     */
    public function provideJavaScriptData($name, $value) {
        $this->javaScriptData[$name] = $value;
    }

    /**
     * Will provide the $value to HTML attribute data context, accessible from the
     * array returned by getHtmlAttributeData using $name as index.
     *
     * Any $name already set will be overwritten with $value.
     *
     * @param string $name
     * @param mixed $value
     */
    public function provideHtmlAttributeData($name, $value) {
        $this->htmlAttributeData[$name] = $value;
    }

    /**
     * Will provide the $value to HTML content data context, accessible from the
     * array returned by getHtmlContentData using $name as index.
     *
     * Any $name already set will be overwritten with $value.
     *
     * @param string $name
     * @param mixed $value
     */
    public function provideHtmlContentData($name, $value) {
        $this->htmlContentData[$name] = $value;
    }

}