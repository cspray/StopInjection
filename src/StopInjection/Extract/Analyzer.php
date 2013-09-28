<?php

/**
 * Concrete implementation of StopInjection\Analyzer\Anzlyer that will deeply inspect
 * the usage of the extract function to determine if any of those uses should be
 * considered vulnerable to code injection.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Extract;

use \StopInjection\Analyzer\Analyzer as SIAnalyzer;
use \StopInjection\Analyzer\Report as SIReport;

class Analyzer implements SIAnalyzer {

    const VERSION = '0.1.0';

    // These constants help determine what analysis details we assign to each usage
    // @todo These constants and the functionality surrounding them need to be refactored into their own module

    const SEC_USING_EXTR_SKIP = 'secure_extr_skip';

    const SUSC_NO_SECOND_PARAMETER = 'susc_no_second_parameter';
    const SUSC_ARRAY_LITERAL_NO_KEYS = 'susc_array_literal_no_keys';

    const SUSC_PARAM_EXTR_OVERWRITE = 'susc_second_param_extr_overwrite';

    const INSEC_ARRAY_LITERAL_VARIABLE_KEYS = 'insec_array_literal_variable_keys';
    const INSEC_ARRAY_LITERAL_SUPERGLOBAL_STRING_KEY = 'insec_array_literal_superglobal_key';
    const INSEC_EXTRACTING_SUPERGLOBAL = 'insec_extracting_superglobal';
    const INSEC_PREFIX_INSUFFICIENT_BLANK = 'insec_prefix_insufficient';
    const INSEC_EXTRACTING_VARIABLE = 'susc_extracting_variable';

    const EXTR_TYPE_OVERWRITE = 'EXTR_OVERWRITE';
    const EXTR_TYPE_PREFIX = 'EXTR_PREFIX';

    /**
     * @property \PHPParser_Parser
     */
    private $Parser;

    /**
     * @property \PHPParser_NodeTraverserInterface
     */
    private $Traverser;

    /**
     * @property \StopInjection\Extract\NodeVisitor
     */
    private $NodeVisitor;

    /**
     * These are listed here to allow detection of using these as actual string
     * literals as extract array keys.
     *
     * @property array
     */
    private $superglobals = ['_SERVER', '_GET', '_POST', '_FILES', '_COOKIE', '_SESSION', '_REQUEST', '_ENV', 'GLOBALS'];

    /**
     * @param \PHPParser_Parser $Parser
     * @param \PHPParser_NodeTraverserInterface $Traverser
     */
    public function __construct(\PHPParser_Parser $Parser, \PHPParser_NodeTraverserInterface $Traverser) {
        $this->Parser = $Parser;
        $this->Traverser = $Traverser;
        $this->NodeVisitor = new NodeVisitor();
    }

    /**
     *
     *
     * @param string $code
     * @param \StopInjection\Analyzer\Report $Report
     */
    public function analyze($code, SIReport $Report) {
        $this->Traverser->addVisitor($this->NodeVisitor);
        $this->Traverser->traverse($this->Parser->parse($code));

        foreach($this->NodeVisitor->getExtractFunctionNodes() as $Node) {
            $config = $this->getFreshConfig();
            if ($this->mayBeSusceptible($Node, $config)) {
                $this->analyzeExtractTarget($Node->args[0], $config);
            } else {
                // @todo this is not accurate; just because it is not susceptible does not mean using EXTR_SKIP
                $config->usage = '\\StopInjection\\Analyzer\\Usage\\SecureUsage';
                $config->details[] = $this->getMessage(self::SEC_USING_EXTR_SKIP);
            }

            $usage = $config->usage;
            $Report->addUsage(new $usage($Node, $config->details));
        }
    }

    /**
     * Returns a stdClass with the properties $usage and $details set to empty
     * default values.
     *
     * We are using a stdClass for config storage because we need to pass it to
     * different methods for various analysis while still keeping a reference to
     * the usage class and the details regarding the analysis. By using an object
     * we don't need to explicitly pass arrays around by reference.
     *
     * @return \stdClass
     */
    private function getFreshConfig() {
        $config = new \stdClass();
        $config->usage = '';
        $config->details = [];
        return $config;
    }

    /**
     * Determines if the arguments passed to the extract may be indicative of a
     * use that is susceptible or insecure; returns true if there is likely a
     * vulnerability present and false if there is not.
     *
     * The details on why we believe the given $Node might be vulnerable will be
     * included in the $details of the $config.
     *
     * @param \PHPParser_Node $Node
     * @param \stdClass $config
     * @return boolean
     */
    private function mayBeSusceptible(\PHPParser_Node $Node, \stdClass $config) {
        if (\count($Node->args) === 1) {
            $config->usage = '\\StopInjection\\Analyzer\\Usage\\SusceptibleUsage';
            $config->details[] = $this->getMessage(self::SUSC_NO_SECOND_PARAMETER);
            return true;
        }

        $extrConst = $Node->args[1]->value->name->parts[0];
        $return = false;
        switch ($extrConst) {
            case self::EXTR_TYPE_OVERWRITE:
                $config->usage = '\\StopInjection\\Analyzer\\Usage\\SusceptibleUsage';
                $config->details[] = $this->getMessage(self::SUSC_PARAM_EXTR_OVERWRITE);
                $return = true;
                break;
            case self::EXTR_TYPE_PREFIX:
                if (empty($Node->args[2]->value->value)) {
                    $config->usage = '\\StopInjection\\Analyzer\\Usage\\InsecureUsage';
                    $config->details[] = $this->getMessage(self::INSEC_PREFIX_INSUFFICIENT_BLANK);
                    $return = true;
                }
                break;
        }

        return $return;
    }

    /**
     * Will do a detailed inspection of the argument being extracted to determine
     * if that value causes a security vulnerability.
     *
     * @param \PHPParser_Node_Arg $Arg
     * @param \stdClass $config
     */
    private function analyzeExtractTarget(\PHPParser_Node_Arg $Arg, \stdClass $config) {
        $Val = $Arg->value;
        if ($Val instanceof \PHPParser_Node_Expr_Variable) {
            $this->analyzeExtractingVariable($Val, $config);
        } elseif ($Val instanceof \PHPParser_Node_Expr_Array) {
            $this->analyzeExtractingArrayLiteral($Val, $config);
        }
    }

    private function analyzeExtractingVariable(\PHPParser_Node_Expr_Variable $Var, $config) {
        $config->details[] = $this->getMessage(self::INSEC_EXTRACTING_VARIABLE);
        $config->usage = '\\StopInjection\\Analyzer\\Usage\\InsecureUsage';

        if (\in_array($Var->name, $this->superglobals)) {
            $config->details[] = $this->getMessage(self::INSEC_EXTRACTING_SUPERGLOBAL, ['superglobal' => $Var->name]);
        }
    }

    /**
     * Will do a detailed inspection of an array literal passed as the first
     * argument to the extract function.
     *
     * @param \PHPParser_Node_Expr_Array $Array
     * @param \stdClass $config
     */
    private function analyzeExtractingArrayLiteral(\PHPParser_Node_Expr_Array $Array, \stdClass $config) {
        if ($config->usage !== '\\StopInjection\\Analyzer\\Usage\\InsecureUsage') {
            $config->usage = '\\StopInjection\\Analyzer\\Usage\\SusceptibleUsage';
        }

        if (!\count($Array->items)) {
            $config->details[] = $this->getMessage(self::SUSC_ARRAY_LITERAL_NO_KEYS);
        } else {
            foreach($Array->items as $ArrayItem) {
                $key = $ArrayItem->key;
                /** @var \PHPParser_Node_Expr_ArrayItem $ArrayItem */
                if ($key instanceof \PHPParser_Node_Expr_Variable) {
                    $config->usage = '\\StopInjection\\Analyzer\\Usage\\InsecureUsage';
                    $config->details[] = $this->getMessage(self::INSEC_ARRAY_LITERAL_VARIABLE_KEYS);
                    break;
                }

                if ($key instanceof \PHPParser_Node_Scalar_String) {
                    if (\in_array($ArrayItem->key->value, $this->superglobals)) {
                        $config->usage = '\\StopInjection\\Analyzer\\Usage\\InsecureUsage';
                        $config->details[] = $this->getMessage(self::INSEC_ARRAY_LITERAL_SUPERGLOBAL_STRING_KEY);
                        break;
                    }
                }
            }
        }
    }

    private function getMessage($status, array $options = []) {
        $options += ['superglobal' => ''];
        \extract($options, \EXTR_SKIP);
        switch ($status) {
            case self::SEC_USING_EXTR_SKIP:
                return 'This usage is secured against superglobal injections by using \EXTR_SKIP!';
            case self::SUSC_NO_SECOND_PARAMETER:
                return 'No second parameter usage; recommended to use \EXTR_SKIP as second argument to extract()';
            case self::SUSC_ARRAY_LITERAL_NO_KEYS:
                return 'This is not vulnerable because it is an array literal with no keys; however it is susceptible to a future vulnerability';
            case self::INSEC_ARRAY_LITERAL_VARIABLE_KEYS:
                return 'An array literal is passed but a variable is used as a key which may lead to user input being extracted as the variable name';
            case self::INSEC_EXTRACTING_VARIABLE:
                return 'A variable is passed to the first argument and this may lead to user input being extracted';
            case self::SUSC_PARAM_EXTR_OVERWRITE:
                return 'The second parameter passed will overwrite set variables; it is recommended that you use \EXTR_SKIP as second argument to extract()';
            case self::INSEC_EXTRACTING_SUPERGLOBAL:
                return "You are extracting a superglobal \${$superglobal} and this is extremely bad practice! Please see /doc/extract/002-extracting-superglobals.md";
            case self::INSEC_ARRAY_LITERAL_SUPERGLOBAL_STRING_KEY:
                return 'An array literal is being extracted but a hard-coded string key is the same value as a superglobal variable';
            case self::INSEC_PREFIX_INSUFFICIENT_BLANK:
                return 'A potentially secure second argument, \\EXTR_PREFIX, was provided but the prefix in the third argument, \'\', is not sufficient to stop superglobal injection';
            default:
                return 'Unknown status';
        }
    }

} 
