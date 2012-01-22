<?php

/**
 * @file
 * @brief
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire repository: http://www.github.com/cspray/SprayFire/
 *
 * SprayFire wiki: http://www.github.com/cspray/SprayFire/wiki/
 *
 * SprayFire API Documentation: http://www.cspray.github.com/SprayFire/
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

namespace SprayFire\Test\Cases\Structure;

/**
 * @brief
 */
class ImmutableStorageTest extends \PHPUnit_Framework_TestCase {

    public function testAddingDataToImmutableStorage() {
        $exceptionThrown = false;
        $data = array('test' => 'one');
        $ImmutableStorage = new \SprayFire\Structure\Storage\ImmutableStorage($data);
        $this->assertSame('one', $ImmutableStorage->test);
        try {
            $ImmutableStorage->something = 'not valid';
        } catch (\SprayFire\Exception\UnsupportedOperationException $UnsupportedOpExc) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

    public function testChangingDataInImmutableStorage() {
        $exceptionThrown = false;
        $data = array('test' => 'one');
        $ImmutableStorage = new \SprayFire\Structure\Storage\ImmutableStorage($data);
        $this->assertSame('one', $ImmutableStorage->test);
        try {
            $ImmutableStorage->test = 'something else';
        } catch (\SprayFire\Exception\UnsupportedOperationException $UnsupportedOpExc) {
            $exceptionThrown = true;
        }

        $this->assertTrue($exceptionThrown);
    }

}