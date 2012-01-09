<?php

/**
 * @file
 * @brief Holds a class that allows for a configuration to be stored by passing
 * a simple array
 */

namespace SprayFire\Config;

/**
 * @brief A SprayFire.Config.Configuration object that expects configuration data
 * to be passed in the form of an array.
 *
 * @details
 * Note that there is no implementing code, everything is handled by SprayFire.Core.Structure.ImmutableStoreage
 *
 * @uses SprayFire.Config.Configuration
 * @uses SprayFire.Core.Structure.ImmutableStorage
 */
class ArrayConfig extends \SprayFire\Structure\Storage\ImmutableStorage implements \SprayFire\Config\Configuration {

}