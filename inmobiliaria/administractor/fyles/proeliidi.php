<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);
	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'proeli.php';
	$usu = $_SESSION["usuario"];
	$permiso =  permisos($usu, $prog);
	if($permiso){
	
		$enlace = enlace();
		$cod=$_GET['cod']; 
		$idi=$_GET['codidi']; 
				
		//elimino idioma 
		$qryreseli="DELETE FROM prodet WHERE codpro = '$cod' AND codidi ='$idi'";
		$resreseli=mysql_query($qryreseli,$enlace);				

		?> <script language = "JavaScript" type="text/javascript">
			location = "proedi.php?cod=<?php echo $cod?>&acc=1";
			</script>
		<?php
	}
?>