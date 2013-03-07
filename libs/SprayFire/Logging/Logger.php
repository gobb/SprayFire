<?php

/**
 * Interface to facilitate storing log information to a specific source.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Logging;

use \SprayFire\Object as SFObject;

/**
 * Implementations should be used by SprayFire.Logging.LogOverseer to store information
 * to a specific source.
 *
 * @package SprayFire
 * @subpackage Logging
 */
interface Logger extends SFObject {

    /**
     * @param string $message
     * @param mixed $options
     * @return mixed
     */
    public function log($message, $options = null);

}
