<?php

// Load Zend Class Loader and Zend GData Classes
require_once 'Zend/Loader.php';

Zend_Loader::loadClass('Zend_Gdata_AuthSub');
Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
Zend_Loader::loadClass('Zend_Gdata_Spreadsheets');
Zend_Loader::loadClass('Zend_Gdata_Docs');

include("pchart/class/pData.class.php");
include("pchart/class/pDraw.class.php");
include("pchart/class/pImage.class.php");

/**
 * An abstract class that defines a general class to explore and visualize data from several sources
 *
 * @author wanderer
 */
abstract class DataVisualizer {

    protected $showAverage;

    /**
     * An array containing the arrays
     * @var <array[]>
     */
    protected $years = array();

    function __construct($showAverage = false) {
        $this->showAverage = $showAverage;
    }

    /**
     * Force Extending classes to define these methods
     */    
    abstract protected function getXAxisLabels();

    abstract protected function getYAxisLabels();

    abstract protected function filterYData(&$data, $yLabels, $filterData=null);

    /**
     * Creates a graphic (png image) from the data and parameters chosen chosen
     * @param <String> $file_name the name of the file
     * @param <String> $title the title of the graphic (optional, if null it will get the title from the spreadsheet)
     * @param <String> $yAxisTitle
     * @param <Int> $chartType the type of graphic (by default its 2, i.e., a line graph)
     * @param <String()> $yDataToUse
     * @param <String()> $xDataToUse
     */
    public function createChart($file_name, $title = "", $yAxisTitle = "", $chartType = 2, $yDataToUse = null, $xDataToUse = null) {

        /* Create the pData object */
        $myData = new pData();

        //adds the X Data
        $this->filterXData($myData, $this->getXAxisLabels(), $xDataToUse);

        /* Put the timestamp column on the abscissa axis */
        $myData->setSerieDescription("Years", "Years");
        $myData->setAbscissa("Years");
        //DEFINO O EIXO DOS XX COMO DATAS
        //$myData->setXAxisDisplay(AXIS_FORMAT_DATE);

        //adds the Y Data
        $this->filterYData($myData, $this->getYAxisLabels(), $yDataToUse);

        //defines the yAxis title (if it isn't passed in the method it uses the name of the default data)
        if ($yAxisTitle == "")
            $myData->setAxisName(0, "No. Killed by Extreme Temperatures");
        else
            $myData->setAxisName(0, $yAxisTitle);

        /* Create the pChart object */
        $myPicture = new pImage(900, 430, $myData);

        /* Turn off Antialiasing */
        $myPicture->Antialias = FALSE;

        /* Add a border to the picture */
        $myPicture->drawRectangle(0, 0, 899, 429, array("R" => 0, "G" => 0, "B" => 0));

        /* Write the chart title */
        $myPicture->setFontProperties(array("FontName" => "pchart/fonts/Forgotte.ttf", "FontSize" => 11));

        //when the title isn't passed to the method, it will get the title directly from the spreadsheet
        if ($title == "")
            $title = $this->getTitle();

        $myPicture->drawText(150, 35, $title, array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

        /* Set the default font */
        $myPicture->setFontProperties(array("FontName" => "pchart/fonts/pf_arma_five.ttf", "FontSize" => 6));

        /* Define the chart area */
        $myPicture->setGraphArea(60, 40, 850, 400);

        /* Draw the scale */
        $scaleSettings = array("XMargin" => 10, "YMargin" => 10, "Floating" => TRUE, "GridR" => 200, "GridG" => 200, "GridB" => 200, "DrawSubTicks" => TRUE, "CycleBackground" => TRUE);
        $myPicture->drawScale($scaleSettings);

        /* Turn on Antialiasing */
        $myPicture->Antialias = TRUE;

        /**
         * Draws the graphic given its type (Note: myPicture is passed by reference here)
         */
        $this->getChart($myPicture, $chartType);

        /* $myPicture->setGraphArea(500, 60, 670, 190);
          $myPicture->drawFilledRectangle(480, 170, 650, 200, array("R" => 255, "G" => 255, "B" => 255, "Surrounding" => -200, "Alpha" => 10));
          $myPicture->drawScale(array("XMargin" => 10, "YMargin" => 10, "Pos" => SCALE_POS_LEFTRIGHT, "DrawSubTicks" => TRUE));
          $myPicture->setShadow(TRUE, array("X" => -1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));
          $myPicture->drawLineChart();
          $myPicture->setShadow(FALSE); */


        /* Write the chart legend */
        $myPicture->drawLegend(540, 20, array("Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL));

        /* Render the picture (choose the best way) */
        $myPicture->render("pictures/" . $file_name);
    }

    /**
     * This function filters the data to use in the X axis (usually the years)
     * @param <pData()> $data a pData object (passed by reference)
     * @param <String()> $xLabels the complete labels of the xAxis contained in the spreadsheet
     * @param <String()> $filterData the xLabels to be used in the graphic
     */
    protected function filterXData(&$data, $xLabels, $filterData = null) {
        if (empty($filterData)) {
            $data->addPoints($xLabels, "Years");
        } else {
            /*
             * Esta funcao serve para converter o array de strings para inteiros
             * (Isto teve que ser feito por causa que a funcao quando recebe a
             * string da problemas de memoria)
             */
            $filterDataInt = array_map(
                            create_function('$value', 'return (int)$value;'),
                            $filterData
            );

            $data->addPoints($filterDataInt, "Years");

            $this->years = $filterDataInt;
        }
    }

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
