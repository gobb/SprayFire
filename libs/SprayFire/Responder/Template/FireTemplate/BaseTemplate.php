<?php

/**
 * Abstract class to provide basic getName() support across a variety of templates
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Responder\Template\FireTemplate;

use \SprayFire\Responder\Template as SFResponderTemplate,
    \SprayFire\StdLib as SFStdLib;

/**
 * @package SprayFire
 * @subpackage Responder.FireResponder.FireTemplate
 */
abstract class BaseTemplate extends SFStdLib\CoreObject implements SFResponderTemplate\Template {

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
