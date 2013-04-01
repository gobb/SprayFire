<?php

/**
 * 
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.2
 */
namespace SprayFire\Http;

use \SprayFire\Object as SFObject;

/**
 *
 *
 * @package SprayFire
 * @subpackage Http
 */
interface Response extends SFObject {

    const STATUS_100 = 100;
    const STATUS_101 = 101;
    const STATUS_102 = 102;
    const STATUS_200 = 200;
    const STATUS_201 = 201;
    const STATUS_202 = 202;
    const STATUS_203 = 203;
    const STATUS_204 = 204;
    const STATUS_205 = 205;
    const STATUS_206 = 206;
    const STATUS_207 = 207;
    const STATUS_208 = 208;
    const STATUS_226 = 226;
    const STATUS_300 = 300;
    const STATUS_301 = 301;
    const STATUS_302 = 302;
    const STATUS_303 = 303;
    const STATUS_304 = 304;
    const STATUS_305 = 305;
    const STATUS_306 = 306;
    const STATUS_307 = 307;
    const STATUS_308 = 308;
    const STATUS_400 = 400;
    const STATUS_401 = 401;
    const STATUS_402 = 402;
    const STATUS_403 = 403;
    const STATUS_404 = 404;
    const STATUS_405 = 405;
    const STATUS_406 = 406;
    const STATUS_407 = 407;
    const STATUS_408 = 408;
    const STATUS_409 = 409;
    const STATUS_410 = 410;
    const STATUS_411 = 411;
    const STATUS_412 = 412;
    const STATUS_413 = 413;
    const STATUS_414 = 414;
    const STATUS_418 = 418;
    const STATUS_420 = 420;
    const STATUS_422 = 422;
    const STATUS_423 = 423;
    const STATUS_424 = 424;
    const STATUS_425 = 425;
    const STATUS_426 = 426;
    const STATUS_428 = 428;
    const STATUS_429 = 429;
    const STATUS_431 = 431;
    const STATUS_444 = 444;
    const STATUS_449 = 449;
    const STATUS_450 = 450;
    const STATUS_499 = 499;
    const STATUS_500 = 500;
    const STATUS_501 = 501;
    const STATUS_502 = 502;
    const STATUS_503 = 503;
    const STATUS_504 = 504;
    const STATUS_505 = 505;
    const STATUS_506 = 506;
    const STATUS_507 = 507;
    const STATUS_508 = 508;
    const STATUS_509 = 509;
    const STATUS_510 = 510;
    const STATUS_511 = 511;
    const STATUS_598 = 598;
    const STATUS_599 = 599;

    public function setBody($body);

    public function setHeader($header);

    public function setStatusCode($code);

    public function setStatusReason($reason);

    public function getBody();

    public function getHeaders();

    public function getStatusCode();

    public function getStatusReason();

    public function removeHeader($header);

    public function removeAllHeaders();

    public function send();




}
