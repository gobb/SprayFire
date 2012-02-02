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

    public function testNormalizingPlainJaneController() {
        $requested = 'blog';
        $expected = 'Blog';
        $Normalizer = new \SprayFire\Routing\Normalizer();
        $actual = $Normalizer->normalizeController($requested);

        $this->assertSame($expected, $actual);
    }



}