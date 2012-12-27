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
     * Used in conjunction with preserveDataType to indicate that boolean and
     * numeric types should be escaped.
     */
    const NO_PRESERVATION = 1;

    /**
     * Used in conjunction with preserveDataType to indicate that boolean data
     * should be preserved and not escaped.
     */
    const PRESERVE_BOOLEAN = 2;

    /**
     * Used in conjunction with preserveDataType to indicate that numeric data,
     * integers and floats, should be preserved and not escaped.
     */
    const PRESERVE_NUMERIC = 4;

    /**
     * @property \Zend\Escaper\Escaper
     */
    protected $ZendEscaper;

    /**
     * The property that controls whether or not numeric and/or boolean data should
     * be preserved during escaping.
     *
     * @property integer
     */
    protected $typePreservation = self::NO_PRESERVATION;

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
        $type = \gettype($data);
        $preserveBooleanAndNumeric = self::PRESERVE_NUMERIC | self::PRESERVE_BOOLEAN;
        $preserveBooleanOnly = self::PRESERVE_BOOLEAN;
        $preserveNumericOnly = self::PRESERVE_NUMERIC;
        $escapedData = null;
        switch ($type) {
            case 'array':
            case 'object':
                $escapedData = $this->escapeMultipleContent($data, $context);
                break;
            case 'double':
            case 'integer':
                if ($this->typePreservation === $preserveBooleanAndNumeric || $this->typePreservation === $preserveNumericOnly) {
                    $escapedData = $data;
                } else {
                    $escapedData = $this->ZendEscaper->$context($data);
                }
                break;
            case 'boolean':
                if ($this->typePreservation === $preserveBooleanAndNumeric || $this->typePreservation === $preserveBooleanOnly) {
                    $escapedData = $data;
                } else {
                    $escapedData = $this->ZendEscaper->$context($data);
                }
                break;
            default:
                $escapedData = $this->ZendEscaper->$context($data);
        }

        return $escapedData;
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
            $escapedData[$key] = $this->escapeContent($value, $context);
        }
        return $escapedData;
    }

    /**
     * Indicate whether or not boolean and numeric data types should be preserved
     * and not escaped.
     *
     * It is strongly advised that you make use of the OutputEscaper constants
     * to ensure the appropriate data types are preserved.
     *
     * Preserve boolean and numeric data
     * -------------------------------------------------------------------------
     * $typeBitMask = OutputEscaper::PRESERVE_NUMERIC | OutputEscaper::PRESERVE_BOOLEAN
     *
     * Preserve boolean data only
     * -------------------------------------------------------------------------
     * $typeBitMask = OutputEscaper::PRESERVE_BOOLEAN
     *
     * Preserve numeric data only
     * -------------------------------------------------------------------------
     * $typeBitMask = OutputEscaper::PRESERVE_NUMERIC
     *
     * Any other value will result in no preservation taking place and all values
     * being escaped.
     *
     * @param $typeBitMask
     * @return void
     */
    public function preserveDataType($typeBitMask) {
        $this->typePreservation = $typeBitMask;
    }

}
