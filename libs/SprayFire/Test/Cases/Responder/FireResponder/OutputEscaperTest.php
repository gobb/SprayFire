<?php

/**
 * Test to ensure that data is escaped properly according to various web contexts.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Responder\FireResponder;

use \SprayFire\Responder\FireResponder as FireResponder;

/**
 * Ensures that data is properly escaped by SprayFire.Responder.FireResponder.OutputEscaper.
 *
 * @package SprayFireTest
 * @subpackage Cases.Responder.FireResponder
 */
class OutputEscaperTest extends \PHPUnit_Framework_TestCase {

    /**
     * Ensures that a single string escaped for HTML content context is properly
     * escaped.
     */
    public function testSingleHtmlContentStringEscaped() {
        $string = '\', ", <, >, &';
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeHtmlContent($string);
        $this->assertSame('&#039;, &quot;, &lt;, &gt;, &amp;', $escaped);
    }

}
