<?php

require_once("data_explorer.php");

class EnergyConsumptionDataExplorer extends DataExplorer {

    /**
     * Subclass constructor calls parent class
     * @param <String> $key The key of the spreadsheet
     */
    function __construct($key="") {

        error_reporting(E_ALL & ~E_NOTICE);
        if($key == "")
            parent::__construct('pyj6tScZqmEd1G8qI4GpZQg'); // the key of the default data
        else
            parent::__construct ($key);
    }

    /**
     * Creates a graphic (png image) from the data and parameters chosen chosen
     * @param <String> $file_name the name of the file
     * @param <String> $title the title of the graphic (optional, if null it will get the title from the spreadsheet)
     * @param <Int> $chartType the type of graphic (by default its 2, i.e., a line graph)
     */
    public function createChart($file_name, $title="", $yAxisTitle="", $chartType=2, $YDataToUse=null) {

        //gets the Y labels from the data
        $yAxis = $this->getYAxisLabels();

        /* Create and populate the pData object */
        $MyData = new pData();

        //adds the Y Data
        $this->filterYData($MyData, $yAxis, $YDataToUse);

        //defines the yAxis title (if it isn't passed in the method it uses the name of the default data)
        if($yAxisTitle == "")
            $MyData->setAxisName(0, "Energy Consumption");
        else
            $MyData->setAxisName(0, $yAxisTitle);
        
        $MyData->addPoints($this->getXAxisLabels(), "Years");
        $MyData->setSerieDescription("Years", "Years");
        $MyData->setAbscissa("Years");
        //DEFINO O EIXO DOS XX COMO DATAS
        //$MyData->setXAxisDisplay(AXIS_FORMAT_DATE);

        /* Create the pChart object */
        $myPicture = new pImage(900, 430, $MyData);

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

        /* Write the chart legend */
        $myPicture->drawLegend(540, 20, array("Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL));

        /* Render the picture (choose the best way) */
        $myPicture->render("pictures/" . $file_name);
    }

    /**
     * Filters the Y axis data (in the energy case, these are the countries)
     * @param <pData> $data a pData object (passed by reference)
     * @param <String[]> $yLabels
     * @param <String[]> $filterData
     */
    private function filterYData(&$data, $yLabels, $filterData=null) {
        if (empty($filterData)) {
            $data->addPoints($this->getDataRow(2), $yLabels[2]);
            $data->addPoints($this->getDataRow(3), $yLabels[3]);
            $data->addPoints($this->getDataRow(4), $yLabels[4]);
        } else {
            /*
             * Esta funcao serve para converter o array de strings para inteiros
             * (Isto teve que ser feito por causa que a funcao quando recebe a
             * string da problemas de memoria)
             */
            $array = array_map(
                            create_function('$value', 'return (int)$value;'),
                            $filterData
            );
            //adds the chosen data to the graphic
            for ($i = 0; $i < count($array); $i++) {
                echo "array[$i] = " . $array[$i] . "<br/>";
                $data->addPoints($this->getDataRow($array[$i]), $yLabels[$array[$i]]);
            }
        }
    }

    /**
     * Draws the chart by passing the object that draws and the type of chart
     * @param <pImage> $mypic
     * @param <integer> $chartType an integer that defines the type of chart (1->bar, 2->line, 3->plot)
     * @return <type>
     */
    private function getChart($mypic, $chartType) {
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