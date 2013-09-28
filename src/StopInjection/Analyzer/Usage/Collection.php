<?php

/**
 * A concrete implementation of the StopInjection\Analyzer\UsageCollection interface.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Analyzer\Usage;

use \StopInjection\Analyzer\Usage as SIUsage;
use \StopInjection\Analyzer\UsageCollection as SIUsageCollection;
use \Iterator;

class Collection implements Iterator, SIUsageCollection {

    /**
     * The actual storage of the Usage instances
     *
     * @property array
     */
    private $collection = [];

    /**
     * The position the iterator is currently pointing at
     *
     * @property integer
     */
    private $pos = 0;

    /**
     * Will return the current Usage element or null if there is not one present
     *
     * @return null|\StopInjection\Analyzer\Usage
     */
    public function current() {
        return $this->collection[$this->pos];
    }

    /**
     * Advances the position to the next element in the collection
     *
     * @return void
     */
    public function next() {
        $this->pos++;
    }

    /**
     * Will return the current position of iterator pointer
     *
     * @return integer
     */
    public function key() {
        return $this->pos;
    }

    /**
     *
     *
     * @return boolean
     */
    public function valid() {
        return $this->pos < $this->count();
    }

    /**
     * Will reset the position of the iterator
     *
     * @return void
     */
    public function rewind() {
        $this->pos = 0;
    }

    /**
     * The number of Usage added to this UsageCollection
     *
     * @return integer
     */
    public function count() {
        return \count($this->collection);
    }

    /**
     * Stores a Usage in the collection.
     *
     * @param \StopInjection\Analyzer\Usage $Usage
     * @return void
     */
    public function add(SIUsage $Usage) {
        $this->collection[] = $Usage;
    }

    /**
     * Returns the Usage added at the given index or null if one does not exist
     * at that index.
     *
     * @param integer $index
     * @return \StopInjection\Analyzer\Usage|null
     */
    public function item($index) {
        $index = (int) $index;
        return isset($this->collection[$index]) ? $this->collection[$index] : null;
    }

    /**
     * @param \StopInjection\Analyzer\UsageCollection $Collection
     * @return void
     */
    public function merge(SIUsageCollection $Collection) {
        foreach($Collection as $Usage) {
            $this->add($Usage);
        }
    }

}
