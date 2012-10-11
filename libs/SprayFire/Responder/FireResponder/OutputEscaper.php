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

    }

    /**
     * Pass a string or string[] that should be escape in a HTML attribute context.
     *
     * @param mixed $data
     * @return mixed
     */
    public function escapeHtmlAttribute($data) {

    }

    /**
     * Pass a string or string[] that should be escaped in a HTML content context.
     *
     * @param mixed $data
     * @return mixed
     */
    public function escapeHtmlContent($data) {
        if (\is_string($data)) {
            return $this->ZendEscaper->escapeHtml($data);
        } else if (\is_array($data)) {
            $escapedData = array();
            foreach ($data as $key => $value) {
                $escapedData[$key] = $this->ZendEscaper->escapeHtml($value);
            }
            return $escapedData;
        }

    }

    /**
     * Pass a string or string[] that should be escaped in a JavaScript context.
     *
     * @param mixed $data
     * @return mixed
     */
    public function escapeJavaScript($data) {

    }


}