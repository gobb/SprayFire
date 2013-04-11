<?php

/**
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFireTest\Service\FireService;

use \SprayFire\Service\FireService as FireService;

/**
 *
 * @package SprayFireTest
 * @subpackage Service.FireService
 */
class ConsumerTest extends \PHPUnit_Framework_TestCase {

    protected function getBaseConsumer() {
        $Builder = $this->getMock('\SprayFire\Service\Builder');
        return new BaseConsumer($Builder);
    }

    public function testConsumerAddingAndRetrievingProperService() {
        $ServiceOne = new \stdClass();
        $Consumer = $this->getBaseConsumer();
        $Consumer->giveService('serviceOne', $ServiceOne);
        $this->assertEquals($ServiceOne, $Consumer->service('serviceOne'));
    }

    public function testConsumerAddingServiceNotObject() {
        $invalidService = 'not an object';
        $Consumer = $this->getBaseConsumer();
        $this->setExpectedException('\InvalidArgumentException');
        $Consumer->giveService('serviceOne', $invalidService);
    }

    public function testConsumerGettingServiceNotAdded() {
        $Consumer = $this->getBaseConsumer();
        $this->assertFalse($Consumer->service('doesNotExist'));
    }

    public function testConsumerGettingServiceThroughPropertyAccess() {
        $ServiceOne = new \stdClass();
        $Consumer = $this->getBaseConsumer();
        $Consumer->giveService('ServiceUno', $ServiceOne);
        $this->assertEquals($ServiceOne, $Consumer->ServiceUno);
    }

    public function testConsumerThrowExceptionForSettingThroughMagicGetter() {
        $ServiceOne = new \stdClass();
        $Consumer = $this->getBaseConsumer();
        $Consumer->ServiceUno = $ServiceOne;
        $this->assertEquals($ServiceOne, $Consumer->service('ServiceUno'));
    }

    public function testConsumerHasProperService() {
        $ServiceOne = new \stdClass();
        $Consumer = $this->getBaseConsumer();
        $Consumer->giveService('ServiceUno', $ServiceOne);
        $this->assertTrue(isset($Consumer->ServiceUno));
    }

    public function testConsumerDoesNotHaveProperService() {
        $Consumer = $this->getBaseConsumer();
        $this->assertFalse(isset($Consumer->ServiceUno));
    }

    public function testUnsettingAService() {
        $ServiceOne = new \stdClass();
        $Consumer = $this->getBaseConsumer();
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
