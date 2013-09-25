<?php
/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace StopInjectionTest\Unit\Parser;

use \StopInjection\Analyzer\Report\BasicReport;
use \StopInjection\Extract\Analyzer as ExtractAnalyzer;

/**
 * @property \PHPParser_Parser $Parser
 * @property \PHPParser_NodeTraverser $Traverser
 *
 * @coversDefaultClass \StopInjection\Extract\Analyzer
 */
class AnalyzerTest extends \PHPUnit_Framework_TestCase {

    private $Parser;
    private $Traverser;

    public function setUp() {
        $this->Parser = new \PHPParser_Parser(new \PHPParser_Lexer());
        $this->Traverser = new \PHPParser_NodeTraverser();
    }

    /**
     * @covers ::analyze
     */
    public function testAnalyzeOneSecureUsageWithExtrSkipSecondParameter() {
        $code = <<<'PHP'
<?php

$a = [];
extract($a, \EXTR_SKIP);
PHP;
        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport',['/foo', '0']);
        $Analyzer = new ExtractAnalyzer($this->Parser, $this->Traverser);
        $Analyzer->analyze($code, $Report);

        $this->assertCount(1, $Report->getAllUsage(), 'We did not record exactly 1 usage in the parsed code');
        $this->assertCount(1, $Report->getSecureUsage(), 'We did not record the usage under the appropriate security level');
        $this->assertSame(4, $Report->getSecureUsage()->item(0)->getNode()->getLine(), 'The line for the usage is not on the line we expected');

        $expectedDetails = [
            'This usage is secured against superglobal injections by using \EXTR_SKIP!'
        ];

        $this->assertSame($expectedDetails, $Report->getSecureUsage()->item(0)->getAnalysisDetails(), 'The details of the analysis are not what we expect');
    }

    /**
     * @covers ::analyze
     */
    public function testAnalyzeOneSusceptibleUsageWithArrayLiteralAndNoKeysAndNoSecondParamter() {
        $code = <<<'PHP'
<?php extract([]);
PHP;
        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport',['/foo', '0']);
        $Analyzer = new ExtractAnalyzer($this->Parser, $this->Traverser);
        $Analyzer->analyze($code, $Report);

        $this->assertCount(1, $Report->getAllUsage(), 'We did not record exactly 1 usage in the parsed code');
        $this->assertCount(1, $Report->getSusceptibleUsage(), 'We did not record the usage under the appropriate security level');
        $this->assertSame(1, $Report->getSusceptibleUsage()->item(0)->getNode()->getLine(), 'The line for the usage is not on the line we expected');

        $expectedDetails = [
            'No second parameter usage; recommended to use \EXTR_SKIP as second argument to extract()',
            'This is not vulnerable because it is an array literal with no keys; however it is susceptible to a future vulnerability'
        ];

        $this->assertSame($expectedDetails, $Report->getSusceptibleUsage()->item(0)->getAnalysisDetails(), 'The details of the analysis are not what we expect');
    }

    /**
     * @covers ::analyze
     */
    public function testAnalyzeOneSusceptibleUsageWithArrayLiteralAndNoKeysAndExtrOverwriteSecondParameter() {
        $code = <<<'PHP'
<?php extract([], \EXTR_OVERWRITE);
PHP;
        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport',['/foo', '0']);
        $Analyzer = new ExtractAnalyzer($this->Parser, $this->Traverser);
        $Analyzer->analyze($code, $Report);

        $this->assertCount(1, $Report->getAllUsage(), 'We did not record exactly 1 usage in the parsed code');
        $this->assertCount(1, $Report->getSusceptibleUsage(), 'We did not record the usage under the appropriate security level');
        $this->assertSame(1, $Report->getSusceptibleUsage()->item(0)->getNode()->getLine(), 'The line for the usage is not on the line we expected');

        $expectedDetails = [
            'The second parameter passed will overwrite set variables; it is recommended that you use \EXTR_SKIP as second argument to extract()',
            'This is not vulnerable because it is an array literal with no keys; however it is susceptible to a future vulnerability'
        ];

        $this->assertSame($expectedDetails, $Report->getSusceptibleUsage()->item(0)->getAnalysisDetails(), 'The details of the analysis are not what we expect');
    }

    /**
     * @covers ::analyze
     */
    public function testAnalyzeOneInsecureUsageWithVariableAndNoSecondParamter() {
        $code = <<<'PHP'
<?php

$a = [];
extract($a);
PHP;

        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport',['/foo', '0']);
        $Analyzer = new ExtractAnalyzer($this->Parser, $this->Traverser);
        $Analyzer->analyze($code, $Report);

        $this->assertCount(1, $Report->getAllUsage(), 'We did not record exactly 1 usage in the parsed code');
        $this->assertCount(1, $Report->getInsecureUsage(), 'We did not record the usage under the appropriate security level');
        $this->assertSame(4, $Report->getInsecureUsage()->item(0)->getNode()->getLine(), 'The line for the usage is not on the line we expected');

        $expectedDetails = [
            'No second parameter usage; recommended to use \EXTR_SKIP as second argument to extract()',
            'A variable is passed to the first argument and this may lead to user input being extracted'
        ];
        $this->assertSame($expectedDetails, $Report->getInsecureUsage()->item(0)->getAnalysisDetails(), 'The details of the analysis are not what we expect');
    }

