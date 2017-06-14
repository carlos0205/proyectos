<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);
	
	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'notedi.php';
	$usu = $_SESSION["usuario"];
	permisos($usu, $prog);
	
	$enlace = enlace();
		
	function array_recibe($url_codpubfot) { 
		$tmp = stripslashes($url_codpubfot); 
		$tmp = urldecode($tmp); 
		$tmp = unserialize($tmp); 
	    return $tmp; 
	} 
	
	$codpubfot=$_GET['codpubfot']; 
	//codigo de producto
	$cod=$_GET['cod']; 	
		
	$codpubfot=array_recibe($codpubfot); 
	
	$qryimgalbum = "SELECT img FROM pubconfot WHERE codpubfot IN (".implode(',',$codpubfot).") ";
	$resimgalbum =mysql_query($qryimgalbum,$enlace);	
	

	while ($filimgalbum = mysql_fetch_array($resimgalbum)){
		
				$ruta1="../publicaciones/fotos/".$filimgalbum["img"];
				unlink ($ruta1);
			
				$ruta2="../publicaciones/fotos/mini/".$filimgalbum["img"];
				unlink ($ruta2);
			
		} //fin while
		
	mysql_free_result($resimgalbum);
	
	//elimino fotos
	$qryalbumfoteli="DELETE FROM pubconfot WHERE codpubfot IN (".implode(',',$codpubfot).") ";
	$resalbumfoteli=mysql_query($qryalbumfoteli,$enlace);	
?>
	<script language="javascript" type="text/javascript">
	location = "notfot.php?cod=<?php echo $cod?>";
	</script>