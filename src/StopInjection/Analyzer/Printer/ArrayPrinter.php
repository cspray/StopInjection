<?php

/**
 * An implementation of StopInjection\Analyzer\ReportPrinter that will turn a
 * StopInjection\Analyzer\Report into a native PHP array structure.
 *
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Analyzer\Printer;

use StopInjection\Analyzer\Report as SIReport;
use \StopInjection\Analyzer\ReportPrinter as SIReportPrinter;

class ArrayPrinter implements SIReportPrinter {

    /**
     * Returns a multidimensional PHP array representing the report; please see
     * class level docs for the exact format of the returned array.
     *
     * @param \StopInjection\Analyzer\Report $Report
     * @return array
     */
    public function printReport(SIReport $Report) {
        return ['This printer has not yet been implemented'];
    }

}