    /**
     * @covers ::analyze
     */
    public function testAnalyzeOneInsecureUsageWithArrayLiteralVariableKeyAndNoSecondParameter() {
        $code = <<<'PHP'
<?php

$a = 'foo';
extract([$a => 'bar']);
PHP;

        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport',['/foo', '0']);
        $Analyzer = new ExtractAnalyzer($this->Parser, $this->Traverser);
        $Analyzer->analyze($code, $Report);

        $this->assertCount(1, $Report->getAllUsage(), 'We did not record exactly 1 usage in the parsed code');
        $this->assertCount(1, $Report->getInsecureUsage(), 'We did not record the usage under the appropriate security level');
        $this->assertSame(4, $Report->getInsecureUsage()->item(0)->getNode()->getLine(), 'The line for the usage is not on the line we expected');

        $expectedDetails = [
            'No second parameter usage; recommended to use \EXTR_SKIP as second argument to extract()',
            'An array literal is passed but a variable is used as a key which may lead to user input being extracted as the variable name'
        ];
        $this->assertSame($expectedDetails, $Report->getInsecureUsage()->item(0)->getAnalysisDetails(), 'The details of the analysis are not what we expect');
    }

    /**
     * @covers ::analyze
     */
    public function testAnalyzingExtractingSuperglobalAddsCodeSmellDetails() {
        $code = <<<'PHP'
<?php

extract($_GET);
PHP;

        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport',['/foo', '0']);
        $Analyzer = new ExtractAnalyzer($this->Parser, $this->Traverser);
        $Analyzer->analyze($code, $Report);

        $this->assertCount(1, $Report->getAllUsage(), 'We did not record exactly 1 usage in the parsed code');
        $this->assertCount(1, $Report->getInsecureUsage(), 'We did not record the usage under the appropriate security level');
        $this->assertSame(3, $Report->getInsecureUsage()->item(0)->getNode()->getLine(), 'The line for the usage is not on the line we expected');

        $expectedDetails = [
            'No second parameter usage; recommended to use \EXTR_SKIP as second argument to extract()',
            'A variable is passed to the first argument and this may lead to user input being extracted',
            'You are extracting a superglobal $_GET and this is extremely bad practice! Please see /doc/extract/002-extracting-superglobals.md'
        ];

        $this->assertSame($expectedDetails, $Report->getInsecureUsage()->item(0)->getAnalysisDetails(), 'The details of the analysis are not what we expect');
    }

    /**
     * @covers ::analyze
     */
    public function testAnalyzingExtractingArrayLiteralWithSuperglobalKeySet() {
        $code = <<<'PHP'
<?php

extract(['_GET' => 'why would you do this?']);
PHP;

        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport',['/foo', '0']);
        $Analyzer = new ExtractAnalyzer($this->Parser, $this->Traverser);
        $Analyzer->analyze($code, $Report);

        $this->assertCount(1, $Report->getAllUsage(), 'We did not record exactly 1 usage in the parsed code');
        $this->assertCount(1, $Report->getInsecureUsage(), 'We did not record the usage under the appropriate security level');
        $this->assertSame(3, $Report->getInsecureUsage()->item(0)->getNode()->getLine(), 'The line for the usage is not on the line we expected');

        $expectedDetails = [
            'No second parameter usage; recommended to use \EXTR_SKIP as second argument to extract()',
            'An array literal is being extracted but a hard-coded string key is the same value as a superglobal variable'
        ];

        $this->assertSame($expectedDetails, $Report->getInsecureUsage()->item(0)->getAnalysisDetails(), '');
    }

    /**
     * @covers ::analyze
     */
    public function testAnalyzingExtrPrefixUsageWithBlankPrefix() {
        $code = <<<'PHP'
<?php

extract([], \EXTR_PREFIX, '');
PHP;

        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport',['/foo', '0']);
        $Analyzer = new ExtractAnalyzer($this->Parser, $this->Traverser);
        $Analyzer->analyze($code, $Report);

        $this->assertCount(1, $Report->getAllUsage(), 'We did not record exactly 1 usage in the parsed code');
        $this->assertCount(1, $Report->getInsecureUsage(), 'We did not record the usage under the appropriate security level');

        $expectedDetails = [
            'A potentially secure second argument, \\EXTR_PREFIX, was provided but the prefix in the third argument, \'\', is not sufficient to stop superglobal injection',
            'This is not vulnerable because it is an array literal with no keys; however it is susceptible to a future vulnerability'
        ];

        $this->assertSame($expectedDetails, $Report->getInsecureUsage()->item(0)->getAnalysisDetails());
    }






}
