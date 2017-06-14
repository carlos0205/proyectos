<?php

session_start();
include("../conexion.php") ;
//include("../sesion.php");
//sesion(1);

$enlace = enlace();

include( "psxlsgen.php" );

$cod = $_GET["cod"];

//consulto encuesta
$qrypub ="SELECT
tblformatoinscripcionevepre.*
FROM
tblformatoinscripcionevepre 
INNER JOIN pubcon 
ON tblformatoinscripcionevepre.codformato = pubcon.codformato
WHERE pubcon.codpub = $cod";
$respub = mysql_query($qrypub, $enlace);


$myxls = new PhpSimpleXlsGen();
$myxls->totalcol = mysql_num_rows($respub);
//genero encabezado dearchivo
while($filpub=mysql_fetch_assoc($respub)){
$myxls->InsertText( "".$filpub["nombrepregunta"]."" );
}

//consulto 
$qryins = " SELECT codinscrito FROM tblformatoinscripcioneveres WHERE codpub = $cod
GROUP BY codinscrito";
$resins= mysql_query($qryins, $enlace);

$contador = 1;

while($filins = mysql_fetch_assoc($resins)){
			$myxls->ChangePos($contador,0);
			//consulto las respuestas del inscrito
			$qryres="SELECT  i.texteva FROM tblformatoinscripcioneveres AS i
INNER JOIN  tblformatoinscripcionevepre AS p 
ON i.codpregunta = p.codpregunta 
WHERE i.codinscrito = ".$filins["codinscrito"]." AND i.codpub = $cod ";
			$resres = mysql_query($qryres, $enlace);
			
			while($filres=mysql_fetch_assoc($resres)){
				
				$myxls->InsertText($filres["texteva"] );
				
			}
			
			$contador++;
			
		}

$myxls->SendFile();
?>
	<script language = 'javascript'>
	window.close();
	</script>
