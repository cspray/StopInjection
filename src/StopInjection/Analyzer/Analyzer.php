<?php

/**
 *
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Analyzer;

interface Analyzer {

    /**
     *
     *
     * @param $code
     * @param \StopInjection\Analyzer\Report $Report
     * @return void
     */
    public function analyze($code, Report $Report);

} 
