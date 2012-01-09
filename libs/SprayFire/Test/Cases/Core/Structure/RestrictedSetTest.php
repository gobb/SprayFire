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

namespace SprayFire\Test\Cases\Core\Structure;

/**
 * @brief
 */
class RestrictedSetTest extends \PHPUnit_Framework_TestCase {

    public function testAddingObjects() {
        $Set = new \SprayFire\Structure\Collection\RestrictedSet('\\SprayFire\\Test\\Helpers\\TestObject');
        $this->assertSame(0, $Set->addObject(new \SprayFire\Test\Helpers\TestObject()));
        $this->assertSame(1, $Set->addObject(new \SprayFire\Test\Helpers\TestObject()));
        $this->assertSame(2, $Set->count());
    }

    public function testAddingInvalidObject() {
        $Set = new \SprayFire\Structure\Collection\RestrictedSet('\\SprayFire\\Test\\Helpers\\TestObject');
        $exceptionThrown = false;
        try {
            $Set->addObject($Set);
        } catch (\InvalidArgumentException $InvalArgExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

    public function testCreatingSetWithImproperType() {
        $exceptionThrown = false;
        try {
            $Set = new \SprayFire\Structure\Collection\RestrictedSet('NonExistent');
        } catch (\SprayFire\Exception\TypeNotFoundException $TypeNotFoundExc) {
            $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    }

}