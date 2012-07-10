<?php

/**
 * A Responder object that generates and sends a response as a string of HTML.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Responder;

use \SprayFire\Responder\Responder as Responder,
    \SprayFire\Service\FireBox\Consumer as ServiceConsumer;

class HtmlResponder extends ServiceConsumer implements Responder {

    /**
     *
     * @property SprayFire.Controller.Controller
     */
    protected $Controller;

    /**
     *
     * @property string
     */
    protected $response = '';

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
    public function generateResponse(\SprayFire\Controller\Controller $Controller) {
        $this->Controller = $Controller;
        $data = $this->getSafeData();
        $templatePath = $this->Controller->getTemplatePath();
        $templateContent = $this->render($templatePath, $data);
        $data['templateContent'] = $templateContent;
        $layoutPath = $this->Controller->getLayoutPath();
        $this->response = $this->render($layoutPath, $data);
        return $this->response;
    }

    /**
     * Returns an array of data from the Controller that is clean, with any dirty
     * data being sanitized.
     *
     * @return array
     */
    protected function getSafeData() {
        $cleanData = $this->Controller->getCleanData();
        $dirtyData = $this->Controller->getDirtyData();
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
     */
    public function sanitizeData(array $data) {
        $cleanData = array();
        foreach ($data as $key => $dirtyValue) {
            $cleanValue = \htmlspecialchars($dirtyValue, \ENT_COMPAT, 'UTF-8', false);
            $cleanData[$key] = $cleanValue;
        }
        return $cleanData;
    }

}