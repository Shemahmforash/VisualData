<?php
//a folha de cálculo por defeito (energia per capita)
define("SPREADSHEETDEFAULT", "pyj6tScZqmEd1G8qI4GpZQg");

//o tipo de gráfico por defeito (line graph)
define("CHARTDEFAULT", 2);

//os países q aparecem por defeito (os 3 primeiros da spreadsheet)
$COUNTRIESDEFAULT = array(2,3,4);

$YEARSDEFAULT = array();

//an auxiliary array in order to pass the name of the yAxis of each spreadsheet to the graphics
$yAxisTitlePerSpreadSheet = array();
$yAxisTitlePerSpreadSheet['pyj6tScZqmEd1G8qI4GpZQg'] = "Energy Consumption";
$yAxisTitlePerSpreadSheet['pyj6tScZqmEfnPl7VRfT9WA'] = "Arms";
$yAxisTitlePerSpreadSheet['phAwcNAVuyj2ZMli4YTn2Ag'] = "Cell phones (per 100 people)";
$yAxisTitlePerSpreadSheet['pyj6tScZqmEdrsBnj2ROXAg'] = "Adult literacy rate (%)";

?>
