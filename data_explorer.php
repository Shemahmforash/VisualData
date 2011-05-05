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

// DataExplorer Class
class DataExplorer {

    private $key = null;
    private $spreadSheetService = null;

    protected  $showAverage;

    /**
     *
     * @param <type> $spreadSheetKey
     */
    function __construct($spreadSheetKey, $showAverage = false) {
        $this->key = $spreadSheetKey;

        $this->spreadSheetService = new Zend_Gdata_Spreadsheets(new Zend_Http_Client());

        $this->showAverage = $showAverage;
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
     * Gets the title of the spreadsheet from row1, collumn1
     * @return <type>
     */
    public function getTitle() {
        $data = $this->_getSpreadSheetData(1, 1, 1, 1);
        return $data[1][1];
    }

    /**
     * Gets the X axis Labels, which are present in the first row (from the 2nd collumn on)
     * @return <type>
     */
    public function getXAxisLabels() {
        $data = $this->_getSpreadSheetData(2, null, 1, 1);
        return $data[1];
    }

    /**
     *
     * @return <type>
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
     *
     * @param <type> $row_number
     * @return <type> 
     */
    public function getDataRow($row_number) {
        $data = $this->_getSpreadSheetData(2, null, $row_number, $row_number);
        return $data[$row_number];
    }

}
