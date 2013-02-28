<?php

/**
 * An interface that allows the abstraction of templates that deliver the appropriate
 * content for a response.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Responder\Template;

use \SprayFire\Object as SFObject;

/**
 * @package SprayFire
 * @subpackage Responder.Template
 */
interface Template extends SFObject {

    /**
     * Return a value that can be used to uniquely identify this template.
     *
     * @return string
     */
    public function getName();

    /**
     * Return the fully rendered template based on the $data passed.
     *
     * @param array $data
     * @return string
     */
    public function getContent(array $data);

}
