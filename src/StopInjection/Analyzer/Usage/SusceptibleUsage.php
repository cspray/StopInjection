<?php
/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace StopInjection\Analyzer\Usage;


class SusceptibleUsage extends AbstractUsage {

    public function getSecurityLevel() {
        return self::SUSCEPTIBLE_USAGE;
    }

} 
