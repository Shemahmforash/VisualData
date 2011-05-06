<?php
//a folha de cálculo por defeito (energia per capita)
define("SPREADSHEETDEFAULT", "pyj6tScZqmEd1G8qI4GpZQg");

//o tipo de gráfico por defeito (line graph)
define("CHARTDEFAULT", 2);

//os países q aparecem por defeito (os 2 primeiros da spreadsheet)
$COUNTRIESDEFAULT = array(2,3);

//os anos q aparecem por default (vazio significa q aparecem todos os anos q existam na spreadsheet)
$YEARSDEFAULT = array();

$spreadSheets = array();
$spreadSheets['pyj6tScZqmEd1G8qI4GpZQg'] = "Energy Consumption per Capita";
//$spreadSheets['pyj6tScZqmEfnPl7VRfT9WA'] = "Arms imports";

$spreadSheets['rSv5aMDwESiKg-yA__-tRFg'] = "Air accidents killed";

//$spreadSheets['phAwcNAVuyj2ZMli4YTn2Ag'] = "Cell phones (per 100 people)";
$spreadSheets['pyj6tScZqmEc8dxBU9o6rRQ'] = "Forest Area (Km²)";
$spreadSheets['pyj6tScZqmEdrsBnj2ROXAg'] = "Adult literacy rate (%)";

//an auxiliary array in order to pass the name of the yAxis of each spreadsheet to the graphics
$yAxisTitlePerSpreadSheet = array();
$yAxisTitlePerSpreadSheet['pyj6tScZqmEd1G8qI4GpZQg'] = "Energy Consumption";
//$yAxisTitlePerSpreadSheet['pyj6tScZqmEfnPl7VRfT9WA'] = "Arms";
$yAxisTitlePerSpreadSheet['rSv5aMDwESiKg-yA__-tRFg'] = "People Killed";
//$yAxisTitlePerSpreadSheet['phAwcNAVuyj2ZMli4YTn2Ag'] = "Cell phones (per 100 people)";
$yAxisTitlePerSpreadSheet['pyj6tScZqmEc8dxBU9o6rRQ'] = "Forest Area (Km²)";
$yAxisTitlePerSpreadSheet['pyj6tScZqmEdrsBnj2ROXAg'] = "Adult literacy rate (%)";

//the available tables from the database
$tables = array("ExtremeTemperatureKilled" => "Killed by Extreme Temperature", "TradeBalance" => "Trade Balance (US$)");

//the table that appears by default
$TABLEDEFAULT = "ExtremeTemperatureKilled";

$SHOWAVERAGEDEFAULT = FALSE;

//by default one sees the spreadsheet data
$TIPOFONTEDEFAULT = "folhaCalculo";

?>
