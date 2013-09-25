<?php

/**
 * A test to ensure that the StopInjection\Analyzer\Usage\Collection implementation
 * works with Usage objects appropriately.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjectionTest\Unit\Analyzer\Usage;

use \StopInjection\Analyzer\Usage\Collection;

/**
 * @coversDefaultClass \StopInjection\Analyzer\Usage\Collection
 */
class CollectionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @covers ::item
     */
    public function testCollectionRetrievingItemIndexNotExistsReturnsNull() {
        $Collection = new Collection();
        $this->assertNull($Collection->item(0), 'We did not return null for an item index that is not present');
    }

    /**
     * @covers ::item
     * @covers ::add
     */
    public function testCollectionRetrievingItemIndexAfterAddedReturnsAppropriateUsage() {
        $Collection = new Collection();
        $Collection->add($Usage = $this->getMock('\\StopInjection\\Analyzer\\Usage'));

        $this->assertSame($Usage, $Collection->item(0), 'The first index of the Collection is not the expected Usage object');
    }

    /**
     * @covers ::count
     */
    public function testAddingMultipleUsageToCollectionReturnsAppropriateCount() {
        $Collection = new Collection();
        $Usage1 = $Usage2 = $Usage3 = $this->getMock('\\StopInjection\\Analyzer\\Usage');

        $Collection->add($Usage1);
        $Collection->add($Usage2);
        $Collection->add($Usage3);

        $this->assertCount(3, $Collection, 'The Collection did not appropriately count all added Usage');
    }

    /**
     * @covers ::current
     * @covers ::key
     * @covers ::valid
     * @covers ::rewind
     * @covers ::next
     */
    public function testAddingMultipleUsageAndIteratingOverThemIsAppropriate() {
        $Collection = new Collection();
        $Usage1 = $this->getMock('\\StopInjection\\Analyzer\\Usage');
        $Usage2 = $this->getMock('\\StopInjection\\Analyzer\\Usage');
        $Usage3 = $this->getMock('\\StopInjection\\Analyzer\\Usage');

        $Collection->add($Usage1);
        $Collection->add($Usage2);
        $Collection->add($Usage3);

        $data = [];
        foreach($Collection as $key => $Usage) {
            $data[$key] = $Usage;
        }

        $expected = [0 => $Usage1, 1 => $Usage2, 2 => $Usage3];
        $this->assertSame($expected, $data, 'The Collection did not iterate over the added Usage properly');
    }

    /**
     * @covers ::merge
     */
    public function testMergingMultipleCollections() {
        $FooCollection = new Collection();
        $BarCollection = new Collection();
        $BazCollection = new Collection();

        $FooUsage = $this->getMock('\\StopInjection\\Analyzer\\Usage');
        $BarUsage = $this->getMock('\\StopInjection\\Analyzer\\Usage');
        $BazUsage = $this->getMock('\\StopInjection\\Analyzer\\Usage');

        $FooCollection->add($FooUsage);
        $BarCollection->add($BarUsage);
        $BazCollection->add($BazUsage);

        $Collection = new Collection();
        $Collection->merge($FooCollection);
        $Collection->merge($BarCollection);
        $Collection->merge($BazCollection);

        $data = [];
        foreach($Collection as $key => $Usage) {
            $data[$key] = $Usage;
        }

        $expected = [0 => $FooUsage, 1 => $BarUsage, 2 => $BazUsage];
        $this->assertSame($expected, $data, 'The collections were not merged as expected');
    }


}
