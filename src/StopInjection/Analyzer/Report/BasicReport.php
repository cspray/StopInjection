<?php

/**
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Analyzer\Report;

use \StopInjection\Analyzer\Report as SIReport;
use \StopInjection\Analyzer\Usage as SIUsage;
use \StopInjection\Analyzer\Usage\Collection;

abstract class BasicReport implements SIReport {

    private $file;
    private $timestamp;
    /**
     * @property \StopInjection\Analyzer\UsageCollection[]
     */
    private $collections;

    public function __construct($file, $unixTimestamp) {
        $this->file = (string) $file;
        $this->timestamp = (int) $unixTimestamp;
        $this->collections = [
            SIUsage::SECURE_USAGE => new Collection(),
            SIUsage::INSECURE_USAGE => new Collection(),
            SIUsage::SUSCEPTIBLE_USAGE => new Collection()
        ];
    }

    /**
     * Add the usage of a statement or feature that may be a security vulnerability
     *
     * @param \StopInjection\Analyzer\Usage $Usage
     * @return void
     */
    public function addUsage(SIUsage $Usage) {
        $this->collections[$Usage->getSecurityLevel()]->add($Usage);
    }

    /**
     * The absolute path to the file that this Report was generated for
     *
     * @return string
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * The Unix Timestamp for the time that this Report was generated
     *
     * @return integer
     */
    public function getTimestamp() {
        return $this->timestamp;
    }

    /**
     * Get all the Usage for the Report
     *
     * @return \StopInjection\Analyzer\UsageCollection
     */
    public function getAllUsage() {
        $Coll = new Collection();
        foreach($this->collections as $StoredColl) {
            $Coll->merge($StoredColl);
        }
        return $Coll;
    }

    /**
     * Return the Usage that was determined to be secure for the vulnerability
     * being analyzed against.
     *
     * @return \StopInjection\Analyzer\UsageCollection
     */
    public function getSecureUsage() {
        return $this->collections[SIUsage::SECURE_USAGE];
    }

    /**
     * Return the Usage that was determined may be susceptible to attack but does
     * not currently appear to be vulnerable.
     *
     * @return \StopInjection\Analyzer\UsageCollection
     */
    public function getSusceptibleUsage() {
        return $this->collections[SIUsage::SUSCEPTIBLE_USAGE];
    }

    /**
     * Return the Usage that was determined to be very likely vulnerable to malicious
     * attacks.
     *
     * @return \StopInjection\Analyzer\UsageCollection
     */
    public function getInsecureUsage() {
        return $this->collections[SIUsage::INSECURE_USAGE];
    }

}
