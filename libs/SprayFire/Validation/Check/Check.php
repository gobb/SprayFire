<?php

/**
 * Interface that all validation rules must implement to run a specific check.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Validation\Check;

use \SprayFire\Object as SFObject;

/**
 * The primary responsibilities for objects implementing this interface are to
 * ensure a value passes a specific rule and to provide a means to retrieve
 * customizable, detailed information on how the value does not match the specific
 * rule.
 *
 * Rule Responsbility
 * -----------------------------------------------------------------------------
 * - Rules should be simple and common
 * - Parameters that may be needed should be passed at construction time or the
 *   module should have a clear, internal API on setting parameters that can be
 *   used before the Check is passed off to a Validator.
 * - Rules should be unaware of the data set field it is checking against.  The
 *   same Check object should be usable on multiple fields.
 *
 * Messages Responsbility
 * -----------------------------------------------------------------------------
 * - Messages should be customizable and there should be at least 2 messages
 *   available for each error code, a message suitable for logging and a message
 *   suitable for display to the user.
 * - Messages should support tokens which can be replaced with various information
 *   from the check. Strongly recommended that if you implement this interface
 *   you take advantage of the MessageParser interface in the same module.
 * - Messages should not be returned formatted.  The array of token values returned
 *   by MessageTokenizable should be specific to the given $value check.
 *
 * @package SprayFire
 * @subpackage Validation.Check
 */
interface Check extends SFObject, MessageTokenizable {

    /**
     * Code that should be returned by passesCheck if there was no error and the
     * value passes the rule check.
     */
    const NO_ERROR = 0;

    /**
     * In logging and display messages this token will be replaced with the value
     * being checked against.
     */
    const VALUE_TOKEN = 'value';

    /**
     * Determine if the $value passes the rule for the given check.
     *
     * An error code should be returned that allows the retrieval of the appropriate
     * error messages. If no error was encountered and the check passes you should
     * return 0. If the check does not pass a value between 1-n should be returned.
     *
     * @param string $value
     * @return integer
     */
    public function passesCheck($value);

    /**
     * Set the log message that should be returned for a given error code.
     *
     * Please see interface level documentation for important information about
     * the $message parameter.
     *
     * @param string $message
     * @param integer $errorCode
     */
    public function setLogMessage($message, $errorCode = null);

    /**
     * Set the display message that is suitable for showing to users for a given
     * error code.
     *
     * Please see interface level documentation for important information about
     * the $message parameter.
     *
     * @param string $message
     * @param integer $errorCode
     */
    public function setDisplayMessage($message, $errorCode = null);

    /**
     * If no $errorCode is passed your check should return some default messages;
     * the messages returned will be formatted with the information from the last
     * call to passesCheck
     *
     * An array with 2 keys, log and display, should be returned holding the unformatted
     * messages.
     *
     * Please see interface level documentation for important information about
     * the message values returned from this method.
     *
     * @param integer $errorCodes
     * @return array
     */
    public function getMessages($errorCode = null);

}