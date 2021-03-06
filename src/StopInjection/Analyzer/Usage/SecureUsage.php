<?php

/**
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Analyzer\Usage;

class SecureUsage extends AbstractUsage {

    /**
     * @return string
     */
    public function getSecurityLevel() {
        return self::SECURE_USAGE;
    }

} 
