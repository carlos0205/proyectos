<?php
session_start();
include("../../administractor/fyles/general/conexion.php") ;
include("../../administractor/fyles/general/sesion.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'inmueblesedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

function array_recibe($url_codinmueblevis) { 
	$tmp = stripslashes($url_codinmueblevis); 
	$tmp = urldecode($tmp); 
	$tmp = unserialize($tmp); 
	return $tmp; 
} 
$codinmueblevis=$_GET['codinmueblevis']; 

//codigo del inmueble
$cod=$_GET['codinmueble']; 	

$codinmueblevis=array_recibe($codinmueblevis); 

$qryfotinmueble = mysql_query("SELECT imginmueble FROM inmueblesvis WHERE codinmueblevis IN (".implode(',',$codinmueblevis).")");

while($filimginmueble = mysql_fetch_array($qryfotinmueble)){
	$ruta1="../inmuebles/vistas/".$filimginmueble["imginmueble"];
	unlink ($ruta1);

	$ruta2="../inmuebles/vistas/mini/".$filimginmueble["imginmueble"];
	unlink ($ruta2);
} //fin while
mysql_free_result($qryfotinmueble);

//elimino fotos
$qryfoteli=mysql_query("DELETE FROM inmueblesvis WHERE codinmueblevis IN (".implode(',',$codinmueblevis).")");
?>
<script language="javascript" type="text/javascript">
location = "inmueblesfot.php?cod=<?php echo $cod?>";
</script>
