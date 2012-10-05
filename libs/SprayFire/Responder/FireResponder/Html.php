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
 *
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
        'Paths' => 'SprayFire.FileSys.FireFileSys.Paths'
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
        $data = $this->getSafeData($Controller);
        $templatePath = $Controller->getTemplatePath();
        $templateContent = $this->render($templatePath, $data);
        $data['templateContent'] = $templateContent;
        $layoutPath = $Controller->getLayoutPath();
        $this->response = $this->render($layoutPath, $data);
        return $this->response;
    }

    /**
     * Returns an array of data from the Controller that is clean, with any dirty
     * data being sanitized.
     *
     * @return array
     */
    protected function getSafeData(SFController\Controller $Controller) {
        $cleanData = $Controller->getCleanData();
        $dirtyData = $Controller->getDirtyData();
        $sanitizedData = $this->sanitizeData($dirtyData);
        return \array_merge(array(), $cleanData, $sanitizedData);
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

    /**
     * Ensures that an array of data has been escaped for unsafe HTML entities.
     *
     * @param array $data
     * @return array
     *
     * @todo
     * This is not a suitable solution as we do not know in what context the
     * various pieces of $data will be used.  We need to take a major overhaul
     * at how we are escaping and what the best method of escaping should be.
     */
    public function sanitizeData(array $data) {
        $cleanData = array();
        foreach ($data as $key => $dirtyValue) {
            if (\is_array($dirtyValue)) {
                $cleanData[$key] = $this->sanitizeData($dirtyValue);
            } else {
                $cleanValue = \htmlspecialchars($dirtyValue, \ENT_COMPAT, 'UTF-8', false);
                $cleanData[$key] = $cleanValue;
            }
        }
        return $cleanData;
    }

}