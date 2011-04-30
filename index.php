<?php
//an auxiliary array in order to pass the name of the yAxis of each spreadsheet to the graphics
$yAxisTitlePerSpreadSheet = array();

$yAxisTitlePerSpreadSheet['pyj6tScZqmEd1G8qI4GpZQg'] = "Energy Consumption";
$yAxisTitlePerSpreadSheet['pyj6tScZqmEfnPl7VRfT9WA'] = "Arms";
$yAxisTitlePerSpreadSheet['phAwcNAVuyj2ZMli4YTn2Ag'] = "Cell phones (per 100 people)";
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <?php
    require_once("energy_consumption_data_explorer.php");

    /*var_dump($_POST);
    echo "<br/>";*/

    if (empty($_POST)) {
        $de = new EnergyConsumptionDataExplorer();
        $de->createChart("energyconsumption.png", "", "", 2);
    } else {
        $de = new EnergyConsumptionDataExplorer($_POST['spreadSheet']);
        $de->createChart("energyconsumption.png", "", $yAxisTitlePerSpreadSheet[$_POST['spreadSheet']], $_POST['chart'], $_POST['countries']);
    }
    ?>
<html>
    <body bgcolor="yellow">
        <h1>Gr&aacute;fico</h1>

        <p>
            <form name="formChart" action="" method="POST">

                Escolha o tipo de Dados:&nbsp;
                <select name="spreadSheet">
                    <option value="pyj6tScZqmEd1G8qI4GpZQg" <? if ($_POST['spreadSheet'] == 'pyj6tScZqmEd1G8qI4GpZQg' || $_POST['spreadSheet'] == '')
        echo 'selected="selected"'; ?>>Energy Consumption per Capita</option>
                    <option value="pyj6tScZqmEfnPl7VRfT9WA" <? if ($_POST['spreadSheet'] == 'pyj6tScZqmEfnPl7VRfT9WA')
                                echo 'selected="selected"'; ?>>Arms imports</option>
                    <option value="phAwcNAVuyj2ZMli4YTn2Ag" <? if ($_POST['spreadSheet'] == 'phAwcNAVuyj2ZMli4YTn2Ag')
                                echo 'selected="selected"'; ?>>Cell phones (per 100 people)</option>
                </select>
                <br/><br/>

                Escolha o tipo de gr&aacute;fico: &nbsp;
                <select name="chart">

                    <option value="1" <? if ($_POST['chart'] == '1')
                                echo 'selected="selected"'; ?>>Bar chart</option>
                    <option value="2" <? if ($_POST['chart'] == '2' || $_POST['chart'] == '')
                                echo 'selected="selected"'; ?>>Line chart</option>
                    <option value="3" <? if ($_POST['chart'] == '3')
                                echo 'selected="selected"'; ?>>Point chart</option>
                </select>


                <br/><!--<strong>FILTRAR:</strong>--><br/>
                Escolha os pa&iacute;ses e os anos que deseja ver no gr&aacute;fico: <br />
                <select name="countries[]" multiple="multiple" size=5>
                    <?php foreach ($de->getYAxisLabels() as $id => $country): ?>
                                <option value="<?php echo $id ?>"><?php echo $country; ?></option>
                    <?php endforeach; ?>
                            </select>
                            &nbsp;&nbsp;
                            <select name="year" size=5>
                    <?php foreach ($de->getXAxisLabels() as $id => $year): ?>
                                    <option value="<?php echo $id ?>"><?php echo $year; ?></option>
                    <?php endforeach; ?>
                                </select>
                                <br/>

                                <input type="submit" value="Submit" name="submit" />
                            </form>

                        </p>

                        <img alt="<? $yAxisTitlePerSpreadSheet[$_POST['spreadSheet']] ?>" src="pictures/energyconsumption.png" />

        <h2>Rodap&eacute;</h2>
    </body>
</html>
