<?php

/**
 * An abstract class that defines a general class to explore and visualize data from several sources
 *
 * @author wanderer
 */
abstract class DataVisualizer {

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
