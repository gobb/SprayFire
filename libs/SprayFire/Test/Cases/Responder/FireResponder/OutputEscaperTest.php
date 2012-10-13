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
     * Used in tests ensuring that arrays of HTML content strings are properly escaped.
     *
     * @property array
     */
    protected $arrayHtmlContentUnescaped = array(
        'singleQuote' => '\'',
        'doubleQuote' => '"',
        'lessThan' => '<',
        'greaterThan' => '>',
        'ampersand' => '&'
    );

    /**
     * Used in tests ensuring that arrays of HTML content strings are properly escaped.
     *
     * @property array
     */
    protected $arrayHtmlContentEscaped = array(
        'singleQuote' => '&#039;',
        'doubleQuote' => '&quot;',
        'lessThan' => '&lt;',
        'greaterThan' => '&gt;',
        'ampersand' => '&amp;'
    );

    protected $arrayHtmlAttributeUnescaped = array(
        'singleQuote' => '\'',
        'doubleQuote' => '"',
        'lessThan' => '<',
        'greaterThan' => '>',
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

    protected $arrayHtmlAttributeEscaped = array(
        'singleQuote' => '&#x27;',
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
        'underscore' => '_',
        'comma' => ','
    );

    protected $arrayCssUnescaped = array(
        'lessThan' => '<',
        'greaterThan' => '>',
        'singleQuote' => '\'',
        'doubleQuote' => '"',
        'ampersand' => '&',
        'highAsciiValue' => 'Ā',
        'comma' => ',',
        'period' => '.',
        'underscore' => '_',
        'a' => 'a',
        'A' => 'A',
        'z' => 'z',
        'Z' => 'Z',
        'zero' => '0',
        'nine' => '9',
        'carriageReturn' => "\r",
        'newLine' => "\n",
        'tab' => "\t",
        'null' => "\0",
        'space' => ' '
    );

    protected $arrayCssEscaped = array(
        'lessThan' => '\\3C ',
        'greaterThan' => '\\3E ',
        'singleQuote' => '\\27 ',
        'doubleQuote' => '\\22 ',
        'ampersand' => '\\26 ',
        'highAsciiValue' => '\\100 ',
        'comma' => '\\2C ',
        'period' => '\\2E ',
        'underscore' => '\\5F ',
        'a' => 'a',
        'A' => 'A',
        'z' => 'z',
        'Z' => 'Z',
        'zero' => '0',
        'nine' => '9',
        'carriageReturn' => '\\D ',
        'newLine' => '\\A ',
        'tab' => '\\9 ',
        'null' => '\\0 ',
        'space' => '\\20 '
    );

    protected $arrayJavaScriptUnescaped = array(
        'lessThan' => '<',
        'greaterThan' => '>',
        'singleQuote' => '\'',
        'doubleQuote' => '"',
        'ampersand' => '&',
        'highAsciiValue' => 'Ā',
        'comma' => ',',
        'period' => '.',
        'underscore' => '_',
        'a' => 'a',
        'A' => 'A',
        'z' => 'z',
        'Z' => 'Z',
        'zero' => '0',
        'nine' => '9',
        'carriageReturn' => "\r",
        'newLine' => "\n",
        'tab' => "\t",
        'null' => "\0",
        'space' => ' '
    );

    protected $arrayJavaScriptEscaped = array(
        'lessThan' => '\\x3C',
        'greaterThan' => '\\x3E',
        'singleQuote' => '\\x27',
        'doubleQuote' => '\\x22',
        'ampersand' => '\\x26',
        'highAsciiValue' => '\\u0100',
        'comma' => ',',
        'period' => '.',
        'underscore' => '_',
        'a' => 'a',
        'A' => 'A',
        'z' => 'z',
        'Z' => 'Z',
        'zero' => '0',
        'nine' => '9',
        'carriageReturn' => "\\x0D",
        'newLine' => "\\x0A",
        'tab' => "\\x09",
        'null' => "\\x00",
        'space' => '\\x20'
    );

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
        $data = $this->arrayHtmlContentUnescaped;
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeHtmlContent($data);

        $expected = $this->arrayHtmlContentEscaped;
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
        $data = $this->arrayHtmlContentUnescaped;
        $data['secondLevel'] = $this->arrayHtmlContentUnescaped;
        $data['secondLevel']['thirdLevel'] = $this->arrayHtmlContentUnescaped;
        $data['secondLevel']['thirdLevel']['fourthLevel'] = $this->arrayHtmlContentUnescaped;

        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeHtmlContent($data);

        $expected = $this->arrayHtmlContentEscaped;
        $expected['secondLevel'] = $this->arrayHtmlContentEscaped;
        $expected['secondLevel']['thirdLevel'] = $this->arrayHtmlContentEscaped;
        $expected['secondLevel']['thirdLevel']['fourthLevel'] = $this->arrayHtmlContentEscaped;

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
     * Ensures that a single dimension array of strings are properly escaped in
     * HTML attribute context.
     */
    public function testArrayOfStringHtmlAttributeEscaped() {
        $data = $this->arrayHtmlAttributeUnescaped;

        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeHtmlAttribute($data);

        $expected = $this->arrayHtmlAttributeEscaped;

        $this->assertSame($expected, $escaped);
    }

    /**
     * Ensures that a nested array of HTML attribute data is properly escaped.
     */
    public function testNestedArrayOfStringsHtmlAttributeEscape() {
        $data = $this->arrayHtmlAttributeUnescaped;
        $data['secondLevel'] = $this->arrayHtmlAttributeUnescaped;
        $data['secondLevel']['thirdLevel'] = $this->arrayHtmlAttributeUnescaped;
        $data['secondLevel']['thirdLevel']['fourthLevel'] = $this->arrayHtmlAttributeUnescaped;

        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeHtmlAttribute($data);

        $expected = $this->arrayHtmlAttributeEscaped;
        $expected['secondLevel'] = $this->arrayHtmlAttributeEscaped;
        $expected['secondLevel']['thirdLevel'] = $this->arrayHtmlAttributeEscaped;
        $expected['secondLevel']['thirdLevel']['fourthLevel'] = $this->arrayHtmlAttributeEscaped;

        $this->assertSame($expected, $escaped);
    }

    /**
     * Ensures that some basic HTML characters are properly escaped in a CSS
     * context.
     */
    public function testSingleStringEscapingCssContext() {
        $string = '<>\'"&';
        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeCss($string);
        // @todo Figure out why Zend.Escaper.Escaper
        $expected = '\\3C \\3E \\27 \\22 \\26 ';

        $this->assertSame($expected, $escaped);
    }

    /**
     * Ensures that an array of strings is properly escaped in a CSS context.
     */
    public function testArrayOfStringEscapingCssContext() {
        $data = $this->arrayCssUnescaped;

        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeCss($data);

        $expected = $this->arrayCssEscaped;

        $this->assertSame($expected, $escaped);
    }

    /**
     * Ensures that a nested array of strings is properly escaped in a CSS context.
     */
    public function testNestedArrayOfStringsEscapingCssContext() {
        $data = $this->arrayCssUnescaped;
        $data['secondLevel'] = $this->arrayCssUnescaped;
        $data['secondLevel']['thirdLevel'] = $this->arrayCssUnescaped;
        $data['secondLevel']['thirdLevel']['fourthLevel'] = $this->arrayCssUnescaped;

        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeCss($data);

        $expected = $this->arrayCssEscaped;
        $expected['secondLevel'] = $this->arrayCssEscaped;
        $expected['secondLevel']['thirdLevel'] = $this->arrayCssEscaped;
        $expected['secondLevel']['thirdLevel']['fourthLevel'] = $this->arrayCssEscaped;

        $this->assertSame($expected, $escaped);
    }

    public function testStringEscapingJavaScriptContext() {
        $data = '<,>,\',",&';

        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeJavaScript($data);

        $expected = '\\x3C,\\x3E,\\x27,\\x22,\\x26';
        $this->assertSame($expected, $escaped);
    }

    public function testArrayOfStringsEscapingJavaScriptContext() {
        $data = $this->arrayJavaScriptUnescaped;

        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeJavaScript($data);

        $expected = $this->arrayJavaScriptEscaped;
        $this->assertSame($expected, $escaped);
    }

    public function testNestedArrayOfStringsEscapingJavaScriptContext() {
        $data = $this->arrayJavascriptUnescaped;
        $data['secondLevel'] = $this->arrayJavascriptUnescaped;
        $data['secondLevel']['thirdLevel'] = $this->arrayJavascriptUnescaped;
        $data['secondLevel']['thirdLevel']['fourthLevel'] = $this->arrayJavascriptUnescaped;

        $Escaper = new FireResponder\OutputEscaper('utf-8');
        $escaped = $Escaper->escapeJavaScript($data);

        $expected = $this->arrayJavascriptEscaped;
        $expected['secondLevel'] = $this->arrayJavascriptEscaped;
        $expected['secondLevel']['thirdLevel'] = $this->arrayJavascriptEscaped;
        $expected['secondLevel']['thirdLevel']['fourthLevel'] = $this->arrayJavaScriptEscaped;
        $this->assertSame($expected, $escaped);
    }

}
