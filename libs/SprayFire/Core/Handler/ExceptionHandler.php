<?php

/**
 * @file
 * @brief A file storing the ExceptionHandler used as a callback for set_exception_handler()
 */

namespace SprayFire\Core\Handler;

/**
 * @brief A class that accepts a log, the absolute path to a content replacement
 * file, and headers that should be sent with the request.
 *
 * @details
 * This class will log the appropriate exception information and then include the
 * file set by the \a $contentReplacementPath injected into the constructor.  In
 * addition, a 500 HTTP status will be returned to the user.  For the interim, it
 * is expected that the content returned is HTML.
 *
 * @uses SprayFire.Logging.Logger
 * @uses SprayFire.Core.Util.CoreObject
 */
class ExceptionHandler extends \SprayFire\Core\Util\CoreObject {

    protected $Logger;

    /**
     * @brief A complete path to a page that should handle 500 requests
     *
     * @details
     * The content of this file, if the file exists, will be included as the content
     * to be shown instead of the normal content for a request.
     *
     * @property $urlPath
     */
    protected $replacePath;

    /**
     * @brief An array holding strings to be set with header() before the response
     * is sent.
     *
     * @property $headers
     */
    protected $headers;

    /**
     * @param $Log SprayFire.Loging.Logger to log information about the caught exception
     * @param $contentReplacementPath File path holding content to serve up after the info is logged
     * @param $headers an array of header information to be sent to the user
     */
    public function __construct(\SprayFire\Logging\Logger $Log, $contentReplacementPath, array $headers = array()) {
        $this->Logger = $Log;
        $this->replacePath = $contentReplacementPath;
        $this->headers = $headers;
    }

    /**
     * @brief It should be known that after the Exception information is logged
     * the request will have a 500 HTTP status error returned and the passed
     * content will be sent to the user.
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
        $this->Logger->log($logMessage);
    }

    /**
     * @brief Will loop through the headers injected into the constructor, sending
     * each one to the user.
     *
     * @see http://www.php.net/manual/en/function.header.php
     */
    protected function setHeaders() {
        foreach ($this->headers as $headerValue) {
            \header($headerValue);
        }
    }

    /**
     * @brief If the content file injected into the constructor could not be found
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
     * @brief This returns the content to be sent to the user if the content path
     * injected could not properly be included.
     *
     * @return HTML markup
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