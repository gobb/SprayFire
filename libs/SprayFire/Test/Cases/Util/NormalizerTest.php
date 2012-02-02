<?php

/**
 * @file
 * @brief
 */

namespace SprayFire\Test\Cases\Util;

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

}