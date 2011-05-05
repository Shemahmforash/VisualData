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

    /**
     * Draws the chart by passing the object that draws and the type of chart
     * @param <pImage> $mypic
     * @param <integer> $chartType an integer that defines the type of chart (1->bar, 2->line, 3->plot)
     * @return <type>
     */
    protected function getChart($mypic, $chartType) {
        switch ($chartType) {
            case "1": $gchart = $mypic->drawBarChart(array("DisplayPos" => LABEL_POS_INSIDE, "DisplayValues" => FALSE, "Rounded" => TRUE, "Surrounding" => 30));
                break;
            case "2": $gchart = $mypic->drawLineChart();
                break;
            case "3": $gchart = $mypic->drawPlotChart(array("PlotSize" => 1, "PlotBorder" => TRUE, "BorderSize" => 1));
                ;
                break;
            default: $gchart = $mypic->drawLineChart();
        }

        return $gchart;
    }
}

?>
