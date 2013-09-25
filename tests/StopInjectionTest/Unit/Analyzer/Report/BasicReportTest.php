<?php

/**
 * A test case to ensure that the StopInjection\Analyzer\Report\BasicReport properly
 * stores and retrieves Usages.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace StopInjectionTest\Unit\Analyzer\Report;

use \StopInjection\Analyzer\Usage as SIUsage;
use \StopInjection\Analyzer\Usage\SecureUsage;
use \StopInjection\Analyzer\Usage\SusceptibleUsage;
use \StopInjection\Analyzer\Usage\InsecureUsage;
use \StopInjection\Analyzer\Report\BasicReport;


/**
 * @coversDefaultClass \StopInjection\Analyzer\Report\BasicReport
 */
class BasicReportTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers ::addUsage
     * @covers ::getSecureUsage
     */
    public function testAddingSecureUsageAddsToAppropriateCollection() {
        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport',['/foo', '0']);

        $Usage = $this->getMock('\\StopInjection\\Analyzer\\Usage');
        $Usage->expects($this->once())
              ->method('getSecurityLevel')
              ->will($this->returnValue(SIUsage::SECURE_USAGE));

        $Report->addUsage($Usage);
        $Coll = $Report->getSecureUsage();

        $this->assertInstanceOf('\\StopInjection\\Analyzer\\UsageCollection', $Coll, 'The return value from getSecureUsage is not a collection of Usage types');
        $this->assertCount(1, $Coll, 'Unexpected number of Usage elements present in a collection');
        $this->assertSame($Usage, $Coll->item(0), 'The Usage in the collection is not the Usage we passed in');
    }

    /**
     * @covers ::addUsage
     * @covers ::getInsecureUsage
     */
    public function testAddingInsecureUsageAddsToAppropriateCollection() {
        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport',['/foo', '0']);

        $Usage = $this->getMock('\\StopInjection\\Analyzer\\Usage');
        $Usage->expects($this->once())
              ->method('getSecurityLevel')
              ->will($this->returnValue(SIUsage::INSECURE_USAGE));

        $Report->addUsage($Usage);
        $Coll = $Report->getInsecureUsage();

        $this->assertInstanceOf('\\StopInjection\\Analyzer\\UsageCollection', $Coll, 'The return value from getInsecureUsage is not a collection of Usage types');
        $this->assertCount(1, $Coll, 'Unexpected number of Usage elements present in a collection');
        $this->assertSame($Usage, $Coll->item(0), 'The Usage in the collection is not the Usage we passed in');
    }

    /**
     * @covers ::addUsage
     * @covers ::getInsecureUsage
     */
    public function testAddingSusceptibleUsageAddsToAppropriateCollection() {
        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport',['/foo', '0']);

        $Usage = $this->getMock('\\StopInjection\\Analyzer\\Usage');
        $Usage->expects($this->once())
              ->method('getSecurityLevel')
              ->will($this->returnValue(SIUsage::SUSCEPTIBLE_USAGE));

        $Report->addUsage($Usage);
        $Coll = $Report->getSusceptibleUsage();

        $this->assertInstanceOf('\\StopInjection\\Analyzer\\UsageCollection', $Coll, 'The return value from getSusceptibleUsage is not a collection of Usage types');
        $this->assertCount(1, $Coll, 'Unexpected number of Usage elements present in a collection');
        $this->assertSame($Usage, $Coll->item(0), 'The Usage in the collection is not the Usage we passed in');
    }

    /**
     * @covers ::addUsage
     * @covers ::getAllUsage
     */
    public function testAddingAllThreeTypesGettingAllUsage() {
        $Report = $this->getMockForAbstractClass('\\StopInjection\\Analyzer\\Report\\BasicReport',['/foo', '0']);

        $SecureUsage = new SecureUsage($this->getMock('\\PHPParser_Node'), []);
        $InsecureUsage = new InsecureUsage($this->getMock('\\PHPParser_Node'), []);
        $SusceptibleUsage = new SusceptibleUsage($this->getMock('\\PHPParser_Node'), []);

        $Report->addUsage($SecureUsage);
        $Report->addUsage($InsecureUsage);
        $Report->addUsage($SusceptibleUsage);

        $Coll = $Report->getAllUsage();

        $this->assertInstanceOf('\\StopInjection\\Analyzer\\UsageCollection', $Coll, 'The return value from getAllUsage is not a collection of Usage types');
        $this->assertCount(3, $Coll, 'The all usage collection did not have appropriate elements added');

        $data = [];
        foreach($Coll as $key => $Usage) {
            $data[$key] = $Usage;
        }

        $expected = [0 => $SecureUsage, 1 => $InsecureUsage, 2 => $SusceptibleUsage];
        $this->assertSame($expected, $data, 'The expected Usage were not present in all usage');
    }


}
