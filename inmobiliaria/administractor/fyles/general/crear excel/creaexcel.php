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
$myxls->totalcol = 5;
$myxls->InsertText( "Encuesta" );
$myxls->InsertText( "Item" );
$myxls->InsertText( "Pregunta" );
$myxls->InsertText( "Respuestas" );
$myxls->InsertText( "Votos" );



$qrypre = "SELECT e.*, p.codpre, p.nompre, r.nomres, r.hits FROM enc e, encpre p, encpreres r WHERE e.codenc = '$cod' AND p.codenc ='$cod' AND r.codpre = p.codpre";
$respre = mysql_query($qrypre, $enlace);

$contador = 1;
$pregunta = 0;
$item = 0;
while ($filpre=mysql_fetch_assoc($respre)){

	$myxls->ChangePos($contador,0);
	if($contador == 1){
	$myxls->InsertText( $filenc["nomenc"] );
	}else{
	$myxls->InsertText( "" );
	}
	if ($filpre["codpre"] <> $pregunta ){
	$myxls->InsertNumber( $item +1 );
	$myxls->InsertText( $filpre["nompre"] );
	$item++;
	}else{
	$myxls->InsertText( "");
	$myxls->InsertText("");
	}
	$pregunta = $filpre["codpre"];
	
	$myxls->InsertText( $filpre["nomres"] );
	$myxls->InsertNumber( $filpre["hits"] );

$contador++;
}
$myxls->SendFile();

?>