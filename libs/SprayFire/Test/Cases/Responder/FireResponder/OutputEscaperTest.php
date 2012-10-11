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

    /**
     * Ensures that an array of data passed to be escaped is returned with all
     * values escaped and keys preserved.
     */
    public function testArrayOfStringsHtmlContentEscaped() {
        $data = array(
            'singleQuote' => '\'',
            'doubleQuote' => '"',
            'lessThan' => '<',
            'greaterThan' => '>',
            'ampersand' => '&'
        );
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeHtmlContent($data);

        $expected = array(
            'singleQuote' => '&#039;',
            'doubleQuote' => '&quot;',
            'lessThan' => '&lt;',
            'greaterThan' => '&gt;',
            'ampersand' => '&amp;'
        );
        $this->assertSame($expected, $Escaper->escapeHtmlContent($data));
    }

    /**
     * Ensures that a nested array of strings is properly escaped with all keys
     * reserved.
     *
     * Although this is only testing 4 levels of nesting we assume that the algorithm
     * to pass this test is recursive and can go to an arbirtrary level of nesting.
     */
    public function testNestedArrayOfStringsHtmlContentEscaped() {
        $data = array(
            'singleQuote' => '\'',
            'doubleQuote' => '"',
            'lessThan' => '<',
            'greaterThan' => '>',
            'ampersand' => '&',
            'secondLevel' => array(
                'singleQuote' => '\'',
                'doubleQuote' => '"',
                'lessThan' => '<',
                'greaterThan' => '>',
                'ampersand' => '&',
                'thirdLevel' => array(
                    'singleQuote' => '\'',
                    'doubleQuote' => '"',
                    'lessThan' => '<',
                    'greaterThan' => '>',
                    'ampersand' => '&',
                    'fourthLevel' => array(
                        'singleQuote' => '\'',
                        'doubleQuote' => '"',
                        'lessThan' => '<',
                        'greaterThan' => '>',
                        'ampersand' => '&'
                    )
                )
            )
        );
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeHtmlContent($data);

        $expected = array(
            'singleQuote' => '&#039;',
            'doubleQuote' => '&quot;',
            'lessThan' => '&lt;',
            'greaterThan' => '&gt;',
            'ampersand' => '&amp;',
            'secondLevel' => array(
                'singleQuote' => '&#039;',
                'doubleQuote' => '&quot;',
                'lessThan' => '&lt;',
                'greaterThan' => '&gt;',
                'ampersand' => '&amp;',
                'thirdLevel' => array(
                    'singleQuote' => '&#039;',
                    'doubleQuote' => '&quot;',
                    'lessThan' => '&lt;',
                    'greaterThan' => '&gt;',
                    'ampersand' => '&amp;',
                    'fourthLevel' => array(
                        'singleQuote' => '&#039;',
                        'doubleQuote' => '&quot;',
                        'lessThan' => '&lt;',
                        'greaterThan' => '&gt;',
                        'ampersand' => '&amp;'
                    )
                )
            )
        );

        $this->assertSame($expected, $escaped);
    }

    /**
     * Ensures that some characters we expect to be escaped for an HTML attribute
     * context are properly escaped: ', ", <, >, &, Ā, ' ' are checked.
     */
    public function testSingleStringHtmlAttributeEscaped() {
        $data = '\',",<,>,&,Ā, ';
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeHtmlAttribute($data);
        $this->assertSame('&#x27;,&quot;,&lt;,&gt;,&amp;,&#x0100;,&#x20;', $escaped);
    }

    /**
     * Ensures that appropriate control characters, such as line feeds, tabs and
     * null are properly escaped.
     */
    public function testSingleControlCharactersHtmlAttributeEscaped() {
        $data = "\r,\n,\t,\0";
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeHtmlAttribute($data);
        $this->assertSame('&#x0D;,&#x0A;,&#x09;,&#xFFFD;', $escaped);
    }

    /**
     * Ensures that some basic characters that should not be escaped in an HTML
     * attribute context are indeed not escaped.
     */
    public function testBasicCharactersNotEscapedHtmlAttribute() {
        $data = 'A,a,Z,z,0,9,.,-,_';
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeHtmlAttribute($data);
        $this->assertSame('A,a,Z,z,0,9,.,-,_', $escaped);
    }

    /**
     * Ensures that a single dimension array of strings are properly escaped.
     */
    public function testArrayOfStringHtmlAttributeEscaped() {
        $data = array(
            'singleQuote' => '\'',
            'doubleQuote' => '"',
            'lessThan', '<',
            'greaterThan', '>',
            'ampersand' => '&',
            'above255Ascii' => 'Ā',
            'space' => ' ',
            'carriageReturn' => "\r",
            'carriageLine' => "\n",
            'tab' => "\t",
            'null' => "\0",
            'a' => 'a',
            'A' => 'A',
            'z' => 'z',
            'Z' => 'Z',
            'zero' => '0',
            'nine' => '9',
            'period' => '.',
            'dash' => '-',
            'underscore' => '_',
            'comma' => ','
        );
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeHtmlAttribute($data);

        $expected = array(
            'singleQuote' => '&#x2l;',
            'doubleQuote' => '&quot;',
            'lessThan' => '&lt;',
            'greaterThan' => '&gt;',
            'ampersand' => '&amp;',
            'above255Ascii' => '&#x0100;',
            'space' => '&#x20;',
            'carriageReturn' => '&#x0D;',
            'carriageLine' => '&#x0A;',
            'tab' => '&#x09;',
            'null' => '&#xFFFD;',
            'a' => 'a',
            'A' => 'A',
            'z' => 'z',
            'Z' => 'Z',
            'zero' => '0',
            'nine' => '9',
            'period' => '.',
            'dash' => '-',
            'unerscore' => '_',
            'comma' => ','
        );

        $this->assertSame($expected, $escaped);
    }

}
