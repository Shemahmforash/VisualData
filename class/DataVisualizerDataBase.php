<?php

require_once("DataVisualizer.php");

/**
 * A class that allows on to read, filter and visualize data from database tables.
 *
 * @author wanderer
 */
class DataVisualizerDataBase extends DataVisualizer {

    //The variables to connect to the database
    private $HOST = "localhost";
    private $DBUSER = "MossUser";
    private $DBPW = "6GWSKB4jLeDXTwnC";
    private $DBname = "MOSS";
    private $connection;
    private $tableName;
    
    /**
     * An array containing the values for each country
     * @var <array[country][i]>
     */
    private $countriesValues = array();

    public function __construct($showAverage = false, $tblName="") {
        //invoes the parent constructor
        parent::__construct($showAverage);
        if ($tblName == "") {
            $this->tableName = "ExtremeTemperatureKilled";
        } else {
            $this->tableName = $tblName;
        }

        //does the connection to the db
        $this->initiateDatabase();

        //puts the values from the table in attributes of this class
        $this->getAllDataFromTable($filterData);
    }

    /**
     * Connects to the database
     */
    private function initiateDatabase() {
        $this->connection = mysql_connect($this->HOST, $this->DBUSER, $this->DBPW);
        mysql_select_db($this->DBname, $this->connection) or die("Couldn't select database");
    }

    /**
     * Filters the Y axis data (in the energy case, these are the countries)
     * @param <pData> $data a pData object (passed by reference)
     * @param <String[]> $yLabels
     * @param <String[]> $filterData
     */
    protected function filterYData(&$data, $yLabels, $filterData = null) {

        //refreshes the countriesValues, i.e., fills only the data corresponding to the selected years
        $this->refreshCountriesValuesFromTable($filterData);

        if (empty($filterData)) {
            $data->addPoints($this->countriesValues[0], $yLabels[0]);
            $data->addPoints($this->countriesValues[1], $yLabels[1]);
            if ($this->showAverage) {
                $average0 = $this->getAverage($this->countriesValues[0]);
                $average1 = $this->getAverage($this->countriesValues[1]);
                for ($i = 0; $i < count($this->countriesValues[0]); $i++) {
                    $data->addPoints($average0, "{$yLabels[0]} (Media)");
                    $data->addPoints($average0, "{$yLabels[0]} (Media)");
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
                echo "array[$i] = " . $filterDataInt[$i] . " ; countriesValue = " . $this->countriesValues[$filterDataInt[$i]] . "<br/>";

                //preciso de usar $yLabels[$filterDataInt[$i]], pois as keys do countryValues são mesmo países, e aquilo q vem no filtro são indices do array, desta forma converto indice para nome de país
                $data->addPoints($this->countriesValues[$yLabels[$filterDataInt[$i]]], $yLabels[$filterDataInt[$i]]);
                if ($this->showAverage) {
                    for ($k = 0; $k < count($this->countriesValues[$yLabels[$filterDataInt[$i]]]); $k++) {
                        $data->addPoints($this->getAverage($this->countriesValues[$yLabels[$filterDataInt[$i]]]), "{$yLabels[$filterDataInt[$i]]} (Media)");
                    }
                }
            }
        }
    }

    /**
     * Gets the Years from the table
     * @return <type>
     */
    public function getXAxisLabels() {
        $query = "SELECT DISTINCT(Years) FROM ExtremeTemperatureKilled";
        $result = mysql_query($query) or die("Nao deu para executar o query " . $query . " pq " . mysql_error());

        $xLabels = array();
        while ($row = mysql_fetch_array($result)) {
            $xLabels[] = $row['Years'];
        }

        return $xLabels;
    }

    /**
     * Gets the names of the countries
     * @return <type>
     */
    public function getYAxisLabels() {
        $query = "SHOW COLUMNS FROM $this->tableName";
        $result = mysql_query($query) or die("Nao deu para executar o query " . $query . " pq " . mysql_error());
        $yLabels = array();
        while ($row = mysql_fetch_array($result)) {
            if ($row['Field'] != 'id' && $row['Field'] != 'Years')
                $yLabels[] = $row['Field'];
        }
        return $yLabels;
    }

    /**
     * Fills the attributes of the class from the data in the table chosen
     */
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

            $i++;
        }
        $this->countriesValues = $countries;
        $this->years = $years;
    }

    private function refreshCountriesValuesFromTable($filter = null) {


        $this->countriesValues = null;
        if ($filter == null) {
            $this->getAllDataFromTable();
        } else {
            $query = "SELECT * FROM $this->tableName";
            $result = mysql_query($query) or die("Nao deu para executar o query " . $query . " pq " . mysql_error());

            $i = 0;
            $countries = array();
            while ($row = mysql_fetch_array($result)) {
                /* Push the results of the query in an array */
                $years[] = $row["Years"];

                //the years array has to be uptaded before this (by calling filterXData before filterYData
                if(in_array($row["Years"], $this->years)) {
                    foreach ($this->getYAxisLabels() as $country) {
                        $countries[$country][$i] = $row[$country];
                    }
                }

                /*foreach ($filter as $indexYear) {
                    if ($this->years[$indexYear] == $row["Years"]) {
                        foreach ($this->getYAxisLabels() as $country) {
                            $countries[$country][$i] = $row[$country];
                        }
                    }
                }*/
                $i++;
            }
            $this->countriesValues = $countries;
            echo "";
        }
    }

    /**
     * Returns the average of an array
     * @param <String()> $a The array to average
     * @return <Int>  The average of the array
     */
    private function getAverage($a) {
        return array_sum($a) / count($a);
    }

}

?>
