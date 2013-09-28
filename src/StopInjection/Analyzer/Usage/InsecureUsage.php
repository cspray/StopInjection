<?php

/**
 *
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Analyzer\Usage;

class InsecureUsage extends AbstractUsage {

    /**
     * @return string
     */
    public function getSecurityLevel() {
        return self::INSECURE_USAGE;
    }



} 
