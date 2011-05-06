<?php

require_once("DataVisualizer.php");

/**
 * A class that allows one to explore and visualize data from public spreadsheets
 *
 * @author wanderer
 */
class DataVisualizerSpreadSheet extends DataVisualizer {

    /**
     * The key of the public spreadsheet
     * @var <String> 
     */
    private $key = null;

    /**
     * The class that allows one to connect to the public spreadsheet
     * @var <Zend_Gdata_Spreadsheets>
     */
    private $spreadSheetService = null;

    public function __construct($showAverage = false, $spreadSheetKey = 'pyj6tScZqmEd1G8qI4GpZQg') {
        //invokes the parent constructor to set the showAverage value
        parent::__construct($showAverage);

        //sets the variables that referr to the spreadsheet
        $this->key = $spreadSheetKey;
        $this->spreadSheetService = new Zend_Gdata_Spreadsheets(new Zend_Http_Client());
    }

    // The following functions assume the spreadsheet data is in the following format:
    //
    //  |----------------+----------------+----------------+-----+----------------|
    //  | Title          | X Axis Label 1 | X Axis Label 2 | ... | X Axis Label n |
    //  |----------------+----------------+----------------+-----+----------------|
    //  | Y Axis Label 1 | value 1,1      | value 1,2      | ... | value 1,n      |
    //  | Y Axis Label 2 | values 2,1     | value 2,2      | ... | values 2,n     |
    //  | ...            | ...            | ...            | ... | ...            |
    //  | Y Axis Label n | value n,1      | value n,2      | ... | value n,3      |
    //  |----------------+----------------+----------------+-----+----------------|
    //

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
                //averages
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
                    //averages
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
    public function getXAxisLabels() {
        $data = $this->_getSpreadSheetData(2, null, 1, 1);
        return $data[1];
    }

    /**
     * Gets the names of the countries
     * @return <String()>
     */
    public function getYAxisLabels() {
        $data = $this->_getSpreadSheetData(1, 1, 2, null);
        // transform a column into an array of labels and make shure the key matches
        // the row number on the spreadsheet
        foreach ($data as $key => $row) {
            $labels[$key] = $row[1];
        }
        return $labels;
    }

    /**
     * Gets the spreadsheet data from a particular set of collumns and rows
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
     * Returns a data row from the spreadsheet
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
