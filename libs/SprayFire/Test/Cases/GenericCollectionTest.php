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
namespace SprayFire\Test\Cases;

/**
 * @brief
 */
class GenericCollectionTest extends \PHPUnit_Framework_TestCase {

    public function testAddingObjects() {
        $Collection = new \SprayFire\Core\Structure\GenericCollection();

        $this->assertTrue($Collection->isEmpty());

        $ObjectOne = new \SprayFire\Test\Helpers\TestObject();
        $Collection->addObject($ObjectOne);
        $this->assertSame(1, $Collection->count());
        $this->assertFalse($Collection->isEmpty());

        $ObjectTwo = new \SprayFire\Test\Helpers\TestObject();
        $Collection->addObject($ObjectTwo);
        $this->assertSame(2, $Collection->count());

        $this->assertSame(0, $Collection->getIndex($ObjectOne));
        $this->assertSame(1, $Collection->getIndex($ObjectTwo));
        $this->assertTrue($Collection->containsObject($ObjectOne));
    }

    public function testAddingTooManyObjects() {
        $Collection = new \SprayFire\Core\Structure\GenericCollection(2);
        $ObjectOne = new \SprayFire\Test\Helpers\TestObject();
        $ObjectTwo = new \SprayFire\Test\Helpers\TestObject();
        $ObjectThree = new \SprayFire\Test\Helpers\TestObject();
        $ObjectFour = new \SprayFire\Test\Helpers\TestObject();

        $this->assertSame(2, $Collection->getNumberOfBuckets());
        $Collection->addObject($ObjectOne);
        $Collection->addObject($ObjectTwo);
        $this->assertSame(2, \count($Collection));
        $Collection->addObject($ObjectThree);
        $this->assertSame(4, $Collection->getNumberOfBuckets());
        $Collection->addObject($ObjectFour);
        $this->assertSame(3, $Collection->getIndex($ObjectFour));
        $this->assertSame($ObjectFour, $Collection->getObject(3));
    }

    public function testGettingObjectWithStringIndex() {
        $Collection = new \SprayFire\Core\Structure\GenericCollection();
        $exceptionThrown = false;
        try {
            $Collection->getObject('map-key');
        } catch (\InvalidArgumentException $InvalArgExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    public function testGettingIndexOfNonExistentObject() {
        $Collection = new \SprayFire\Core\Structure\GenericCollection();
        $ObjectOne = new \SprayFire\Test\Helpers\TestObject();
        $this->assertFalse($Collection->getIndex($ObjectOne));
    }

    public function testCollectionContainsNonExistentObject() {
        $Collection = new \SprayFire\Core\Structure\GenericCollection();
        $ObjectOne = new \SprayFire\Test\Helpers\TestObject();
        $ObjectTwo = new \SprayFire\Test\Helpers\TestObject();
        $ObjectThree = new \SprayFire\Test\Helpers\TestObject();
        $Collection->addObject($ObjectOne);
        $Collection->addObject($ObjectTwo);
        $this->assertFalse($Collection->containsObject($ObjectThree));
    }

    public function testRemovingAnIndex() {
        $Collection = new \SprayFire\Core\Structure\GenericCollection();
        $ObjectOne = new \SprayFire\Test\Helpers\TestObject();
        $ObjectTwo = new \SprayFire\Test\Helpers\TestObject();
        $Collection->addObject($ObjectOne);
        $Collection->addObject($ObjectTwo);

        $this->assertTrue($Collection->containsObject($ObjectOne));
        $this->assertTrue($Collection->containsObject($ObjectTwo));

        $Collection->removeIndex(0);
        $this->assertFalse($Collection->containsObject($ObjectOne));
        $this->assertSame(1, $Collection->count());
    }

    public function testRemovingAnObject() {
        $Collection = new \SprayFire\Core\Structure\GenericCollection();
        $ObjectOne = new \SprayFire\Test\Helpers\TestObject();
        $ObjectTwo = new \SprayFire\Test\Helpers\TestObject();
        $Collection->addObject($ObjectOne);
        $Collection->addObject($ObjectTwo);

        $this->assertTrue($Collection->containsObject($ObjectOne));
        $this->assertTrue($Collection->containsObject($ObjectTwo));

        $Collection->removeObject($ObjectTwo);
        $this->assertFalse($Collection->containsObject($ObjectTwo));
        $this->assertSame(1, $Collection->count());
    }

}