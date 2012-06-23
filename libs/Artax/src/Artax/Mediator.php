<?php

/**
 * Mediator Interface File
 * 
 * @category   Artax
 * @package    Core
 * @author     Daniel Lowrey <rdlowrey@gmail.com>
 * @copyright  ${copyright.msg}
 * @license    ${license.txt}
 * @version    ${project.version}
 */

namespace Artax;
  
/**
 * Defines the facing interface for event mediators.
 * 
 * @category   Artax
 * @package    Core
 * @author     Daniel Lowrey <rdlowrey@gmail.com>
 */
interface Mediator {
    
    /**
     * Notify listeners that the specified event has occurred
     * 
     * @param string $event The event that occurred
     */
    function notify($event);
    
    /**
     * Iterates through the items in the order they are traversed, adding them
     * to the event queue found in the key.
     *
     * @param mixed $iterable The variable to loop through and add listeners
     */
    function pushAll($iterable);
    
    /**
     * Connect a `$listener` to the end of the `$eventName` queue
     * 
     * @param string $eventName Event identifier name
     * @param mixed  $listener  Event listener
     * @param mixed  $lazyDef   Optional lazy injection definition array
     *                          or instance of ArrayAccess
     */
    function push($eventName, $listener, $lazyDef);
    
    /**
     * Connect a `$listener` to the front of the `$eventName` queue
     * 
     * @param string $eventName Event identifier name
     * @param mixed  $listener  Event listener
     * @param mixed  $lazyDef   Optional lazy injection definition array
     *                          or instance of ArrayAccess
     */
    function unshift($eventName, $listener, $lazyDef);
    
    /**
     * Remove the first `$listener` from the front of the `$eventName` event queue
     * 
     * @param string $eventName Event identifier name
     */
    function shift($eventName);
    
    /**
     * Remove the last `$listener` from the end of the `$eventName` event queue
     * 
     * @param string $eventName Event identifier name
     */
    function pop($eventName);
    
    /**
     * Clear all listeners from the `$eventName` event queue
     * 
     * @param string $eventName Event identifier name
     */
    function clear($eventName);
    
    /**
     * Retrieve a count of all listeners in the queue for a specific event
     * 
     * @param string $eventName Event identifier name
     */
    function count($eventName);
    
    /**
     * Retrieve a list of all event listeners in the queue for an event
     */
    function all($eventName);
    
    /**
     * Retrieve the first event listener in the queue for the specified event
     * 
     * @param string $eventName Event identifier name
     */
    function first($eventName);
    
    /**
     * Retrieve the last event listener in the queue for the specified event
     * 
     * @param string $eventName Event identifier name
     */
    function last($eventName);
    
    /**
     * Retrieve a list of all listened-for events in the queue
     */
    function keys();
    
}
