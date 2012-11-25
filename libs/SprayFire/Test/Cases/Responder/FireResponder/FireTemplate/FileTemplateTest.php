<?php

/**
 * Unit test covering SprayFire.Responder.FireResponder.FireTemplate.FileTemplate.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Responder\FireResponder\FireTemplate;

use SprayFire\Responder\FireResponder\FireTemplate as FireResponderTemplate;

/**
 * @package SprayFireTest
 * @subpackage Cases.Responder.FireResponder.FireTemplate
 */
class FileTemplateTest extends \PHPUnit_Framework_TestCase {

    /**
     * Ensures that a file is properly rendered with the passed data.
     */
    public function testFileTemplateRenderingContent() {
        $name = '';
        $filePath = \SPRAYFIRE_ROOT .'/libs/SprayFire/Test/mockframework/';
        $filePath .= 'libs/SprayFire/Responder/html/file-template-test.php';
        $FileTemplate = new FireResponderTemplate\FileTemplate($name, $filePath);

        $data = array(
            'foo' => 'bar'
        );

        $expected = '<div>bar</div>';
        $actual = $FileTemplate->getContent($data);

        $this->assertSame($expected, $actual);
    }

}
