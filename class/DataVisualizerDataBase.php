<?php

require_once("DataVisualizer.php");

/**
 * Description of DataVisualizerDataBase
 *
 * @author wanderer
 */
class DataVisualizerDataBase extends DataVisualizer {

    //database data
    private $HOST = "localhost";
    private $DBUSER = "MossUser";
    private $DBPW = "6GWSKB4jLeDXTwnC";
    private $DBname = "MOSS";
    private $connection;
    private $tableName;

    public function __construct($showAverage = false, $tblName="") {
        parent::__construct($showAverage);
        if ($tblName == "") {
            $this->tableName = "ExtremeTemperatureKilled";
        } else {
            $this->tableName = $tblName;
        }

        //does the connection to the db
        $this->initiateDatabase();
        //puts the values from the table in attributes of this class
        $this->getAllDataFromTable();
    }

    /**
     * Connects to the database
     */
    private function initiateDatabase() {
        $this->connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPW);
        mysql_select_db($this->DBname, $this->connection) or die("Couldn't select database");
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

    }

    protected function getYAxisLabels() {

    }

    private function getAllDataFromTable() {
        $query = "SELECT * FROM $this->tableName";
        $result = mysql_query($query) or die("Nao deu para executar o query " . $query . " pq " . mysql_error());

        $i = 0;
        $countries = array();
        while ($row = mysql_fetch_array($result)) {
            /* Push the results of the query in an array */
            $years[] = $row["Years"];


            foreach ($this->getYAxisLabels() as $country) {
                $countries[$country][$i] = $row[$country];
            }


            /* $India[] = $row["India"];
              $Portugal[] = $row["Portugal"];
              $Romania[] = $row["Romania"];
              $USA[] = $row["United States"]; */

            $i++;
        }
        $this->countriesValues = $countries;
        $this->years = $years;
    }

    /**
     * Returns the average of an array
     * @param <type> $a
     * @return <type> 7
     */
    private function getAverage($a) {
        return array_sum($a) / count($a);
    }

}

?>
