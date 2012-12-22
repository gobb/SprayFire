<?php

/**
 * Implementation of SprayFire.Http.Uri provided by the default SprayFire install.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Http\FireHttp;

use \SprayFire\Http as SFHttp,
    \SprayFire\Object as SFObject,
    \SprayFire\CoreObject as SFCoreObject;

/**
 * This implementation is designed to gather information about the HTTP URI from
 * the superglobal $_SERVER variable.
 *
 * This implementation is known to work with Apache servers but may not work on
 * servers that do not properly populate the $_SERVER superglobal with the appropriate
 * values.
 *
 * @package SprayFire
 * @subpackage Http.FireHttp
 */
class Uri extends SFCoreObject implements SFHttp\Uri {

    /**
     * @property string
     */
    protected $authority = '';

    /**
     * @property string
     */
    protected $path = '';

    /**
     * @property int
     */
    protected $port = 80;

    /**
     * @property string
     */
    protected $query = '';

    /**
     * @property string
     */
    protected $scheme = '';

    /**
     * If $_server is null then the $_SERVER superglobal will be used to parse the
     * URI fragments.
     *
     * @param array $_server
     */
    public function __construct(array $_server = null) {
        if (empty($_server)) {
            $_server = $_SERVER;
        }
        $this->parseParameters($_server);
    }

    /**
     * After calling this method all class properties will be properly set to the
     * values, if present, in $_server.
     *
     * @param array $_server
     */
    protected function parseParameters(array $_server) {
        if (isset($_server['REMOTE_PORT'])) {
            $this->port = $_server['REMOTE_PORT'];
        }

        if (isset($_server['HTTP_HOST'])) {
            $this->authority = $_server['HTTP_HOST'] . ':' . $this->port;
        }

        if (isset($_server['QUERY_STRING'])) {
            $this->query = $_server['QUERY_STRING'];
        }

        if (isset($_server['HTTPS']) && \filter_var($_server['HTTPS'], \FILTER_VALIDATE_BOOLEAN)) {
            $this->scheme = 'https';
        } else {
            $this->scheme = 'http';
        }

        if (isset($_server['REQUEST_URI']) && !empty($_server['REQUEST_URI'])) {
            $pathLength = \strpos($_server['REQUEST_URI'], '?');
            if ($pathLength !== false) {
                $this->path = \substr($_server['REQUEST_URI'], 0, $pathLength);
            } else {
                $this->path = $_server['REQUEST_URI'];
            }
        }
    }

    /**
     * @return string
     */
    public function getAuthority() {
        return $this->authority;
    }

    /**
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getScheme() {
        return $this->scheme;
    }

    /**
     * Overridden from SprayFire.CoreObject to ensure that the string version of
     * the parsed HTTP URI is returned and not an object hash code.
     *
     * @return string
     */
    public function __toString() {
        $uri = $this->scheme . '://' . $this->authority . $this->path;
        if (!empty($this->query)) {
            $uri .= '?' . $this->query;
        }
        return $uri;
    }

    /**
     * Overrides \SprayFire\CoreObject to ensure that objects compared to this
     * implementation for equality does not care if they are the same object
     * but whether or not the appropriate stringified URI is the same as the object
     * being compared to.
     *
     * @param \SprayFire\Object $Object
     * @return boolean
     */
    public function equals(SFObject $Object) {
        if (!($Object instanceof SFHttp\Uri)) {
            return false;
        }

        return ((string) $this === (string) $Object);
    }

}
