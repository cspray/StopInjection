<?php

/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace StopInjection\Analyzer;

use \Countable;
use \JsonSerializable;
use \Serializable;
use \Traversable;

interface UsageCollection extends Countable, Traversable {

    public function add(Usage $Usage);

    public function item($index);

    public function merge(UsageCollection $Collection);

} 
