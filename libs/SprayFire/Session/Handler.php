<?php

/**
 * Interface that provides appropriate callbacks for ext/session::session_set_save_handler
 * and handles the persistence of session data.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Session;

use \SprayFire\Object as SFObject;

/**
 * Implementations of this interface would replace the default session handling
 * provided by PHP ext/session.
 *
 * The primary use case for implementing this interface would be to store session
 * data outside of the normal storage, for example in a database.
 *
 * @package SprayFire
 * @subpackage Session
 *
 * @see http://php.net/manual/en/function.session-set-save-handler.php
 */
interface Handler extends SFObject {

    /**
     * Callback called at end of script execution or when session_write_close()
     * is called; this callback should be added to register_shutdown_function()
     * to ensure the session is properly closed.
     *
     * @return boolean
     */
    public function close();

    /**
     * Callback called to destroy all data associated with the session.
     *
     * Note that this does not completely kill the session but instead removes
     * all data associated with the $sessionId passed.
     *
     * @param string $sessionId
     * @return boolean
     */
    public function destroy($sessionId);

    /**
     * Remove any session data older than $maxLifetime.
     *
     * @param $maxLifetime
     * @return boolean
     */
    public function gc($maxLifetime);

    /**
     * Callback called when the session is started, whether that be automatically
     * due to php.ini configuration or when session_start() is called manually.
     *
     * @param string $savePath
     * @param string $sessionName
     * @return boolean
     */
    public function open($savePath, $sessionName);

    /**
     * Callback called when the session is started but after the Handler::open
     * method is called.
     *
     * This method must always return an appropriately encoded string representing
     * data associated to the passed $sessionId. Although this data may appear to
     * be similar to the data returned from the serialize function it should be
     * in a different format, one specific for session data.
     *
     * @param string $sessionId
     * @return string
     */
    public function read($sessionId);

    /**
     * Callback called when the session should be saved and before the Handler::close
     * callback is invoked.
     *
     * The $sessionData is passed in a session serializable format. When data is read
     * for $sessionId it must be returned in the same format that it was passed to
     * Handler::write.
     *
     * @param string $sessionId
     * @param string $sessionData
     * @return void
     */
    public function write($sessionId, $sessionData);

}
