<?php

/**
 * @file
 * @brief Holds an interface to be implemented by classes that will take a string
 * and normalize it into a specific format.
 */

namespace SprayFire\Util;

interface Normalizer {

    /**
     * @param $stringToNormalize A string to turn into a normal format
     * @return The normalized version of the parameter passed
     */
    public function normalize($stringToNormalize);

}
