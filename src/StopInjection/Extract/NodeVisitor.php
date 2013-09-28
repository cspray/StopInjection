<?php

/**
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace StopInjection\Extract;


class NodeVisitor extends \PHPParser_NodeVisitorAbstract {

    private $nodes = [];

    public function getExtractFunctionNodes() {
        return $this->nodes;
    }

    public function enterNode(\PHPParser_Node $Node) {
        if ($Node instanceof \PHPParser_Node_Expr_FuncCall) {
            if ($Node->name instanceof \PHPParser_Node_Name && (string) $Node->name === 'extract') {
                $this->nodes[] = $Node;
            }
        }
    }

    public function clear() {
        $this->nodes = [];
    }

} 
