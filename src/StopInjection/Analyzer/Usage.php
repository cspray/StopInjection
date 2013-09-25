<?php

/**
 *
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
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

    public function getSecurityLevel();

    public function getAnalysisDetails();

}
