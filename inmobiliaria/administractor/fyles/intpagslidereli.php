<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'intpagedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

function array_recibe($url_codreg) { 
	$tmp = stripslashes($url_codreg); 
	$tmp = urldecode($tmp); 
	$tmp = unserialize($tmp); 
	return $tmp; 
} 
$codreg=$_GET['codreg']; 

//codigo de producto
$cod=$_GET['codpag']; 
$idi=$_GET['idi']; 
$titpag	=$_GET['titpag']; 

$codreg=array_recibe($codreg); 

$qryreg = mysql_query("SELECT imgslider FROM pagsiteintslider WHERE codpagintslider IN (".implode(',',$codreg).")");

while($filreg= mysql_fetch_array($qryreg)){
	$ruta1="../../imgseccionslider/".$filreg["imgslider"];
	unlink ($ruta1);

	$ruta2="../../imgseccionslider/mini/".$filreg["imgslider"];
	unlink ($ruta2);
} //fin while
mysql_free_result($qryreg);

//elimino fotos
$qryeli=mysql_query("DELETE FROM pagsiteintslider WHERE codpagintslider IN (".implode(',',$codreg).")");
?>
<script language="javascript" type="text/javascript">
location = "intpagedi.php?cod=<?php echo $cod?>&idi=<?php echo $idi?>&titpag=<?php echo $titpag?>";
</script>