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
 * @property \SprayFire\FileSys\FireFileSys\Paths $Paths
 * @property \SprayFire\Responder\FireResponder\OutputEscaper $Escaper
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
     * @param \SprayFire\Controller\Controller $Controller
     * @return string
     */
    public function generateDynamicResponse(SFController\Controller $Controller) {
        $TemplateManager = $Controller->getTemplateManager();
        $LayoutTemplate = $TemplateManager->getLayoutTemplate();
        $data = array();
        $data['Responder'] = $this;
        $data = \array_merge($data, $this->getEscapedData($Controller));
        $contentTemplates = $TemplateManager->getContentTemplates();
        if ($this->isTraversable($contentTemplates)) {
            foreach ($contentTemplates as $name => $Template) {
                $data[$name] = $Template->getContent($data);
            }
        }

        echo $LayoutTemplate->getContent($data);
    }

    /**
     * Will get the appropriate data from the $Controller with the appropriate
     * contexts and return an array with that data escaped in that context.
     *
     * @param \SprayFire\Controller\Controller $Controller
     * @return array
     */
    protected function getEscapedData(SFController\Controller $Controller) {
        $dirtyHtmlContent = (array) $Controller->getResponderData(SFResponder\OutputEscaper::HTML_CONTENT_CONTEXT);
        $dirtyHtmlAttribute = (array) $Controller->getResponderData(SFResponder\OutputEscaper::HTML_ATTRIBUTE_CONTEXT);
        $dirtyCss = (array) $Controller->getResponderData(SFResponder\OutputEscaper::CSS_CONTEXT);
        $dirtyJavaScript = (array) $Controller->getResponderData(SFResponder\OutputEscaper::JAVASCRIPT_CONTEXT);

        $cleanHtmlContent = $this->Escaper->escapeHtmlContent($dirtyHtmlContent);
        $cleanHtmlAttribute = $this->Escaper->escapeHtmlAttribute($dirtyHtmlAttribute);
        $cleanCss = $this->Escaper->escapeCss($dirtyCss);
        $cleanJavaScript = $this->Escaper->escapeJavaScript($dirtyJavaScript);
        return \array_merge(array(), $cleanHtmlContent, $cleanHtmlAttribute, $cleanCss, $cleanJavaScript);
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
