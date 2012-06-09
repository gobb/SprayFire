<?php

/**
 * @file
 * @brief Holds a PHPUnit test case to confirm the functionality of Container
 */

namespace SprayFire\Test\Cases\Service\FireBox;

class ContainerTest extends \PHPUnit_Framework_TestCase {

    public function setUp() {

    }

    public function testResourceDoesNotExist() {
        $Container = new \SprayFire\Service\FireBox\Container();
        $directoryExist = $Container->doesServiceExist('SprayFire.Util.Directory');
        $this->assertFalse($directoryExist);
    }

    public function tearDown() {

    }

}
