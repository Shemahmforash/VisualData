<?php

require_once("DataVisualizer.php");

/**
 * A class that allows to explore and visualize data from public spreadsheets
 *
 * @author wanderer
 */
class DataVisualizerSpreadSheet extends DataVisualizer {

    /**
     * The key of the publi spreadsheet
     * @var <String> 
     */
    private $key = null;
    private $spreadSheetService = null;

    public function __construct($showAverage = false, $spreadSheetKey = 'pyj6tScZqmEd1G8qI4GpZQg') {
        parent::__construct($showAverage);

        $this->key = $spreadSheetKey;

        $this->spreadSheetService = new Zend_Gdata_Spreadsheets(new Zend_Http_Client());
    }

    /**
     * Creates a graphic (png image) from the data and parameters chosen chosen
     * @param <String> $file_name the name of the file
     * @param <String> $title the title of the graphic (optional, if null it will get the title from the spreadsheet)
     * @param <Int> $chartType the type of graphic (by default its 2, i.e., a line graph)
     */
    protected function createChart($file_name, $title = "", $yAxisTitle = "", $chartType = 2, $yDataToUse = null, $xDataToUse = null) {
        //gets the Y labels from the data
        $yAxis = $this->getYAxisLabels();

        /* Create and populate the pData object */
        $MyData = new pData();

        //adds the Y Data
        $this->filterYData($MyData, $yAxis, $yDataToUse);

        //defines the yAxis title (if it isn't passed in the method it uses the name of the default data)
        if ($yAxisTitle == "")
            $MyData->setAxisName(0, "Energy Consumption");
        else
            $MyData->setAxisName(0, $yAxisTitle);

        //adds the X Data
        //$MyData->addPoints($this->getXAxisLabels(), "Years");
        $this->filterXData($MyData, $this->getXAxisLabels(), $xDataToUse);

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
        }
    }

    /**
     * Filters the Y axis data (in the energy case, these are the countries)
     * @param <pData> $data a pData object (passed by reference)
     * @param <String[]> $yLabels
     * @param <String[]> $filterData
     */
    protected function filterYData(&$data, $yLabels, $filterData = null) {
        if (empty($filterData)) {
            $data->addPoints($this->getDataRow(2), $yLabels[2]);
            $data->addPoints($this->getDataRow(3), $yLabels[3]);
            //$data->addPoints($this->getDataRow(4), $yLabels[4]);


            if ($this->showAverage) {
                //médias
                for ($i = 0; $i < count($this->getDataRow(2)); $i++) {

                    $data->addPoints($data->getSerieAverage($yLabels[2]), "{$yLabels[2]} (Media)");
                    $data->addPoints($data->getSerieAverage($yLabels[3]), "{$yLabels[3]} (Media)");
                    //$data->addPoints($data->getSerieAverage($yLabels[4]), "Media {$yLabels[4]}");
                }
            }
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
            //adds the chosen data to the graphic
            for ($i = 0; $i < count($filterDataInt); $i++) {
                echo "array[$i] = " . $filterDataInt[$i] . "<br/>";
                $data->addPoints($this->getDataRow($filterDataInt[$i]), $yLabels[$filterDataInt[$i]]);
                if ($this->showAverage) {
                    //médias
                    for ($k = 0; $k < count($this->getDataRow($filterDataInt[$i])); $k++) {

                        $data->addPoints($data->getSerieAverage($yLabels[$filterDataInt[$i]]), "{$yLabels[$filterDataInt[$i]]} (Media)");
                    }
                }
            }
        }
    }

    /**
     * Gets the Years
     * @return <String()>
     */
    protected function getXAxisLabels() {
        $data = $this->_getSpreadSheetData(2, null, 1, 1);
        return $data[1];
    }

    /**
     * Gets the names of the countries
     * @return <String()>
     */
    protected function getYAxisLabels() {
        $data = $this->_getSpreadSheetData(1, 1, 2, null);
        // transform a column into an array of labels and make shure the key matches
        // the row number on the spreadsheet
        foreach ($data as $key => $row) {
            $labels[$key] = $row[1];
        }
        return $labels;
    }

    /**
     *
     * @param <type> $minCol
     * @param <type> $maxCol
     * @param <type> $minRow
     * @param <type> $maxRow
     * @return <type>
     */
    private function _getSpreadSheetData($minCol=null, $maxCol=null, $minRow=null, $maxRow=null) {
        $query = new Zend_Gdata_Spreadsheets_CellQuery();
        $query->setSpreadsheetKey($this->key);

        // this is needed for public spreadsheets
        $query->setVisibility('public');
        $query->setProjection('values');

        // range of data to get
        if (is_integer($minCol))
            $query->setMinCol($minCol);
        if (is_integer($maxCol))
            $query->setMaxCol($maxCol);
        if (is_integer($minRow))
            $query->setMinRow($minRow);
        if (is_integer($maxRow))
            $query->setMaxRow($maxRow);

        // get data into a 2 dimensional array
        $feed = $this->spreadSheetService->getCellFeed($query);
        foreach ($feed as $cellEntry) {
            $row = $cellEntry->cell->getRow();
            $col = $cellEntry->cell->getColumn();
            $val = $cellEntry->cell->getText();
            $data[$row][$col] = $val;
        }

        return $data;
    }

    /**
     *
     * @param <type> $row_number
     * @return <type>
     */
    public function getDataRow($row_number) {
        $data = $this->_getSpreadSheetData(2, null, $row_number, $row_number);
        return $data[$row_number];
    }

    /**
     * Gets the title of the spreadsheet from row1, collumn1
     * @return <String>
     */
    public function getTitle() {
        $data = $this->_getSpreadSheetData(1, 1, 1, 1);
        return $data[1][1];
    }

}

?>
