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

$codreg=array_recibe($codreg); 

$qryreg = mysql_query("SELECT imgslider FROM pagsiteimgdiariaslider WHERE codpagintdiaslider IN (".implode(',',$codreg).")");

while($filreg= mysql_fetch_array($qryreg)){
	$ruta1="../../imgsecciondiariaslider/".$filreg["imgslider"];
	unlink ($ruta1);

	$ruta2="../../imgsecciondiariaslider/mini/".$filreg["imgslider"];
	unlink ($ruta2);
} //fin while
mysql_free_result($qryreg);

//elimino fotos
$qryeli=mysql_query("DELETE FROM pagsiteimgdiariaslider WHERE codpagintdiaslider IN (".implode(',',$codreg).")");
?>
<script language="javascript" type="text/javascript">
location = "pagsiteimgdiariaedi.php?cod=<?php echo $cod?>&acc=1>";
</script>