<?php

/**
 * Implementation of SpraySole\Command\AbstractCommand that allows the analysis
 * of a PHP file for extract usage from the command line.
 *
 * ! This class has not yet been implemented !
 *
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Command;

use \SpraySole\Command\AbstractCommand;
use SpraySole\Input\Input;
use SpraySole\Output\Output;

class AnalyzeCommand extends AbstractCommand {

    /**
     * Allows the setting of the SpraySole command to analyze without needing
     * to pass it in constructor.
     */
    public function __construct() {
        parent::__construct([
            'name' => 'analyze'
        ]);
    }

    /**
     * Execute the given Command based on the Input, writing whatever Output is
     * necessary based on any normal processing or errors; return an exit code
     * or 0 if there was no error.
     *
     * @param \SpraySole\Input\Input $Input
     * @param \SpraySole\Output\Output $StdOut
     * @param \SpraySole\Output\Output $StdErr
     * @return integer
     */
    public function execute(Input $Input, Output $StdOut, Output $StdErr) {
        $StdOut->write('This command is not yet implemented', Output::APPEND_NEW_LINE);
    }

    /**
     * @return string
     */
    public function getDescription() {
        return <<<TEXT
Analyzes a set of one or more files to detect usages of the extract function and to highlight usages that are insecure.
TEXT;
    }

    /**
     * @return string
     */
    public function getHelp() {
        return <<<TEXT

TEXT;
    }

}
