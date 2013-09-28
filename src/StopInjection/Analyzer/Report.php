<?php

/**
 * An interface representing the information the StopInjection library was able
 * to parse from a supplied file; typically implementations should be highly
 * specific to a targeted vulnerability.
 * 
 * @author  Charles Sprayberry
 * @license See LICENSE in source root
 * @version 0.1.0
 * @since   0.1.0
 */

namespace StopInjection\Analyzer;

interface Report {

    /**
     * Return the name of the report.
     *
     * @return string
     */
    public function getName();

    /**
     * Return a description of what this report
     *
     * @return string
     */
    public function getDescription();

    /**
     * The absolute path to the file that this Report was generated for
     *
     * @return string
     */
    public function getFile();

    /**
     * The Unix Timestamp for the time that this Report was generated
     *
     * @return integer
     */
    public function getTimestamp();

    /**
     * Add the usage of a statement or feature that may be a security vulnerability
     *
     * @param \StopInjection\Analyzer\Usage $Usage
     * @return void
     */
    public function addUsage(Usage $Usage);

    /**
     * Get all the Usage for the Report
     *
     * @return \StopInjection\Analyzer\UsageCollection
     */
    public function getAllUsage();


    /**
     * Return the Usage that was determined to be very likely vulnerable to malicious
     * attacks.
     *
     * @return \StopInjection\Analyzer\UsageCollection
     */
    public function getInsecureUsage();

    /**
     * Return the Usage that was determined to be secure for the vulnerability
     * being analyzed against.
     *
     * @return \StopInjection\Analyzer\UsageCollection
     */
    public function getSecureUsage();

    /**
     * Return the Usage that was determined may be susceptible to attack but does
     * not currently appear to be vulnerable.
     *
     * @return \StopInjection\Analyzer\UsageCollection
     */
    public function getSusceptibleUsage();

} 
