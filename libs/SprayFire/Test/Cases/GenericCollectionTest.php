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

        $ObjectOne = new \SprayFire\Test\Helpers\TestObject();
        $Collection->addObject($ObjectOne);
        $this->assertSame(1, $Collection->count());

        $ObjectTwo = new \SprayFire\Test\Helpers\TestObject();
        $Collection->addObject($ObjectTwo);
        $this->assertSame(2, $Collection->count());

        $this->assertSame(0, $Collection->getIndex($ObjectOne));
        $this->assertSame(1, $Collection->getIndex($ObjectTwo));
    }

}