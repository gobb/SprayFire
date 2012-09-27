<?php

/**
 * Implementation of SprayFire.Factory.Factory that ensures the proper creation
 * of SprayFire.Controller.Controller objects.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Controller\FireController;

use \SprayFire\Service as SFService,
    \SprayFire\Logging as SFLogging,
    \SprayFire\Utils as SFUtils,
    \SprayFire\Service\FireService as FireService;


/**
 * Will ensure that a SprayFire.Controller.Controller is created with the appropriate
 * services added.
 *
 * You may use SprayFire.Factory.FireFactory.Factory to set the error handling
 * mechanism used, whether to throw an exception or return a Null Object.  By
 * default the type of Null Object returned is SprayFire.Controller.NullObject.
 * You can change the constructor value for the Null Object type given in
 * install_dir/libs/SprayFire/config/SprayFire/environment.php.
 *
 * @package SprayFire
 * @subpackage Controller.FireController
 */
class Factory extends FireService\ConsumerFactory {

    /**
     * @param SprayFire.Utils.ReflectionCache $Cache
     * @param SprayFire.Service.Container $Container
     * @param SprayFire.Logging.LogOverseer $LogOverseer
     * @param string $type
     * @param string $nullType
     */
    public function __construct(
        SFUtils\ReflectionCache $Cache,
        SFService\Container $Container,
        SFLogging\LogOverseer $LogOverseer,
        $type = 'SprayFire.Controller.Controller',
        $nullType = 'SprayFire.Controller.NullObject'
    ) {
        parent::__construct($Cache, $Container, $LogOverseer, $type, $nullType);
    }

}