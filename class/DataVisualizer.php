<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dataVisualizer
 *
 * @author wanderer
 */
abstract class dataVisualizer {

    protected $showAverage;

    function __construct($showAverage = false) {
        $this->showAverage = $showAverage;
    }

    /**
     * Force Extending classes to define these methods
     */
    abstract protected function createChart($file_name, $title="", $yAxisTitle="", $chartType=2, $yDataToUse=null, $xDataToUse=null);

    abstract protected function getXAxisLabels();

    abstract protected function getYAxisLabels();

    abstract protected function filterYData(&$data, $yLabels, $filterData=null);

    abstract protected function filterXData(&$data, $xLabels, $filterData=null);

    abstract protected function getChart($mypic, $chartType);
}

?>
