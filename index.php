<?php
session_start();
//ini_set("display_errors", 1);

require_once './config/config.php';

/**
 * It defines which is the type of source in a session so it can pick the data
 * from the right place when the graph is redrawn
 */
if (empty($_POST)) {
    $_SESSION['tipoFonte'] = "folhaCalculo";
} elseif ($_POST['spreadSheet']) {
    $_SESSION['tipoFonte'] = "folhaCalculo";
} elseif ($_POST['table']) {
    $_SESSION['tipoFonte'] = "dataBase";
}

//the processment of post values into session values (when there are no posts, one uses the default values from config.php)
if ($_POST['spreadSheet']/* || $_POST['chart'] */) {
    $_SESSION['spreadSheet'] = $_POST['spreadSheet'];
} elseif (empty($_POST)) {
    $_SESSION['spreadSheet'] = SPREADSHEETDEFAULT;
}

if ($_POST['chart']) {
    $_SESSION['chart'] = $_POST['chart'];
} elseif (empty($_POST)) {
    $_SESSION['chart'] = CHARTDEFAULT;
}

if ($_POST['countries']) {
    $_SESSION['countries'] = $_POST['countries'];
} elseif (empty($_POST)) {
    $_SESSION['countries'] = $COUNTRIESDEFAULT;
}

if ($_POST['years']) {
    $_SESSION['years'] = $_POST['years'];
} elseif (empty($_POST)) {
    $_SESSION['years'] = $YEARSDEFAULT;
}

if ($_POST['table']) {
    $_SESSION['table'] = $_POST['table'];
} elseif (empty($_POST)) {
    $_SESSION['table'] = $TABLEDEFAULT;
}

