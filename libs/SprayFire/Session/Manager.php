<?php

/**
 * Interface that abstracts away the various session functionality; this is the
 * primary API used by the framework and your applications to manipulate and adjust
 * various session functionality.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Session;

use \SprayFire\Object as SFObject;

/**
 * @package SprayFire
 * @subpackage Session
 */
interface Manager extends SFObject {

    /**
     * Constant passed to Manager::regenerateId if data associated to the previous
     * session ID should be destroyed.
     */
    const DELETE_SESSION_DATA = true;

    /**
     * Constant passed to Manager::regenerateId if data associated to the previous
     * session ID should not be destroyed and associated to the newly created ID.
     */
    const KEEP_SESSION_DATA = false;

    /**
     * Call when the session should be started, makes available the $_SESSION
     * superglobal.
     *
     * @return void
     */
    public function start();

    /**
     * Call when the session should be destroyed
     *
     * The bare minimum of options that should be available include:
     *
     * - expire_cookie (boolean)
     * Determines whether the session cookie should be reset
     *
     * - clear_storage (boolean)
     * Determine whether the storage for the session should be cleared
     *
     * @param array $options
     * @return boolean
     */
    public function destroy(array $options = array());

    /**
     * Return whether or not the session exists and is active.
     *
     * @return boolean
     */
    public function sessionExists();

    /**
     * Manually flush the $_SESSION data to the session handler write callback
     * and close the session, making session storage unavailable for writing.
     *
     * Typically session data is flushed to the write callback when script processing
     * is completed, however it can be manually invoked if you know the session
     * storage does not need to be written to again.
     *
     * @return void
     */
    public function writeClose();

    /**
     * Manually set the session ID to a specific value, return the previous ID for
     * the session.
     *
     * @param string $id
     * @return string
     */
    public function setId($id);

    /**
     * Retrieves the current ID for the given session or null if no session has
     * been started.
     *
     * @return string|null
     */
    public function getId();

    /**
     * Create a new session ID, optionally deleting the data associated to the
     * previous session ID.
     *
     * @param boolean $deleteOldSession
     * @return boolean
     */
    public function regenerateId($deleteOldSession = self::KEEP_SESSION_DATA);

    /**
     * Return the name of the session, this is used in session cookies and when
     * passing session names through the URL.
     *
     * @return string
     */
    public function getSessionName();

    /**
     * Change the name of the session to be $name passed, this new $name will be
     * used for cookies and when passing session information in the URL.
     *
     * The previous session name should be returned.
     *
     * @param $name
     * @return string
     */
    public function setSessionName($name);

}
