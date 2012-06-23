<?php

/**
 * BadListenerException class file
 * 
 * @category   Artax
 * @package    Core
 * @author     Daniel Lowrey <rdlowrey@gmail.com>
 * @copyright  ${copyright.msg}
 * @license    ${license.txt}
 * @version    ${project.version}
 */
 
namespace Artax;
use RuntimeException;

/**
 * Exception thrown on lazy class listener instantiation or invocation failure
 * 
 * @category   Artax
 * @package    Core
 * @author     Daniel Lowrey <rdlowrey@gmail.com>
 */
class BadListenerException extends RuntimeException {}
