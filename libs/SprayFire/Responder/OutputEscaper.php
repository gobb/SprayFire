<?php

/**
 * Interface representing an object capable of escaping output in various web
 * contexts.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Responder;

use \SprayFire\Object;

/**
 * All escape* methods should accept a piece of data in the form of a string or
 * an array of strings, if an array is passed the escaping should be done
 * recursively.
 *
 * @package SprayFire
 * @subpackage Responder.Interface
 */
interface OutputEscaper extends Object {

    /**
     * Constant that can be used by other modules to signify data or escaping in
     * a CSS context.
     */
    const CSS_CONTEXT = 'css';

    /**
     * Constant that can be used by other modules to signify data or escaping in
     * a HTML content context.
     */
    const HTML_CONTENT_CONTEXT = 'html_content';

    /**
     * Constant that can be used by other modules to signify data or escaping in
     * a HTML attribute context.
     */
    const HTML_ATTRIBUTE_CONTEXT = 'html_attribute';

    /**
     * Constant that can be used by other modules to signify data or escaping in
     * a JavaScript context.
     */
    const JAVASCRIPT_CONTEXT = 'javascript';

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
