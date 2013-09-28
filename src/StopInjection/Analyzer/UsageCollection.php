<?php

/**
 * Represents a storage to work with a collection of StopInjection\Analyzer\Usage
 *
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Analyzer;

use \Countable;
use \Traversable;

interface UsageCollection extends Countable, Traversable {

    /**
     * @param Usage $Usage
     * @return void
     */
    public function add(Usage $Usage);

    /**
     * @param integer $index
     * @return \StopInjection\Analyzer\Usage
     */
    public function item($index);

    /**
     * Merge the $Collection into this instance; the elements in $Collection should
     * be stored in this instance after this method is called.
     *
     * @param \StopInjection\Analyzer\UsageCollection $Collection
     * @return void
     */
    public function merge(UsageCollection $Collection);

} 
