<?php

/**
 *
 * @author Charles Sprayberry
 * @license Governed by the LICENSE file found in the root directory of this source
 * code
 */

namespace SprayFire\Http\Routing;

abstract class ConfigFallbacks extends \SprayFire\CoreObject {

    const DEFAULT_NAMESPACE = 'SprayFire.Controller.FireController';

    const DEFAULT_CONTROLLER = 'Pages';

    const DEFAULT_ACTION = 'index';

    const DEFAULT_METHOD = 'GET';

    const DEFAULT_STATIC = false;

    const DEFAULT_STATIC_RESPONDER_NAME = 'SprayFire.Responder.HtmlResponder';

    const DEFAULT_STATIC_LAYOUT_PATH = './libs/SprayFire/Responder/html/layout/default.php';

    const DEFAULT_STATIC_TEMPLATE_PATH = './libs/SprayFire/Responder/html/404.php';

}