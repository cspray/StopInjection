<?php
/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace StopInjectionTest\Unit\Parser;

use \StopInjection\Extract\NodeVisitor as ExtractNodeVisitor;

/**
 * @property \PHPParser_Parser $Parser
 * @property \PHPParser_NodeTraverser $Traverser
 */
class NodeVisitorTest extends \PHPUnit_Framework_TestCase {

    private $Parser;
    private $Traverser;

    public function setUp() {
        $this->Parser = new \PHPParser_Parser(new \PHPParser_Lexer());
        $this->Traverser = new \PHPParser_NodeTraverser();
    }

    public function testEnteringNodeThatIsFunctionCallCapturesNode() {
        $code = <<<PHP
<?php extract([]); ?>
PHP;

        $statements = $this->Parser->parse($code);
        $this->Traverser->addVisitor($SgiVisitor = new ExtractNodeVisitor());
        $this->Traverser->traverse($statements);

        $data = [];
        foreach($SgiVisitor->getExtractFunctionNodes() as $Node) {
            /** @var \PHPParser_Node $Node */
            $data[(string) $Node->name] = $Node->getLine();
        }

        $expected = [
            'extract' => 1
        ];

        $this->assertSame($expected, $data);
    }

    public function testEnteringMultipleFunctionCallsOnlyCapturesExtract() {
        $code = <<<PHP
<?php

extract([]);
json_encode([]);
PHP;

        $statements = $this->Parser->parse($code);
        $this->Traverser->addVisitor($SgiVisitor = new ExtractNodeVisitor());
        $this->Traverser->traverse($statements);

        $data = [];
        foreach($SgiVisitor->getExtractFunctionNodes() as $Node) {
            $data[(string) $Node->name] = $Node->getLine();
        }

        $expected = [
            'extract' => 3
        ];

        $this->assertSame($expected, $data);
    }


}
