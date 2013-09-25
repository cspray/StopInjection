<?php
/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace StopInjection\Command;

use \SpraySole\Command\AbstractCommand;
use SpraySole\Input\Input;
use SpraySole\Output\Output;

class AnalyzeCommand extends AbstractCommand {

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

    }

    public function getDescription() {
        return <<<TEXT
Analyzes a set of one or more files to detect usages of the extract function and to highlight usages that are insecure.
TEXT;
    }

    public function getHelp() {

    }

}
