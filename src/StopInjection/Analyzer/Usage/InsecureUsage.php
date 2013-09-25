<?php

/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace StopInjection\Analyzer\Usage;

class InsecureUsage extends AbstractUsage {

    public function getSecurityLevel() {
        return self::INSECURE_USAGE;
    }



} 
