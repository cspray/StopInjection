<?php

/**
 * An implementation of StopInjection\Analyzer\ReportPrinter that will turn a
 * StopInjection\Analyzer\Report into an appropriate JSON structure.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Analyzer\Printer;

use StopInjection\Analyzer\Report as SIReport;
use \StopInjection\Analyzer\ReportPrinter as SIReportPrinter;

/**
 * This implementation uses the StopInjection\Analyzer\Printer\ArrayPrinter to
 * do the bulk of work formatting the Report.
 */
class JsonPrinter implements SIReportPrinter {

    /**
     * Return a representation of the $Report suitable for the type of printer
     * being implemented.
     *
     * @param \StopInjection\Analyzer\Report $Report
     * @return string
     */
    public function printReport(SIReport $Report) {
        return \json_encode('This printer has not yet been implemented');
    }

}
