<?php
/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace StopInjection\Analyzer;

interface Analyzer {

    public function analyze($code, Report $Report);

} 
