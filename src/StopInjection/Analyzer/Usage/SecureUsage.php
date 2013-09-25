<?php
/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace StopInjection\Analyzer\Usage;

class SecureUsage extends AbstractUsage {

    public function getSecurityLevel() {
        return self::SECURE_USAGE;
    }

} 
