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

namespace SprayFire\Responder\Template\FireTemplate;

use \SprayFire\Responder\Template as SFResponderTemplate;

/**
 * @package SprayFire
 * @subpackage`Responder.FireResponder.FireTemplate
 */
class FileTemplate extends BaseTemplate {

    /**
     * The absolute path to the file storing the output that should be rendered
     *
     * @property SplFileObject
     */
    protected $File;

    /**
     * @param string $name
     * @param string $filePath
     * @throws \SprayFire\Responder\Template\Exception\FileNotFound
     */
    public function __construct($name, $filePath) {
        parent::__construct($name);
        if ($filePath instanceof \SplFileObject) {
            $this->File = $filePath;
        } elseif (\is_string($filePath)) {
            try {
                $this->File = new \SplFileObject($filePath);
            } catch(\RuntimeException $RuntimeException) {
                $message = 'The file path, ' . $filePath . ', could not be found or opened properly';
                throw new SFResponderTemplate\Exception\FileNotFound($message, null, $RuntimeException);
            }
        }
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
        include (string) $this->File;
        $contents = \ob_get_contents();
        \ob_end_clean();
        return $contents;
    }

}
