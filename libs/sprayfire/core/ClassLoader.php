<?php

/**
 * @file
 * @brief Holds a class used as the framework autoloader, converting a namespaced
 * class to an absolute directory.
 *
 * @details
 * SprayFire is a fully unit-tested, light-weight PHP framework for developers who
 * want to make simple, secure, dynamic website content.
 *
 * SprayFire is released under the Open-Source Initiative MIT license.
 * OSI MIT License <http://www.opensource.org/licenses/mit-license.php>
 *
 * @author Charles Sprayberry cspray at gmail dot com
 * @copyright Copyright (c) 2011, Charles Sprayberry
 */

/**
 * @namespace libs.sprayfire.core
 * @brief Holds parts of the framework that are considered essential for SprayFire
 * to operate.
 *
 * @details
 * The classes and interfaces in this namespace are generally things that work
 * with the file system, load classes and otherwise make sure the framework has
 * a common foundation to work with.  Ultimately you can almost think of this as
 * a "utility" namespace, but ultimately goes into the heart of the framework.
 */
namespace libs\sprayfire\core;
use libs\sprayfire\core\CoreObject as CoreObject;

    /**
     * @brief Responsible for including namespaced framework and application classes,
     * assuming they abide to the rules set forth by the framework.
     */
    class ClassLoader extends CoreObject {

        /**
         * @brief Adds the class's autoloader function to the autoload register.
         *
         * @codeCoverageIgnore
         */
        public function setAutoLoader() {
            \spl_autoload_register(array($this, 'loadClass'));
        }

        /**
         * @brief Include the class based on the fully namespaced \a $className passed.
         *
         * @param $className The namespaced class to load
         */
        private function loadClass($className) {
            $convertedPath = $this->convertNamespacedClassToDirectoryPath($className);
            if (\file_exists($convertedPath)) {
                include $convertedPath;
            }
        }

        /**
         * @brief Converts the PHP namespace separator to the appropriate directory
         * separator.
         *
         * @param $className Namespaced name of the class to load
         * @return The complete path to the class
         */
        private function convertNamespacedClassToDirectoryPath($className) {
            $convertedPath = ROOT_PATH . DS;
            $convertedPath .= \str_replace('\\', DS, $className);
            $convertedPath .= '.php';
            return $convertedPath;
        }

    }

    // End ClassLoader

// libs.sprayfire.core