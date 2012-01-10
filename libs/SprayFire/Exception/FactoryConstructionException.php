<?php

/**
 * @file
 * @brief Holds an exception thrown if the construction of a factory could not be
 * completed.
 */

namespace SprayFire\Exception;

/**
 * @brief Generally the only time this exception is thrown by a SprayFire factory
 * is if a NullObject prototype could not properly be created at time of factory
 * construction.
 *
 * @details
 * We consider the failure to have a NullObject prototype as a logical exception
 * in your app.  You should want a NullObject to be returned by a factory, if you
 * don't specify a prototype for that object there is no way ensure that an object
 * is always created by a factory.
 */
class FactoryConstructionException extends \LogicException {

}