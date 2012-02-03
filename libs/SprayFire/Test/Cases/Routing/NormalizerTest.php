<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Cases\Routing;

/**
 * @brief
 */
class NormalizerTest extends \PHPUnit_Framework_TestCase {

    protected $Normalizer;

    public function setUp() {
        if (!$this->Normalizer) {
            $this->Normalizer = new \SprayFire\Routing\Normalizer();
        }
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