<?php

/**
 *
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */
namespace SprayFire\Responder\FireResponder;

use \SprayFire\Responder,
    \SprayFire\Controller,
    \SprayFire\Service\FireService;

/**
 *
 * @package SprayFire
 * @subpackage Responder.Implementation
 *
 * @property \SprayFire\FileSys\FireFileSys\Paths $Paths
 * @property \SprayFire\Responder\FireResponder\OutputEscaper $Escaper
 * @property \SprayFire\Http\Response $Response
 */
abstract class Base extends FireService\Consumer implements Responder\Responder {

    /**
     * Provides functionality to \SprayFire\Service\FireService\Consumer implementation.
     *
     * @property array
     */
    protected $services = [
        'Paths' => 'SprayFire.FileSys.FireFileSys.Paths',
        'Escaper' => 'SprayFire.Responder.FireResponder.OutputEscaper',
        'Response' => 'SprayFire.Http.FireHttp.Response'
    ];

    /**
     * Will get the appropriate data from the $Controller with the appropriate
     * contexts and return an array with that data escaped in that context.
     *
     * @param \SprayFire\Controller\Controller $Controller
     * @return array
     */
    protected function getEscapedData(Controller\Controller $Controller) {
        $dirtyHtmlContent = (array) $Controller->getResponderData(Responder\OutputEscaper::HTML_CONTENT_CONTEXT);
        $dirtyHtmlAttribute = (array) $Controller->getResponderData(Responder\OutputEscaper::HTML_ATTRIBUTE_CONTEXT);
        $dirtyCss = (array) $Controller->getResponderData(Responder\OutputEscaper::CSS_CONTEXT);
        $dirtyJavaScript = (array) $Controller->getResponderData(Responder\OutputEscaper::JAVASCRIPT_CONTEXT);

        $cleanHtmlContent = $this->Escaper->escapeHtmlContent($dirtyHtmlContent);
        $cleanHtmlAttribute = $this->Escaper->escapeHtmlAttribute($dirtyHtmlAttribute);
        $cleanCss = $this->Escaper->escapeCss($dirtyCss);
        $cleanJavaScript = $this->Escaper->escapeJavaScript($dirtyJavaScript);
        return \array_merge([], $cleanHtmlContent, $cleanHtmlAttribute, $cleanCss, $cleanJavaScript);
    }

}
