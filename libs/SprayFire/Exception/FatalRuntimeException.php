<?php

/**
 * Thrown in situations that should stop execution of SprayFire and your application.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Exception;

/**
 * Thrown if a fatal error occurs and a 500 Internal Server Error response should be sent.
 *
 * By design this exception should not generally be caught.  Instead let the ExceptionHandler
 * object responsible for catching uncaught exceptions properly send the user an
 * error response.
 *
 * @package SprayFire
 * @subpackage Exception
 */
class FatalRuntimeException extends \RuntimeException {}