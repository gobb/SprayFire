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
class RestrictedCollectionTest extends \PHPUnit_Framework_TestCase {

    public function testAddingValidObjectsToCollection() {
        $Collection = new \SprayFire\Structure\Collection\RestrictedCollection('SprayFire.Test.Helpers.TestObject');
        $this->assertSame(0, $Collection->addObject(new \SprayFire\Test\Helpers\TestObject));
        $this->assertSame(1, $Collection->count());
    }

    public function testAddingInvalidObjectToCollection() {
        $Collection = new \SprayFire\Structure\Collection\RestrictedCollection('SprayFire.Test.Helpers.TestObject');

        $exceptionThrown = false;
        try {
            $Collection->addObject($Collection);
        } catch (\InvalidArgumentException $InvalArgExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    public function testInvalidTypePassed() {
        $exceptionThrown = false;
        try {
            $Collection = new \SprayFire\Structure\Collection\RestrictedCollection('NonExistentClass');
        } catch (\SprayFire\Exception\TypeNotFoundException $TypeExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

}