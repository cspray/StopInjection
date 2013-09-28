<?php

/**
 * An interface responsible for turning a \StopInjection\Analyzer\Report into a
 * format suitable for consumption outside the context of the StopInjection library.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Analyzer;

interface ReportPrinter {

    /**
     * Return a representation of the $Report suitable for the type of printer
     * being implemented.
     *
     * @param \StopInjection\Analyzer\Report $Report
     * @return mixed
     */
    public function printReport(Report $Report);

} 
