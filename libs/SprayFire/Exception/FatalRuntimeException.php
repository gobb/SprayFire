<?php

/**
 * @file
 * @brief A file holding an exception for situations that should stop execution of
 * SprayFire.
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

namespace SprayFire\Exception;

/**
 * @brief An exception thrown if a fatal error occurs and a 500 Internal Server Error
 * response should be sent.
 *
 * @details
 * By design this exception should not generally be caught.  Instead let the ExceptionHandler
 * object responsible for catching uncaught exceptions properly send the user an
 * error response.
 */
class FatalRuntimeException extends \RuntimeException {

}