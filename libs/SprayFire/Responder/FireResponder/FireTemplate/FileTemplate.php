<?php

/**
 * Implementation of SprayFire.Responder.Template.Template that will render output
 * stored in a file.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Responder\FireResponder\FireTemplate;

/**
 * @package SprayFire
 * @subpackage`Responder.FireResponder.FireTemplate
 */
class FileTemplate extends BaseTemplate {

    /**
     * The absolute path to the file storing the output that should be rendered
     *
     * @property string
     */
    protected $filePath;

    /**
     * @param string $name
     * @param string $filePath
     */
    public function __construct($name, $filePath) {
        parent::__construct($name);
        $this->filePath = $filePath;
    }

    /**
     * Generates the appropriate content based on the $filePath passed to constructor
     * and the $data that should fill in placeholders for the template stored in
     * $filePath.
     *
     * @param array $data
     * @return string
     */
    public function getContent(array $data) {
        \extract($data);
        \ob_start();
        include $this->filePath;
        $contents = \ob_get_contents();
        \ob_end_clean();
        return $contents;
    }

}