if ($_POST['average'] == 'on') {
    $_SESSION['average'] = true;
} else {
    $_SESSION['average'] = false;
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <?php
    //require_once("energy_consumption_data_explorer.php");
    //require_once 'DataBase_DataExplorer.php';

    require_once './class/DataVisualizerSpreadSheet.php';
    require_once './class/DataVisualizerDataBase.php';

    /*echo "POST: <br/>";
    var_dump($_POST);
    echo "<br/>";

    echo "SESSION:<br/>";
    var_dump($_SESSION);
    echo "<br/><br/>";*/

    //By default one shows the Energy consumption spreadsheet
    if (empty($_POST)) {
        //$de = new EnergyConsumptionDataExplorer();
        //$de->createChart("energyconsumption.png", "", "", 2);
        $de = new DataVisualizerSpreadSheet();
        $de->createChart("energyconsumption.png", "", "", 2);
    } else if ($_SESSION['tipoFonte'] == 'folhaCalculo') { /* spreadsheets */
        //echo "spread ou chart<br/>";
        //$de = new EnergyConsumptionDataExplorer($_SESSION['spreadSheet'], $_SESSION['average']);
        $de = new DataVisualizerSpreadSheet($_SESSION['average'], $_SESSION['spreadSheet']);
        $de->createChart("energyconsumption.png", "", $yAxisTitlePerSpreadSheet[$_SESSION['spreadSheet']],
                $_SESSION['chart'], $_SESSION['countries'], $_SESSION['years']);
    } else if ($_SESSION['tipoFonte'] == 'dataBase') { /* Database */
        //$de = new DataBase_DataExplorer($_SESSION['table'], $_SESSION['average']);
        $de = new DataVisualizerDataBase($_SESSION['average'], $_SESSION['table']);
        $de->createChart("energyconsumption.png", $tables[$_SESSION['table']], $_SESSION['table'],
                $_SESSION['chart'], $_SESSION['countries'], $_SESSION['years']);
    }
    /*
      foreach ($de->getYAxisLabels() as $id => $country) {
      echo "id = $id ; pais: $country<br/>";
      }
      foreach ($de->getXAxisLabels() as $id => $year) {
      echo "id = $id; year = $year<br/>";
      } */
    ?>
<html>
    <body bgcolor="yellow">
        <h1>Gr&aacute;fico</h1>

        <p>
            <fieldset >
                <legend>Base de Dados</legend>

                <form name="Database" action="" method="POST">
                    Escolha a tabela:&nbsp;<select name="table">
                        <?
                        foreach ($tables as $tableName => $tableDescription) {
                        ?>
                            <option value="<? echo $tableName; ?>"><? echo $tableDescription; ?></option>
                        <?
                        }
                        ?>


                    </select>
                    <input type="submit" value="Desenhar da Base de Dados" name="submit"/>
                </form>
            </fieldset>

        </p>
        <p>
            <fieldset>
                <legend>Folhas de C&aacute;lculo</legend>
                <form name="chartSource" action="" method="POST">
                    Escolha a Folha de C&aacute;lculo:&nbsp;
                    <select name="spreadSheet">
                        <?
                        /*
                          <option value="pyj6tScZqmEd1G8qI4GpZQg" <? if ($_SESSION['spreadSheet'] == 'pyj6tScZqmEd1G8qI4GpZQg' || $_SESSION['spreadSheet'] == '')
                          echo 'selected="selected"'; ?>>Energy Consumption per Capita</option>
                          <option value="pyj6tScZqmEfnPl7VRfT9WA" <? if ($_SESSION['spreadSheet'] == 'pyj6tScZqmEfnPl7VRfT9WA')
                          echo 'selected="selected"'; ?>>Arms imports</option>
                          <option value="phAwcNAVuyj2ZMli4YTn2Ag" <? if ($_SESSION['spreadSheet'] == 'phAwcNAVuyj2ZMli4YTn2Ag')
                          echo 'selected="selected"'; ?>>Cell phones (per 100 people)</option>
                          <option value="pyj6tScZqmEdrsBnj2ROXAg" <? if ($_SESSION['spreadSheet'] == 'pyj6tScZqmEdrsBnj2ROXAg')
                          echo 'selected="selected"'; ?>>Adult literacy rate (%)</option>
                         *
                         *
                         */
                        foreach ($spreadSheets as $sprdsheetKey => $sprdsheetDescription) {
                        ?>
                            <option value="<? echo $sprdsheetKey; ?>" <? if ($_SESSION['spreadSheet'] == $sprdsheetKey)
                                echo 'selected="selected"'; ?>><? echo $sprdsheetDescription; ?></option>
                                <?
                            }
                                ?>

                    </select>
                    <br/>
                    <input type="submit" value="Mudar Fonte de Dados" name="submit" />
                </form>
            </fieldset>

        </p>

        <p>
            <form name="formChart" action="" method="POST">
                <fieldset>
                    <legend>Configurar Gr&aacute;fico</legend>

                            Escolha o tipo de gr&aacute;fico: &nbsp;
                            <select name="chart">

                                <option value="1" <? if ($_SESSION['chart'] == '1')
                                echo 'selected="selected"'; ?>>Bar chart</option>
                        <option value="2" <? if ($_SESSION['chart'] == '2' || $_SESSION['chart'] == '')
                                    echo 'selected="selected"'; ?>>Line chart</option>
                        <option value="3" <? if ($_SESSION['chart'] == '3')
                                    echo 'selected="selected"'; ?>>Point chart</option>
                    </select>


                <br/><br/>

                    <input type="checkbox" name="average"  <? if ($_SESSION['average'] == true)
                                    echo 'checked="checked"'; ?>/>&nbsp;Desenhar m&eacute;dia<br/><br/>
                         Escolha os pa&iacute;ses e os anos que deseja ver no gr&aacute;fico: <br />
                         <select name="countries[]" multiple="multiple" size=5>
                        <?php foreach ($de->getYAxisLabels() as $id => $country): ?>
                                    <option value="<?php echo $id ?>" <? if (in_array($id, $_SESSION['countries']))
                                        echo "selected='selected';" ?>><?php echo $country; ?></option>
                                <?php endforeach; ?>
                            </select>
                            &nbsp;&nbsp;
                            <select name="years[]" multiple="multiple" size=5>
                        <?php foreach ($de->getXAxisLabels() as $year): ?>
                                            <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                        <?php endforeach; ?>
                                        </select>
                                        <br/>

                                        <input type="submit" value="Redesenhar Gr&aacute;fico" name="submit" />
                                    </fieldset>

                                </form>

                            </p>
                            <br/>

                            <img alt="<? $yAxisTitlePerSpreadSheet[$_SESSION['spreadSheet']] ?>" src="./pictures/energyconsumption.png" />

        <? /* <h2>Rodap&eacute;</h2> */ ?>
    </body>
</html>
