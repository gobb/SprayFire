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
    \SprayFire\Exception as SFException;

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
     * Will create the appropriate HTML structure based on the contents of the
     * $Controller->getLayoutPath() and $Controller->getTemplatePath() return
     * variables.
     *
     * For your layout the contents of the template will be in a variable named
     * $templateContent.
     *
     * @param SprayFire.Controller.Controller $Controller
     * @return string
     */
    public function generateDynamicResponse(SFController\Controller $Controller) {
        $data = $Controller->getResponderData();
        $templatePath = $Controller->getTemplatePath();
        $templateContent = $this->render($templatePath, $data);
        $data['templateContent'] = $templateContent;
        $layoutPath = $Controller->getLayoutPath();
        $this->response = $this->render($layoutPath, $data);
        return $this->response;
    }

    /**
     * Will include the $filePath and extract $data, providing whatever is in $data
     * as variables.
     *
     * @todo This is in a rudimentary state to get tests passing.  We need to take
     * a look at adding some kind of error handling to this.
     *
     * @param string $filePath
     * @param array $data
     * @return string
     */
    protected function render($filePath, array $data) {
        if (!\file_exists($filePath)) {
            throw new SFException\ResourceNotFoundException($filePath . ' could not be found.');
        }
        \extract($data);
        \ob_start();
        include $filePath;
        $content = \ob_get_contents();
        \ob_end_clean();
        return $content;
    }

}