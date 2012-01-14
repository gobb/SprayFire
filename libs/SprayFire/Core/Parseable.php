<?php

/**
 * @file
 * @brief Holds an interface to be implemented by objects that should have some
 * data or state parsed by a SprayFire.Core.Parser object.
 */

namespace SprayFire\Core;

interface Parseable {

    /**
     * @return A data structure storing the data needing to be parsed
     */
    public function getParseableData();

}