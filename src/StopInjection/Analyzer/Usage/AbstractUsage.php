<?php

/**
 * An abstract StopInjection\Analyzer\Usage intended to satisfy the storing of the
 * PHPParser_Node and details about the analysis.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Analyzer\Usage;

use \StopInjection\Analyzer\Usage as SIUsage;

abstract class AbstractUsage implements SIUsage {

    private $Node;
    private $details;

    /**
     * @param \PHPParser_Node $Node
     * @param array $details
     */
    public function __construct(\PHPParser_Node $Node, array $details) {
        $this->Node = $Node;
        $this->details = $details;
    }

    /**
     * Return the node that was found to be using the function or feature that
     * is being analyzed for.
     *
     * @return \PHPParser_Node
     */
    public function getNode() {
        return $this->Node;
    }

    /**
     * Return an indexed array of strings representing the analysis of the Node
     * and why it was given the security level it was given; typically this should
     * include some details on next steps to securing the vulnerability if insecure.
     *
     * @return array
     */
    public function getAnalysisDetails() {
        return $this->details;
    }

}
