<?php

/**
 * Interface to represent data that is passed to a SprayFire.Responder.Responder.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Responder;

use \SprayFire\Object as SFObject;

/**
 * This interface is intended to provide data to a Responder implementation in such
 * a way that the appropriate escaping can be carried out on the data.
 *
 * @package SprayFire
 * @subpackage Responder
 */
interface Data extends SFObject {

    /**
     * Return data that should be escaped for a CSS context.
     *
     * @return array
     */
    public function getCssData();

    /**
     * Return data that should be escaped for a JavaScript context.
     *
     * @return array
     */
    public function getJavaScriptData();

    /**
     * Return data that should be escaped for a HTML content context.
     *
     * @return array
     */
    public function getHtmlContentData();

    /**
     * Return data that should be escaped for a HTML attribute context.
     *
     * @return array
     */
    public function getHtmlAttributeData();

}