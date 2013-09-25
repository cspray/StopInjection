<?php
/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace StopInjectionTest\Unit\Analyzer\Printer;

use \StopInjection\Analyzer\Report\BasicReport;
use \StopInjection\Analyzer\Printer\TextPrinter;
use StopInjection\Analyzer\Usage\InsecureUsage;
use StopInjection\Analyzer\Usage\SecureUsage;

/**
 * @coversDefaultClass \StopInjection\Analyzer\Printer\TextPrinter
 */
class TextPrinterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers ::printReport
     */
    public function testPrintingReportWithNoUsagesFound() {
        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport', ['/foo', 0]);
        $Report->expects($this->once())
               ->method('getName')
               ->will($this->returnValue('Foo Analysis Report'));
        $Report->expects($this->once())
               ->method('getDescription')
               ->will($this->returnValue('A report to analyze when you foo with your foo on your foo'));

        $time = \date('Y-m-d H:i:s', 0);
        $expected = <<<TEXT
Foo Analysis Report
    A report to analyze when you foo with your foo on your foo

File analyzed: /foo
Report run date: {$time}

Total usages found:         0
    Secures usages:         0
    Insecure usages:        0
    Susceptible usages:     0

--- No details to display ---

End of report
TEXT;

        $Printer = new TextPrinter();
        $actual = $Printer->printReport($Report);
        $this->assertSame($expected, $actual);
    }

    /**
     * @covers ::printReport
     */
    public function testPrintingReportWithThreeUsagesAllSecure() {
        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport', ['foo', 0]);
        $Report->expects($this->once())
               ->method('getName')
               ->will($this->returnValue('Foo Analysis Report'));
        $Report->expects($this->once())
               ->method('getDescription')
               ->will($this->returnValue('A report to analyze when you foo with your foo on your foo'));

        $NodeOne = $this->getMock('PHPParser_Node');
        $NodeOne->expects($this->once())
                ->method('getLine')
                ->will($this->returnValue(10));
        $detailsOne = [
            'The first detail in first usage',
            'The second detail in first usage'
        ];
        $UsageOne = new SecureUsage($NodeOne, $detailsOne);

        $NodeTwo = $this->getMock('PHPParser_Node');
        $NodeTwo->expects($this->once())
                ->method('getLine')
                ->will($this->returnValue(20));
        $detailsTwo = [
            'The first detail in second usage',
            'The second detail in second usage'
        ];
        $UsageTwo = new SecureUsage($NodeTwo, $detailsTwo);

        $NodeThree = $this->getMock('PHPParser_Node');
        $NodeThree->expects($this->once())
                  ->method('getLine')
                  ->will($this->returnValue(30));
        $detailsThree = [
            'The first detail in third usage',
            'The second detail in third usage'
        ];
        $UsageThree = new SecureUsage($NodeThree, $detailsThree);

        $Report->addUsage($UsageOne);
        $Report->addUsage($UsageTwo);
        $Report->addUsage($UsageThree);

        $time = \date('Y-m-d H:i:s', 0);
        $expected = <<<TEXT
Foo Analysis Report
    A report to analyze when you foo with your foo on your foo

File analyzed: foo
Report run date: {$time}

Total usages found:         3
    Secures usages:         3
    Insecure usages:        0
    Susceptible usages:     0


Secure usages
================================================================================

#1 on line 10
- The first detail in first usage
- The second detail in first usage

#2 on line 20
- The first detail in second usage
- The second detail in second usage

#3 on line 30
- The first detail in third usage
- The second detail in third usage


Insecure usages
================================================================================

No usage of this type found


Susceptible usages
================================================================================

No usage of this type found


End of report
TEXT;

        $Printer = new TextPrinter();
        $actual = $Printer->printReport($Report);
        $this->assertSame($expected, $actual);
    }

    /**
     * @covers ::printReport
     */
    public function testPrintingReportWithThreeSecureAndThreeInsecure() {
        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport', ['foo', 0]);
        $Report->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('Foo Analysis Report'));
        $Report->expects($this->once())
            ->method('getDescription')
            ->will($this->returnValue('A report to analyze when you foo with your foo on your foo'));

        $SecNodeOne = $this->getMock('PHPParser_Node');
        $SecNodeOne->expects($this->once())
            ->method('getLine')
            ->will($this->returnValue(10));
        $secDetailsOne = [
            'The first detail in first usage secure',
            'The second detail in first usage secure'
        ];
        $SecUsageOne = new SecureUsage($SecNodeOne, $secDetailsOne);

        $SecNodeTwo = $this->getMock('PHPParser_Node');
        $SecNodeTwo->expects($this->once())
            ->method('getLine')
            ->will($this->returnValue(20));
        $secDetailsTwo = [
            'The first detail in second usage secure',
            'The second detail in second usage secure'
        ];
        $SecUsageTwo = new SecureUsage($SecNodeTwo, $secDetailsTwo);

        $SecNodeThree = $this->getMock('PHPParser_Node');
        $SecNodeThree->expects($this->once())
            ->method('getLine')
            ->will($this->returnValue(30));
        $secDetailsThree = [
            'The first detail in third usage secure',
            'The second detail in third usage secure'
        ];
        $SecUsageThree = new SecureUsage($SecNodeThree, $secDetailsThree);

        $InsecNodeOne = $this->getMock('PHPParser_Node');
        $InsecNodeOne->expects($this->once())
                     ->method('getLine')
                     ->will($this->returnValue(40));
        $insecDetailsOne = [
            'The first detail in first usage insecure',
            'The second detail in first usage insecure'
        ];
        $InsecUsageOne = new InsecureUsage($InsecNodeOne, $insecDetailsOne);

        $InsecNodeTwo = $this->getMock('PHPParser_Node');
        $InsecNodeTwo->expects($this->once())
                     ->method('getLine')
                     ->will($this->returnValue(50));
        $insecDetailsTwo = [
            'The first detail in second usage insecure',
            'The second detail in second usage insecure'
        ];
        $InsecUsageTwo = new InsecureUsage($InsecNodeTwo, $insecDetailsTwo);

        $InsecNodeThree = $this->getMock('PHPParser_Node');
        $InsecNodeThree->expects($this->once())
                       ->method('getLine')
                       ->will($this->returnValue(60));
        $insecDetailsThree = [
            'The first detail in third usage insecure',
            'The second detail in third usage insecure'
        ];
        $InsecUsageThree = new InsecureUsage($InsecNodeThree, $insecDetailsThree);

        $Report->addUsage($SecUsageOne);
        $Report->addUsage($SecUsageTwo);
        $Report->addUsage($SecUsageThree);
        $Report->addUsage($InsecUsageOne);
        $Report->addUsage($InsecUsageTwo);
        $Report->addUsage($InsecUsageThree);

        $time = \date('Y-m-d H:i:s', 0);
        $expected = <<<TEXT
Foo Analysis Report
    A report to analyze when you foo with your foo on your foo

File analyzed: foo
Report run date: {$time}

Total usages found:         6
    Secures usages:         3
    Insecure usages:        3
    Susceptible usages:     0


Secure usages
================================================================================

#1 on line 10
- The first detail in first usage secure
- The second detail in first usage secure

#2 on line 20
- The first detail in second usage secure
- The second detail in second usage secure

#3 on line 30
- The first detail in third usage secure
- The second detail in third usage secure


Insecure usages
================================================================================

#1 on line 40
- The first detail in first usage insecure
- The second detail in first usage insecure

#2 on line 50
- The first detail in second usage insecure
- The second detail in second usage insecure

#3 on line 60
- The first detail in third usage insecure
- The second detail in third usage insecure


Susceptible usages
================================================================================

No usage of this type found


End of report
TEXT;

        $Printer = new TextPrinter();
        $actual = $Printer->printReport($Report);
        $this->assertSame($expected, $actual);
    }


}
