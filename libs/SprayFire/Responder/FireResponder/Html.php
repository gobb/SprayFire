<?php

/**
 * A Responder object that generates and sends a response as a string of HTML.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Responder\FireResponder;

use \SprayFire\Responder as SFResponder,
    \SprayFire\Controller as SFController,
    \SprayFire\Service\FireService as FireService,
    \Traversable as Traversable,
    \stdClass as stdClass;

/**
 * @package SprayFire
 * @subpackage Responder.FireResponder
 *
 * @todo
 * We should take a look at abstracting out the services aspect of this into a
 * base Responder that should be available to all SprayFire.Responder.Responder
 * implementations.
 *
 */
class Html extends FireService\Consumer implements SFResponder\Responder {

    /**
     * Stores the final generated response from this Responder
     *
     * @property string
     */
    protected $response = '';

    /**
     * Provides functionality to SprayFire.Service.FireService.Consumer implementation.
     *
     * @property array
     */
    protected $services = array(
        'Paths' => 'SprayFire.FileSys.FireFileSys.Paths',
        'Escaper' => 'SprayFire.Responder.FireResponder.OutputEscaper'
    );

    /**
     *
     * @param SprayFire.Controller.Controller $Controller
     * @return string
     */
    public function generateDynamicResponse(SFController\Controller $Controller) {
        $TemplateManager = $Controller->getTemplateManager();
        $LayoutTemplate = $TemplateManager->getLayoutTemplate();
        $data = array();
        $data['Responder'] = $this;
        $data = \array_merge($data, $Controller->getResponderData());
        $contentTemplates = $TemplateManager->getContentTemplates();
        if ($this->isTraversable($contentTemplates)) {
            foreach ($contentTemplates as $name => $Template) {
                $data[$name] = $Template->getContent($data);
            }
        }

        echo $LayoutTemplate->getContent($data);
    }

    /**
     * Determines if the given $traversable is a valid argument to a foreach() loop
     *
     * @param mixed $traversable
     * @return boolean
     */
    protected function isTraversable($traversable) {
        if (\is_array($traversable) || $traversable instanceof Traversable || $traversable instanceof stdClass) {
            return true;
        }
        return false;
    }

}