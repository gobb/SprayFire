<?php

/**
 * Thrown in situations that should stop execution of SprayFire.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Exception;

/**
 * Thrown if a fatal error occurs and a 500 Internal Server Error response should be sent.
 *
 * By design this exception should not generally be caught.  Instead let the ExceptionHandler
 * object responsible for catching uncaught exceptions properly send the user an
 * error response.
 */
class FatalRuntimeException extends \RuntimeException {

}