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

    
    
    protected function createChart($file_name, $title = "", $yAxisTitle = "", $chartType = 2, $yDataToUse = null, $xDataToUse = null) {

    }

    protected function filterXData(&$data, $xLabels, $filterData = null) {

    }

    protected function filterYData(&$data, $yLabels, $filterData = null) {

    }

    protected function getChart($mypic, $chartType) {

    }

    protected function getXAxisLabels() {
        $data = $this->_getSpreadSheetData(2, null, 1, 1);
        return $data[1];
    }

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
     * @return <type>
     */
    public function getTitle() {
        $data = $this->_getSpreadSheetData(1, 1, 1, 1);
        return $data[1][1];
    }


}

?>
