<?php

/**
 * The framework's base implementation of the SprayFire.Http.Uri interface.
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Http;

use \SprayFire\Http\Uri as Uri,
    \SprayFire\CoreObject as CoreObject;

class ResourceIdentifier extends CoreObject implements Uri {

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
     * @param SprayFire.Object $Object
     * @return boolean
     */
    public function equals(\SprayFire\Object $Object) {
        if (!($Object instanceof \SprayFire\Http\Uri)) {
            return false;
        }

        return ((string) $this === (string) $Object);
    }

}