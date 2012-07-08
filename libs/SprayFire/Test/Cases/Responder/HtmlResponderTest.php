<?php

/**
 * A test to ensure that the HtmlResponder sanitizes data correctly and produces
 * the appropriate output.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Test\Cases\Responder;

class HtmlResponderTest extends \PHPUnit_Framework_TestCase {

    public function testSanitizingHtmlData() {
        $Responder = new \SprayFire\Responder\HtmlResponder();
        $dirtyData = array(
            'var1' => '<script>alert(\'Yo dog, I stole your focus.\');</script>',
            'var2' => 'Some seemingly \'innocent\' text <b>but</b> still has HTML & an &quot;ampersand&quot; in it',
            'var3' => 'Testing that &lt; and &gt; do not get encoded'
        );
        $cleanData = $Responder->sanitizeData($dirtyData);
        $expected = array(
            'var1' => '&lt;script&gt;alert(\'Yo dog, I stole your focus.\');&lt;/script&gt;',
            'var2' => 'Some seemingly innocent text &lt;b&gt;but&lt;/b&gt; still has HTML &amp; an ampersand in it',
            'var3' => 'Testing that &lt; and &gt; do not get encoded'
        );
        $this->assertSame($expected, $cleanData);
    }

}
