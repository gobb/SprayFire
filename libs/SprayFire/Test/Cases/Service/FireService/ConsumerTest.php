<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Test\Cases\Service\FireService;

use \SprayFire\Service\FireService as FireService;

/**
 *
 * @package SprayFireTest
 * @subpackage Cases
 */
class ConsumerTest extends \PHPUnit_Framework_TestCase {

    public function testConsumerAddingAndRetrievingProperService() {
        $ServiceOne = new \stdClass();
        $Consumer = new BaseConsumer();
        $Consumer->giveService('serviceOne', $ServiceOne);
        $this->assertEquals($ServiceOne, $Consumer->service('serviceOne'));
    }

    public function testConsumerAddingServiceNotObject() {
        $invalidService = 'not an object';
        $Consumer = new BaseConsumer();
        $this->setExpectedException('\InvalidArgumentException');
        $Consumer->giveService('serviceOne', $invalidService);
    }

    public function testConsumerGettingServiceNotAdded() {
        $Consumer = new BaseConsumer();
        $this->assertFalse($Consumer->service('doesNotExist'));
    }

    public function testConsumerGettingServiceThroughPropertyAccess() {
        $ServiceOne = new \stdClass();
        $Consumer = new BaseConsumer();
        $Consumer->giveService('ServiceUno', $ServiceOne);
        $this->assertEquals($ServiceOne, $Consumer->ServiceUno);
    }

    public function testConsumerThrowExceptionForSettingThroughMagicGetter() {
        $ServiceOne = new \stdClass();
        $Consumer = new BaseConsumer();
        $Consumer->ServiceUno = $ServiceOne;
        $this->assertEquals($ServiceOne, $Consumer->service('ServiceUno'));
    }

    public function testConsumerHasProperService() {
        $ServiceOne = new \stdClass();
        $Consumer = new BaseConsumer();
        $Consumer->giveService('ServiceUno', $ServiceOne);
        $this->assertTrue(isset($Consumer->ServiceUno));
    }

    public function testConsumerDoesNotHaveProperService() {
        $Consumer = new BaseConsumer();
        $this->assertFalse(isset($Consumer->ServiceUno));
    }

    public function testUnsettingAService() {
        $ServiceOne = new \stdClass();
        $Consumer = new BaseConsumer();
        $Consumer->giveService('ServiceUno', $ServiceOne);
        // we're catching try/block to double ensure that unset service is still
        // accessible.
        $thrown = false;
        $ExpectedService = null;
        try {
            unset($Consumer->ServiceUno);
        } catch(\BadMethodCallException $BadMethodCall) {
            $thrown = true;
            $ExpectedService = $Consumer->service('ServiceUno');
        }
        $this->assertTrue($thrown, 'BadMethodCallException was not thrown on unset');
        $this->assertEquals($ExpectedService, $ServiceOne);
    }



}

class BaseConsumer extends FireService\Consumer {

}
