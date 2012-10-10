<?php

/**
 * Interface representing an object capable of escaping output in various web
 * contexts.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Responder;

use \SprayFire\Object as SFObject;

/**
 * All escape* methods should accept a piece of data in the form of a string or
 * an array of strings, if an array is passed the escaping should be done
 * recursively.
 *
 * @package SprayFire
 * @subpackage
 */
interface OutputEscaper extends SFObject {

    /**
     * Pass a string or string[] that should be escaped in a CSS context.
     *
     * @param mixed $data
     * @return mixed
     */
    public function escapeCss($data);

    /**
     * Pass a string or string[] that should be escape in a HTML attribute context.
     *
     * @param mixed $data
     * @return mixed
     */
    public function escapeHtmlAttribute($data);

    /**
     * Pass a string or string[] that should be escaped in a HTML content context.
     *
     * @param mixed $data
     * @return mixed
     */
    public function escapeHtmlContent($data);

    /**
     * Pass a string or string[] that should be escaped in a JavaScript context.
     *
     * @param mixed $data
     * @return mixed
     */
    public function escapeJavaScript($data);

}