<?php

/**
 * Interface that represents an HTTP request provided by the user to retrieve a
 * specific resource.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Http;

use \SprayFire\Object as SFObject;

/**
 * It is important to note that implementations of this interface need to return
 * other interfaces in the SprayFire.Http module; please check out the rest of
 * this module if you intend on implementing this interface yourself.
 *
 * @package SprayFire
 * @subpackage Http
 */
interface Request extends SFObject {

    const METHOD_OPTIONS = 'OPTIONS';

    const METHOD_GET = 'GET';

    const METHOD_HEAD = 'HEAD';

    const METHOD_POST = 'POST';

    const METHOD_PUT = 'PUT';

    const METHOD_DELETE = 'DELETE';

    const METHOD_TRACE = 'TRACE';

    /**
     * @return \SprayFire\Http\Uri
     */
    public function getUri();

    /**
     * @return \SprayFire\Http\RequestHeaders
     */
    public function getHeaders();

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @return string
     */
    public function getVersion();

}
