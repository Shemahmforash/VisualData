<?php
session_start();

require_once './config/config.php';

//processamento dos POSTS para valores de sessão (se n houver posts, usa-se valores por defeito)
if ($_POST['spreadSheet']) {
    echo "defino a sessão da spreadsheet<br/>";
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
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
require_once("energy_consumption_data_explorer.php");

echo "POST: <br/>";
var_dump($_POST);
echo "<br/>";

echo "SESSION:<br/>";
var_dump($_SESSION);
echo "<br/>";

if (empty($_POST)) {
    $de = new EnergyConsumptionDataExplorer();
    $de->createChart("energyconsumption.png", "", "", 2);
} else {
    $de = new EnergyConsumptionDataExplorer($_SESSION['spreadSheet']);
    $de->createChart("energyconsumption.png", "", $yAxisTitlePerSpreadSheet[$_SESSION['spreadSheet']],
            $_SESSION['chart'], $_SESSION['countries']);
}
?>
<html>
    <body bgcolor="yellow">
        <h1>Gr&aacute;fico</h1>

        <p>
            <form name="chartSource" action="" method="POST">
<?
/*
 * TODO: DEVIA DESENHAR LOGO O GRÁFICO A PARTIR DESTE SELECT, IGNORANDO OS OUTROS CAMPOS, POIS AQUI TEMOS NOVA FONTE DE DADOS E OS FILTROS PODEM SER DIFERENTES (OUTROS ANOS, OUTROS PAÍSES)
 */
?>
                Escolha o tipo de Dados:&nbsp;
                <select name="spreadSheet">
                    <option value="pyj6tScZqmEd1G8qI4GpZQg" <? if ($_SESSION['spreadSheet'] == 'pyj6tScZqmEd1G8qI4GpZQg' || $_SESSION['spreadSheet'] == '')
    echo 'selected="selected"'; ?>>Energy Consumption per Capita</option>
                    <option value="pyj6tScZqmEfnPl7VRfT9WA" <? if ($_SESSION['spreadSheet'] == 'pyj6tScZqmEfnPl7VRfT9WA')
                    echo 'selected="selected"'; ?>>Arms imports</option>
                    <option value="phAwcNAVuyj2ZMli4YTn2Ag" <? if ($_SESSION['spreadSheet'] == 'phAwcNAVuyj2ZMli4YTn2Ag')
                    echo 'selected="selected"'; ?>>Cell phones (per 100 people)</option>
                    <option value="pyj6tScZqmEdrsBnj2ROXAg" <? if ($_SESSION['spreadSheet'] == 'pyj6tScZqmEdrsBnj2ROXAg')
                    echo 'selected="selected"'; ?>>Adult literacy rate (%)</option>
                </select>

                <input type="submit" value="Mudar Fonte de Dados" name="submit" />
            </form>
        </p>

        <p>
            <form name="formChart" action="" method="POST">

<?
                /*
                 * AO MUDAR O TIPO DE GRÁFICO N DEVIA SER NECESSÁRIO RELER OS DADOS DO SERVER, POIS ISTO É APENAS "COSMÉTICA DO GRÁFICO"
                 */
?>
                Escolha o tipo de gr&aacute;fico: &nbsp;
                <select name="chart">

                    <option value="1" <? if ($_SESSION['chart'] == '1')
                    echo 'selected="selected"'; ?>>Bar chart</option>
                    <option value="2" <? if ($_SESSION['chart'] == '2' || $_SESSION['chart'] == '')
                    echo 'selected="selected"'; ?>>Line chart</option>
                    <option value="3" <? if ($_SESSION['chart'] == '3')
                    echo 'selected="selected"'; ?>>Point chart</option>
                </select>


                <br/><!--<strong>FILTRAR:</strong>--><br/>
                Escolha os pa&iacute;ses e os anos que deseja ver no gr&aacute;fico: <br />
                <select name="countries[]" multiple="multiple" size=5>
<?php foreach ($de->getYAxisLabels() as $id => $country): ?>
                    <option value="<?php echo $id ?>" <? if (in_array($id, $_SESSION['countries']))
                        echo "selected='selected';" ?>><?php echo $country; ?></option>
<?php endforeach; ?>
                </select>
                &nbsp;&nbsp;
                <select name="year" size=5>
                    <?php foreach ($de->getXAxisLabels() as $id => $year): ?>
                            <option value="<?php echo $id ?>"><?php echo $year; ?></option>
<?php endforeach; ?>
                        </select>
                        <br/>

                        <input type="submit" value="Redesenhar" name="submit" />
                    </form>

                </p>

                <img alt="<? $yAxisTitlePerSpreadSheet[$_SESSION['spreadSheet']] ?>" src="./pictures/energyconsumption.png" />

        <h2>Rodap&eacute;</h2>
    </body>
</html>
