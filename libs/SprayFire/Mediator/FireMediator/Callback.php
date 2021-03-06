<?php

/**
 * Implementation of SprayFire.Mediator.Callback that abstracts away the invoking
 * of various functions.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Mediator\FireMediator;

use \SprayFire\Mediator as SFMediator,
    \SprayFire\StdLib as SFStdLib,
    \InvalidArgumentException as InvalidArgumentException;

/**
 * @package SprayFire
 * @subpackage Mediator.FireMediator
 */
class Callback extends SFStdLib\CoreObject implements SFMediator\Callback {

    /**
     * Holds the name of the event that this function should be invoked for.
     *
     * @property string
     */
    protected $eventName;

    /**
     * Holds the callable function, in any of PHP's valid callable function syntaxes.
     *
     * @property mixed
     */
    protected $function;

    /**
     * If the $function is not callable an exception will be thrown.
     *
     * @param string $eventName
     * @param callable $function
     * @throws \InvalidArgumentException
     */
    public function __construct($eventName, $function) {
        $this->eventName = $eventName;
        if (!\is_callable($function)) {
            throw new SFMediator\Exception\NotCallableCallback('A callable value must be passed to ' . __CLASS__);
        }
        $this->function = $function;
    }

    /**
     * @return string
     */
    public function getEventName() {
        return $this->eventName;
    }

    /**
     * Invokes the $function stored by the callback, passing along the $Event
     * object as the one and only parameter.
     *
     * @param \SprayFire\Mediator\Event $Event
     * @return mixed
     */
    public function invoke(SFMediator\Event $Event) {
        return \call_user_func_array($this->function, [$Event]);
    }

}
