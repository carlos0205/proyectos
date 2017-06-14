<?php

session_start();
include("../conexion.php") ;
//include("../sesion.php");
//sesion();

$enlace = enlace();

include( "psxlsgen.php" );

$cod = $_GET["cod"];

//consulto encuesta
$qryenc = "Select nomenc FROM enc WHERE codenc = '$cod'";
$resenc = mysql_query($qryenc, $enlace);
$filenc = mysql_fetch_assoc($resenc);

$myxls = new PhpSimpleXlsGen();
$myxls->totalcol = 3;
$myxls->InsertText( "Votante" );
$myxls->InsertText( "Nombre" );
$myxls->InsertText( "Fecha Votación" );


$qryvot = "SELECT t.codter AS codigovotante, t.nitter AS identificacion , t.nomter AS nombre, eu.fecres AS fechavoto
FROM tercli AS t 
INNER JOIN encusu AS eu
ON eu.codusucli = t.codter
WHERE eu.codenc = $codenc
UNION
SELECT e.codusuemp, '' AS cedula, e.nomusu, eue.fecres
FROM empleados AS e
INNER JOIN encusu AS eue
ON e.codusuemp = eue.codusucli 
WHERE eue.codenc = $codenc
ORDER BY fechavoto DESC";
	
$resvot = mysql_query($qryvot, $enlace);

$contador = 1;
while ($filvot=mysql_fetch_assoc($resvot)){

	$myxls->ChangePos($contador,0);
	$myxls->InsertNumber( $filvot["identificacion"] );
	$myxls->InsertText( $filvot["nombre"] );
	$myxls->InsertText( $filvot["fechavoto"] );

$contador++;
}
$myxls->SendFile();

?>