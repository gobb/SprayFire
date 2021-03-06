<?php

/**
 * A test of the Normalizer used to convert an HTTP request controller fragment
 * into the appropriate class name.
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFireTest\Http\Routing\FireRouting;

use \SprayFire\Http\Routing\FireRouting as FireRouting,
    \PHPUnit_Framework_TestCase as PHPUnitTestCase;

/**
 * @package SprayFireTest
 * @subpackage Http.Routing.FireRouting
 */
class NormalizerTest extends PHPUnitTestCase {

    /**
     * @property SprayFire.Http.Routing.Normalizer
     */
    protected $Normalizer;

    /**
     * Ensures that only one Normalizer instance is created for all the tests,
     * done because this is really a utility object and does not keep track of
     * any inherent state.
     */
    public function setUp() {
        $this->Normalizer = new FireRouting\Normalizer();
    }

    public function testNormalizingPlainJaneController() {
        $requested = 'blog';
        $expected = 'Blog';
        $actual = $this->Normalizer->normalizeController($requested);

        $this->assertSame($expected, $actual);
    }

    public function testNormalizeCompoundControllerWithUnderscores() {
        $requested = 'blog_post';
        $expected = 'BlogPost';
        $actual = $this->Normalizer->normalizeController($requested);
        $this->assertSame($expected, $actual);
    }

    public function testNormalizeCompoundControllerWithDashes() {
        $requested = 'another-blog-post';
        $expected = 'AnotherBlogPost';
        $actual = $this->Normalizer->normalizeController($requested);
        $this->assertSame($expected, $actual);
    }

    public function testNormalizeControllerWithInvalidCharacters() {
        $requested = 'something*with#invalid_-characters%^';
        $expected = 'SomethingwithinvalidCharacters';
        $actual = $this->Normalizer->normalizeController($requested);
        $this->assertSame($expected, $actual);
    }

    public function testNormalizePlainJaneAction() {
        $requested = 'action';
        $expected = 'action';
        $actual = $this->Normalizer->normalizeAction($requested);
        $this->assertSame($expected, $actual);
    }

    public function testNormalizeActionWithUnderscores() {
        $requested = 'action_perform';
        $expected = 'actionPerform';
        $actual = $this->Normalizer->normalizeAction($requested);
        $this->assertSame($expected, $actual);
    }

    public function testNormalizeActionWithDashes() {
        $requested = 'the-action-requested-was-this-one';
        $expected = 'theActionRequestedWasThisOne';
        $actual = $this->Normalizer->normalizeAction($requested);
        $this->assertSame($expected, $actual);
    }

    public function testNormalizeActionWithInvalidCharacters() {
        $requested = 'this-action*hith#invalid_stuff            ----_%^';
        $expected = 'thisActionhithinvalidStuff';
        $actual = $this->Normalizer->normalizeAction($requested);
        $this->assertSame($expected, $actual);
    }

}
