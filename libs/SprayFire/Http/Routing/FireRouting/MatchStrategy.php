<?php

/**
 * Abstract implementation of \SprayFire\Http\Routing\MatchStrategy that enables
 * framework installation directories to be removed from a request's path.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */
namespace SprayFire\Http\Routing\FireRouting;

use \SprayFire\Http\Routing as SFHttpRouting,
    \SprayFire\StdLib as SFStdLib;

/**
 * @package SprayFire
 * @subpackage Http.Routing.FireRouting
 */
abstract class MatchStrategy extends SFStdLib\CoreObject implements SFHttpRouting\MatchStrategy  {

    /**
     * @property string
     */
    protected $installDirectory;

    /**
     * @param string|null $installDirectory
     */
    public function __construct($installDirectory = null) {
        $this->installDirectory = (string) $installDirectory;
    }

    /**
     * @param string $uriPath
     * @return string
     */
    protected function removeInstallDirectory($uriPath) {
        if (!empty($this->installDirectory)) {
            $uriPath = \preg_replace('#^' . \trim($this->installDirectory, '/ ') . '#', '', \trim($uriPath, '/ '));
        }
        return \trim($uriPath, '/');
    }

}
