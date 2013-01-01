<?php

/**
 * Unit test covering SprayFire.Responder.FireResponder.FireTemplate.FileTemplate.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Responder\FireTemplate;

use SprayFire\Responder\Template\FireTemplate as FireTemplate;

/**
 * @package SprayFireTest
 * @subpackage Cases.Responder.FireResponder.FireTemplate
 */
class FileTemplateTest extends \PHPUnit_Framework_TestCase {

    protected $mockFrameworkPath;

    public function setUp() {
        $this->mockFrameworkPath = \SPRAYFIRE_ROOT .'/libs/SprayFire/Test/mockframework/';
    }

    /**
     * Ensures that a file is properly rendered with the passed data.
     */
    public function testFileTemplateRenderingContent() {
        $name = '';
        $filePath = $this->mockFrameworkPath . 'libs/SprayFire/Responder/html/file-template-test.php';
        $FileTemplate = new FireTemplate\FileTemplate($name, $filePath);

        $data = array(
            'foo' => 'bar'
        );

        $expected = '<div>bar</div>';
        $actual = $FileTemplate->getContent($data);

        $this->assertSame($expected, $actual);
    }

    /**
     * Ensures that an exception is thrown if the $filePath passed to a FileTemplate
     * does not exist or cannot be properly opened.
     */
    public function testFileTemplateThrowingExceptionIfFileDoesNotExist() {
        $name = '';
        $filePath = $this->mockFrameworkPath . 'libs/SprayFire/Responder/html/file-does-not-exist.php';
        $this->setExpectedException('\SprayFire\Responder\Template\Exception\FileNotFound');
        $FileTemplate = new FireTemplate\FileTemplate($name, $filePath);
    }

    public function testFileTemplatePassingSplFileObjectToFilePath() {
        $name = '';
        $filePath = $this->mockFrameworkPath . 'libs/SprayFire/Responder/html/file-template-test.php';
        $File = new \SplFileObject($filePath);

        $FileTemplate = new FireTemplate\FileTemplate($name, $File);

        $data = array(
            'foo' => 'foobar'
        );

        $expected = '<div>foobar</div>';
        $actual = $FileTemplate->getContent($data);

        $this->assertSame($expected, $actual);
    }

    public function testGettingTemplateName() {
        $name = 'sprayfire';
        $filePath = $this->mockFrameworkPath . 'libs/SprayFire/Responder/html/file-template-test.php';

        $FileTemplate = new FireTemplate\FileTemplate($name, $filePath);

        $this->assertSame('sprayfire', $FileTemplate->getName());
    }

}
