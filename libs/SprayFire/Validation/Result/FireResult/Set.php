<?php

/**
 * Implementation of the SprayFire.Validation.Result.Result interface returned by
 * the SprayFire.Validation.FireValidation.Validator::validate method.
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.2
 * @since   0.1
 */

namespace SprayFire\Validation\Result\FireResult;

use \SprayFire\Validation\Result as SFResult,
    \SprayFire\StdLib;
/**
 *
 *
 * @package SprayFire
 * @subpackage Validation.FireValidation.FireResult
 */
class Set extends StdLib\CoreObject implements SFResult\Set {

    /**
     * Holds the valid \SprayFire\Validation\Result\Result objects added stored
     * against the field for the given Result.
     *
     * @property array
     */
    private $successfulResults = [];

    /**
     * Holds the number of successful results added to the set.
     *
     * This is here to ensure that we can return a proper value from Set::count
     * without having to loop through both successfulResults and failureResults
     * for each call to count.
     *
     * @property integer
     */
    private $numSuccessfulResults = 0;

    /**
     * Holds the invalid \SprayFire\Validation\Result\Result objects added stored
     * against the field for the given Result.
     *
     * @property array
     */
    private $failureResults = [];

    /**
     * Holds the number of failure results added to the set.
     *
     * This is here to ensure that we can return a proper value from Set::count
     * without having to loop through both successfulResults and failureResults
     * for each call to count.
     *
     * @property integer
     */
    private $numFailureResults = 0;

    /**
     * An error message in sprintf format that is used when an improper argument
     * is passed to a given method in this implementation.
     *
     * @property string
     */
    private $errorMessage = 'An invalid argument, %s, was passed to %s, please use defined constants.';

    /**
     * Will add the appropriate $Result stored against its field name based on the
     * validity of the $Result.
     *
     * Will also increment internal counters for $Results added to keep track of
     * the appropriate return value for count()
     *
     * @param \SprayFire\Validation\Result\Result $Result
     */
    public function addResult(SFResult\Result $Result) {
        if ($Result->passedCheck()) {
            $this->addSuccessfulResult($Result);
        } else {
            $this->addFailedResult($Result);
        }
    }

    /**
     * @param \SprayFire\Validation\Result\Result $Result
     */
    protected function addSuccessfulResult(SFResult\Result $Result) {
        $field = (string) $Result->getFieldName();
        if (!isset($this->successfulResults[$field])) {
            $this->successfulResults[$field] = [];
        }
        $this->successfulResults[$field][] = $Result;
        $this->numSuccessfulResults++;
    }

    /**
     * @param \SprayFire\Validation\Result\Result $Result
     */
    protected function addFailedResult(SFResult\Result $Result) {
        $field = (string) $Result->getFieldName();
        if (!isset($this->failureResults[$field])) {
            $this->failureResults[$field] = [];
        }
        $this->failureResults[$field][] = $Result;
        $this->numFailureResults++;
    }

    /**
     * The optional parameter will cause this method to return different value based
     * on whether you are counting all, successful or failed results.
     *
     * Will trigger an error if an invalid parameter is passed and will return
     * -1 count.
     *
     * @param string $resultType
     * @return integer
     */
    public function count($resultType = SFResult\Set::ALL_RESULTS) {
        if ($resultType === SFResult\Set::SUCCESSFUL_RESULTS) {
            return $this->numSuccessfulResults;
        } elseif ($resultType === SFResult\Set::FAILURE_RESULTS) {
            return $this->numFailureResults;
        } elseif ($resultType === SFResult\Set::ALL_RESULTS) {
            return $this->numSuccessfulResults + $this->numFailureResults;
        }
        \trigger_error(\sprintf($this->errorMessage, $resultType, __METHOD__), \E_USER_NOTICE);
        return -1;
    }

    /**
     * Returns a multidimensional array with Result field names as key.
     *
     * @return array
     */
    public function getSuccessfulResults() {
        return $this->successfulResults;
    }

    /**
     * Returns a multidimensional array with Result field names as key.
     *
     * @return array
     */
    public function getFailedResults() {
        return $this->failureResults;
    }

    /**
     * @return boolean
     */
    public function hasFailedResults() {
        return ($this->count(SFResult\Set::FAILURE_RESULTS) > 0);
    }

    /**
     * @param string $field
     * @return array
     */
    protected function getSuccessfulResultsByFieldName($field) {
        if (isset($this->successfulResults[$field])) {
            return $this->successfulResults[$field];
        }
        return [];
    }

    /**
     * @param string $field
     * @return array
     */
    protected function getFailedResultsByFieldName($field) {
        if (isset($this->failureResults[$field])) {
            return $this->failureResults[$field];
        }
        return [];
    }

    /**
     * Will always return an array of results, based on whether you want All,
     * Successful or Failed results.
     *
     * If an invalid value is given to $resultType an error will be triggered and
     * an empty array will be returned.
     *
     * @param string $field
     * @param string $resultType
     * @return array
     */
    public function getResultsByFieldName($field, $resultType = SFResult\Set::ALL_RESULTS) {
        $field = (string) $field;
        if ($resultType === SFResult\Set::SUCCESSFUL_RESULTS) {
            return $this->getSuccessfulResultsByFieldName($field);
        } elseif ($resultType === SFResult\Set::FAILURE_RESULTS) {
            return $this->getFailedResultsByFieldName($field);
        } elseif ($resultType === SFResult\Set::ALL_RESULTS) {
            return \array_merge($this->getSuccessfulResultsByFieldName($field), $this->getFailedResultsByFieldName($field));
        }
        \trigger_error(\sprintf($this->errorMessage, $resultType, __METHOD__), \E_USER_NOTICE);
        return [];
    }

}
