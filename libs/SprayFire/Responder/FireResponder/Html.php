<?php

/**
 * A Responder object that generates and sends a response as a string of HTML.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Responder\FireResponder;

use \SprayFire\Responder,
    \SprayFire\Controller,
    \Traversable,
    \stdClass;

/**
 * @package SprayFire
 * @subpackage Responder.Implementation
 */
class Html extends Base implements Responder\Responder {

    /**
     *
     * @param \SprayFire\Controller\Controller $Controller
     * @return string
     */
    public function generateResponse(Controller\Controller $Controller) {
        $TemplateManager = $Controller->getTemplateManager();
        $data = [];
        $data['Responder'] = $this;
        $data = \array_merge($data, $this->getEscapedData($Controller));
        $contentTemplates = $TemplateManager->getContentTemplates();
        if ($this->isTraversable($contentTemplates)) {
            foreach ($contentTemplates as $name => $Template) {
                /* @var \SprayFire\Responder\Template\Template $Template */
                $data[$name] = $Template->getContent($data);
            }
        }

        $content = $TemplateManager->getLayoutTemplate()->getContent($data);
        $this->Response->setBody($content);
        return $this->Response;
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
