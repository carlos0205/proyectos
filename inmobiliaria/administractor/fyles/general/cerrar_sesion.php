<?php
	session_start();
	include("conexion.php") ;
	$enlace = enlace();
?>
<html>
<head></head>
<body>
<?php
		$codusu = $_SESSION["enlineaadm"];
		
		$qrysesion = "DELETE FROM sesiones WHERE codusu = $codusu AND invitado='administractor' OR invitado='Docente'";
		$ressesion = mysql_query($qrysesion, $enlace);
		
		auditoria($_SESSION["enlineaadm"],'','','2');
		
		session_unregister("ultimoacceso");
		session_unregister("enlineaadm");
		session_unregister("grupo");
		//session_destroy();
		
		echo '<script language = JavaScript>
			location = "../index.html";
			</script>';
		//exit;
		
?>
</body>
</html>
