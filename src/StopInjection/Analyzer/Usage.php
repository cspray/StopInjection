<?php

/**
 *
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Analyzer;

interface Usage {

    const SECURE_USAGE = 'secure_extract';
    const SUSCEPTIBLE_USAGE = 'susceptible_extract';
    const INSECURE_USAGE = 'insecure_extract';

    /**
     * @return \PHPParser_Node
     */
    public function getNode();

    /**
     * Should return a string represented by the constants associated to this
     *
     * @return string
     */
    public function getSecurityLevel();

    /**
     * @return array
     */
    public function getAnalysisDetails();

}
