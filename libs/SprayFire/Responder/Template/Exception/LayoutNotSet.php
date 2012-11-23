<?php

/**
 * Exception thrown if SprayFire.Responder.Template.Manager::getLayoutTemplate()
 * is called without first calling setLayoutTemplate($Template).
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Responder\Template\Exception;

/**
 * @package SprayFire
 * @subpackage`Responder.Template.Exception
 */
class LayoutNotSet extends \RuntimeException {

}