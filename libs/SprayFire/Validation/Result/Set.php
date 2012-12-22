<?php

/**
 * Interface that holds a set of results from a call to \SprayFire\Validation\Validator::validate
 *
 * @author  Charles Sprayberry
 * @license Subject to the terms of the LICENSE file in the project root
 * @version 0.1
 * @since   0.1
 */

namespace SprayFire\Validation\Result;

use \SprayFire\Object as SFObject;

/**
 *
 *
 * @package SprayFire
 * @subpackage Validation.Result
 */
interface Set extends SFObject {

    const ALL_RESULTS = 'all';

    const SUCCESSFUL_RESULTS = 'successful';

    const FAILURE_RESULTS = 'failure';

    /**
     * @param \SprayFire\Validation\Result\Result $Result
     */
    public function addResult(Result $Result);

    /**
     * @param string $field
     * @param string $resultType
     * @return array
     */
    public function getResultsByFieldName($field, $resultType = Set::ALL_RESULTS);

    /**
     * @return array
     */
    public function getSuccessfulResults();

    /**
     * @return array
     */
    public function getFailedResults();

    /**
     * @return boolean
     */
    public function hasFailedResults();

    /**
     * @param string $resultType
     * @return int
     */
    public function count($resultType = Set::ALL_RESULTS);

}
