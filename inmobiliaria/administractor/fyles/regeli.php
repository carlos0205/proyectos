<?php
session_start();
include("general/sesion.php");
sesion(1);

include("general/conexion.php") ;

// fucion validar permisos de acceso a colgrama
/*require 'general/permisos.php';
$colg = 'regeli.php';
$usu = $_SESSION["usuario"];
permisos($usu, $colg);*/

$enlace = enlace();

$dbname = 'cmtx';

if (!mysql_connect('localhost', 'root', '')) {
    echo 'Could not connect to mysql';
    exit;
}

$result = mysql_list_tables($dbname);

if (!$result) {
    echo "DB Error, could not list tables\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
}

while ($row = mysql_fetch_row($result)) {

$tablas = array ("cc","ciudad","deppro","estcon","ip","licusu","pais","progweb","sesionest","tippub","tipusuter");

	if (!in_array($row[0],$tablas)) {	
	
	 	$qry = "DELETE FROM $row[0] ; ";
		$res = mysql_query($qry,$enlace);
		
		$qry1 = "ALTER TABLE `$row[0]` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =1;";
		$res = mysql_query($qry1, $enlace);
	
	
	}
   
}

mysql_free_result($result);



$ruta="administractor/productos/";
//elimino archivos de directorios
if ($dh = opendir($ruta)) 
	{
		while (($file = readdir($dh)) !== false) 
		{
		//esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio
		//mostraría tanto archivos como directorios
		//echo "Nombre de archivo: $file : Es un:" . filetype($ruta . $file);
		if (is_dir($ruta . $file) && $file!="." && $file!="..")
			{
			if ($sdh = opendir($ruta.$file)) { 
			
			while (($files = readdir($sdh)) !== false) 
				{
					if(is_dir($ruta.$file."/".$files) && $files!="." && $files!=".."){
					
						if ($sdh1 = opendir($ruta.$file."/".$files)) { 
							while (($files1 = readdir($sdh1)) !== false) 
								{
								echo $ruta.$file."/".$files."/".$files1."<br>";
								}
						
						}
					}
					echo $ruta.$file."/".$files."<br>";
				}			
			}
			else{
				echo $ruta.$file."<br>";
			}
			}
		}
	closedir($dh);
	}
?>