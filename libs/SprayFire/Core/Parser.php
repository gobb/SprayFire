<?php

/**
 * @file
 * @brief Holds a file that will parse the data from a SprayFire.Core.Parseable
 * object.
 */

namespace SprayFire\Core;

/**
 * @uses SprayFire.Core.Parseable
 */
interface Parser {

    /**
     * @param $ParseableObject An object implementing SprayFire.Core.Parseable
     * @return A parsed form of the data that can be displayed or sent to the user
     */
    public function getParsedData(\SprayFire\Core\Parseable $ParseableObject);

}