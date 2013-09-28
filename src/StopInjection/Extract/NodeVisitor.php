<?php

/**
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Extract;

class NodeVisitor extends \PHPParser_NodeVisitorAbstract {

    /**
     * @property array
     */
    private $nodes = [];

    /**
     * @return array
     */
    public function getExtractFunctionNodes() {
        return $this->nodes;
    }

    /**
     * @param \PHPParser_Node $Node
     */
    public function enterNode(\PHPParser_Node $Node) {
        if ($Node instanceof \PHPParser_Node_Expr_FuncCall) {
            if ($Node->name instanceof \PHPParser_Node_Name && (string) $Node->name === 'extract') {
                $this->nodes[] = $Node;
            }
        }
    }

    /**
     *
     */
    public function clear() {
        $this->nodes = [];
    }

} 
