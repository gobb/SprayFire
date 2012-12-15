<?php

/**
 * Abstract class to provide basic getName() support across a variety of templates
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Responder\Template\FireTemplate;

use \SprayFire\Responder\Template as SFResponderTemplate,
    \SprayFire\CoreObject as SFCoreObject;

/**
 * @package SprayFire
 * @subpackage`Responder.FireResponder.FireTemplate
 */
abstract class BaseTemplate extends SFCoreObject implements SFResponderTemplate\Template {

    /**
     * The name for the template
     *
     * @property string
     */
    protected $name;

    /**
     * @param string $name
     */
    public function __construct($name) {
        $this->name = (string) $name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

}