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
class GenericSetTest extends \PHPUnit_Framework_TestCase {

    public function testAddingValidObjectToCollection() {

        $Set = new \SprayFire\Core\Structure\GenericSet('\\SprayFire\\Test\\Helpers\\TestObject');
        $exceptionThrown = false;
        $Set->addObject(new \SprayFire\Test\Helpers\TestObject());
        try {
            $Set->addObject($Set);
        } catch (\SprayFire\Exception\TypeNotFoundException $TypeExc) {
            $exceptionThrown = true;
        }

        $this->assertSame(1, \count($Set));
        $this->assertTrue($exceptionThrown);

    }

}