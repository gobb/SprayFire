<?php

/**
 * Implementation of SprayFire.Responder.OutputEscaper provided by the default
 * SprayFire install.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Responder\FireResponder;

use \SprayFire\Responder as SFResponder,
    \SprayFire\CoreObject as SFCoreObject,
    \Zend\Escaper as ZendEscaper;

/**
 * This implementation is intended to test the use of the Zend.Escaper.Escaper
 * integration with SprayFire.
 *
 * @package SprayFire
 * @subpackage`Responder.FireResponder
 */
class OutputEscaper extends SFCoreObject implements SFResponder\OutputEscaper {

    /**
     * @property Zend.Escaper.Escaper
     */
    protected $ZendEscaper;

    /**
     * @param string $encoding
     */
    public function __construct($encoding) {
        $this->ZendEscaper = new ZendEscaper\Escaper($encoding);
    }

    /**
     * Pass a string or string[] that should be escaped in a CSS context.
     *
     * @param mixed $data
     * @return mixed
     */
    public function escapeCss($data) {
        return $this->escapeContent($data, 'escapeCss');
    }

    /**
     * Pass a string or string[] that should be escape in a HTML attribute context.
     *
     * @param mixed $data
     * @return mixed
     */
    public function escapeHtmlAttribute($data) {
        return $this->escapeContent($data, 'escapeHtmlAttr');
    }

    /**
     * Pass a string or string[] that should be escaped in a HTML content context.
     *
     * @param mixed $data
     * @return mixed
     */
    public function escapeHtmlContent($data) {
        return $this->escapeContent($data, 'escapeHtml');

    }

    /**
     * Pass a string or string[] that should be escaped in a JavaScript context.
     *
     * @param mixed $data
     * @return mixed
     */
    public function escapeJavaScript($data) {
        return $this->escapeContent($data, 'escapeJs');
    }

    /**
     * Will escape the appropriate data, be it a string or array, with the
     * $context passed being the name of the ZendEscaper method to invoke.
     *
     * @param mixed $data
     * @param string $context
     * @return mixed
     */
    protected function escapeContent($data, $context) {
        if (\is_string($data)) {
            return $this->ZendEscaper->$context($data);
        } else if (\is_array($data)) {
            $escapedData = $this->escapeMultipleContent($data, $context);
            return $escapedData;
        }
    }

    /**
     * Will recursively escape an array of data, the $context being the ZendEscaper
     * method to invoke on the strings in $data.
     *
     * @param array $data
     * @param string $context
     * @return array
     */
    protected function escapeMultipleContent(array $data, $context) {
        $escapedData = array();
        foreach ($data as $key => $value) {
            if (\is_array($value)) {
                $escapedData[$key] = $this->escapeMultipleContent($value, $context);
            } else {
                $escapedData[$key] = $this->ZendEscaper->$context($value);
            }
        }
        return $escapedData;
    }


}