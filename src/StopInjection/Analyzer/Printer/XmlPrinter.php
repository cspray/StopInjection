<?php

/**
 * An implementation of StopInjection\Analyzer\ReportPrinter that will create an
 * XML document based off a StopInjection\Analyzer\Report.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Analyzer\Printer;

use StopInjection\Analyzer\Report as SIReport;
use \StopInjection\Analyzer\ReportPrinter as SIReportPrinter;

class XmlPrinter implements SIReportPrinter {

    /**
     * Will return an XML document representing the $Report
     *
     * @param \StopInjection\Analyzer\Report $Report
     * @return string
     */
    public function printReport(SIReport $Report) {
        return 'This printer has not yet been implemented';
    }

}
