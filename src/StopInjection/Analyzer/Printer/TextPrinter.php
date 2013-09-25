<?php
/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace StopInjection\Analyzer\Printer;

use \StopInjection\Analyzer\Report as SIReport;
use \StopInjection\Analyzer\ReportPrinter as SIReportPrinter;
use \StopInjection\Analyzer\UsageCollection as SIUsageCollection;

class TextPrinter implements SIReportPrinter {

    /**
     * Return a representation of the $Report suitable for the type of printer
     * being implemented.
     *
     * @param \StopInjection\Analyzer\Report $Report
     * @return mixed
     */
    public function printReport(SIReport $Report) {
        return $this->getOutput($Report);
    }

    private function getOutput(SIReport $Report) {
        $out = $this->getFileHeader($Report);
        if (!$Report->getAllUsage()->count()) {
            $out .= <<<TEXT


--- No details to display ---
TEXT;
        } else {
            $out .= $this->getUsageDetails($Report->getSecureUsage(), 'Secure');
            $out .= $this->getUsageDetails($Report->getInsecureUsage(), 'Insecure');
            $out .= $this->getUsageDetails($Report->getSusceptibleUsage(), 'Susceptible');
            $out .= \PHP_EOL;
        }

        $out .= <<<TEXT


End of report
TEXT;


        return $out;
    }


    private function getFileHeader(SIReport $Report) {
        $name = $Report->getName();
        $desc = $Report->getDescription();
        $file = $Report->getFile();
        $time = \date('Y-m-d H:i:s', $Report->getTimestamp());

        $allCount = $Report->getAllUsage()->count();
        $secureCount = $Report->getSecureUsage()->count();
        $insecureCount = $Report->getInsecureUsage()->count();
        $susceptibleCount = $Report->getSusceptibleUsage()->count();

        return <<<TEXT
{$name}
    {$desc}

File analyzed: {$file}
Report run date: {$time}

Total usages found:         {$allCount}
    Secures usages:         {$secureCount}
    Insecure usages:        {$insecureCount}
    Susceptible usages:     {$susceptibleCount}
TEXT;
    }

    private function getUsageDetails(SIUsageCollection $Collection, $name) {
        $out = <<<TEXT



{$name} usages
================================================================================
TEXT;

        if (!$Collection->count()) {
            $out .= <<<TEXT


No usage of this type found
TEXT;
        } else {
            $counter = 1;
            foreach($Collection as $Usage) {
                /** @var \StopInjection\Analyzer\Usage $Usage*/
                $line = $Usage->getNode()->getLine();
                $out .= <<<TEXT


#{$counter} on line {$line}
TEXT;
                foreach($Usage->getAnalysisDetails() as $detail) {
                    $out .= <<<TEXT

- {$detail}
TEXT;
                }
                $counter++;
            }
        }

        return $out;
    }


}
