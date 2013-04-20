<?php

/**
 * Implementation of \SprayFire\Factory\Factory that ensures the proper creation
 * of \SprayFire\Controller\Controller objects.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Controller\FireController;

use \SprayFire\Service,
    \SprayFire\Logging,
    \SprayFire\StdLib,
    \SprayFire\Factory\FireFactory,
    \SprayFire\Service\FireService;


/**
 * Will ensure that a \SprayFire\Controller\Controller is created with the appropriate
 * services added.
 *
 * You may use \SprayFire\Factory\FireFactory\Factory to set the error handling
 * mechanism used, whether to throw an exception or return a Null Object.  By
 * default the type of Null Object returned is \SprayFire\Controller\NullObject.
 * You can change the constructor value for the Null Object type given in
 * install_dir/libs/SprayFire/config/SprayFire/environment.php.
 *
 * @package SprayFire
 * @subpackage Controller.Implementation
 */
class Factory extends FireFactory\Base {

    /**
     * @property \SprayFire\Service\Builder
     */
    protected $Builder;

    /**
     * @param \SprayFire\Service\Builder $Builder
     * @param \SprayFire\StdLib\ReflectionCache $Cache
     * @param \SprayFire\Logging\LogOverseer $LogOverseer
     * @param string $type
     * @param string $nullType
     */
    public function __construct(
        Service\Builder $Builder,
        StdLib\ReflectionCache $Cache,
        Logging\LogOverseer $LogOverseer,
        $type = 'SprayFire.Controller.Controller',
        $nullType = 'SprayFire.Controller.NullObject'
    ) {
        parent::__construct($Cache, $LogOverseer, $type, $nullType);
        $this->Builder = $Builder;
    }

    /**
     * @param string $className
     * @param array $parameters
     * @return \SprayFire\Controller\Controller
     */
    public function makeObject($className, array $parameters = []) {
        return parent::makeObject($className, [$this->Builder]);
    }

}
