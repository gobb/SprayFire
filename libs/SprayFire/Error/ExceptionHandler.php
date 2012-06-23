<?php

/**
 * Holds the ExceptionHandler used as a callback for set_exception_handler()
 *
 * @author Charles Sprayberry
 */

namespace SprayFire\Error;

/**
 * Accepts a log, the absolute path to a content replacement file, and headers
 * that should be sent with the request.
 *
 * Will log the appropriate exception information and then include the file set
 * by \a $contentReplacementPath.  In addition, a 500 HTTP status will be returned
 * to the user.  For the interim, it is expected that the content returned is HTML.
 *
 * @uses SprayFire.Logging.Logger
 * @uses SprayFire.Core.Util.CoreObject
 */
class ExceptionHandler extends \SprayFire\CoreObject {

    /**
     * @property SprayFire.Logging.LogOverseer
     */
    protected $Logger;

    /**
     * Complete path to a page that is shown on requests generating 500 HTTP response
     *
     * @property string
     */
    protected $replacePath;

    /**
     * Holds strings to be set with header() before the response is sent.
     *
     * These are set in the order they are given in the constructor
     *
     * @property array
     */
    protected $headers;

    /**
     * @param $Log SprayFire.Loging.LogOverseer to log information about the caught exception
     * @param $contentReplacementPath File path holding content to serve up after the info is logged
     * @param $headers an array of header information to be sent to the user
     */
    public function __construct(\SprayFire\Logging\LogOverseer $LogOverseer, $contentReplacementPath, array $headers = array()) {
        $this->Logger = $LogOverseer;
        $this->replacePath = $contentReplacementPath;
        $this->headers = $headers;
    }

    /**
     * It should be known that after the Exception information is logged the request
     * will have a 500 HTTP status error returned and the passed content will be
     * sent to the user.
     *
     * @param $Exception Exception thrown and not caught
     */
    public function trap($Exception) {
        $this->logExceptionInfo($Exception);
        $this->setHeaders();
        $this->sendContentAndExit();
    }

    /**
     * @param $Exception An exception to log the info of.
     */
    protected function logExceptionInfo(\Exception $Exception) {
        $file = $Exception->getFile();
        $line = $Exception->getLine();
        $message = $Exception->getMessage();
        $logMessage = 'file:=' . $file . '|line:=' . $line . '|message:=' . $message;
        $this->Logger->logEmergency($logMessage, \LOG_EMERG);
    }

    /**
     * Loops through the headers injected into the constructor, sending each one
     * to the user.
     *
     * @see http://www.php.net/manual/en/function.header.php
     */
    protected function setHeaders() {
        foreach ($this->headers as $headerValue) {
            \header($headerValue);
        }
    }

    /**
     * If the content file injected into the constructor could not be found
     * getDefaultMarkup() will be used in its place.
     */
    protected function sendContentAndExit() {
        if (\file_exists($this->replacePath)) {
            include $this->replacePath;
        } else {
            echo $this->getDefaultMarkup();
        }
        exit;
    }

    /**
     * Returns the content to be sent to the user if the content path injected
     * could not properly be included.
     *
     * @return HTML
     */
    protected function getDefaultMarkup() {
        return <<<HTML
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8" />
            <title>SprayFire Fatal Error</title>
        </head>
        <body>
            <h1>Oops, we goofed!</h1>
            <p>Sorry, but it appears that we may be experiencing some unforeseen
            issues!  We apologize we couldn't get you to the content that you wanted
            but please try back again soon!  We'll be sure to be right on this
            and have the site back up as soon as possible!</p>
        </body>
    </html>
HTML;

    }

}