<?php

session_start();
include("general/sesion.php");
sesion();
include("../conexion.php") ;
$enlace = enlace();

include( "psxlsgen.php" );

$qryenc = "Select nomenc FROM enc WHERE codenc = 4";
$resenc = mysql_query($qryenc, $enlace);
$filenc = mysql_fetch_assoc($resenc);

$myxls = new PhpSimpleXlsGen();
$myxls->totalcol = 5;
$myxls->InsertText( "Encuesta" );
$myxls->InsertText( "Item" );
$myxls->InsertText( "Pregunta" );
$myxls->InsertText( "Respuestas" );
$myxls->InsertText( "Votos" );
//$myxls->ChangePos(1,0);
$myxls->WriteText_pos( 1,0,$filenc["nomenc"] );
$myxls->ChangePos(2,1);
$myxls->InsertText( "1" );
$myxls->InsertText( "Le gusta nuestro sitio" );
$myxls->WriteText_pos(2,3, "Si" );
$myxls->WriteText_pos(3,3, "no" );
$myxls->WriteNumber_pos(2,4, "3" );
$myxls->WriteNumber_pos(3,4, "5" );
$myxls->SendFile();

?>