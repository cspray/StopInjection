<?php
/**
 * 
 * @author Charles Sprayberry
 * @license See LICENSE in source root
 */

namespace StopInjection\Command;

use \SpraySole\Application;
use \SpraySole\Provider\CommandProvider as SpraySoleProvider;


class CommandProvider implements SpraySoleProvider {

    /**
     * Add the appropriate Command implementations to the Application.
     *
     * @param \SpraySole\Application $App
     * @return void
     */
    public function register(Application $App) {
        $App->addCommand(new AnalyzeCommand());
    }

}
