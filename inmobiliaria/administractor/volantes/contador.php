<?
session_start();
header("Cache-control: private"); //IE 6 Fix. 

require '../fyles/general/conexion.php';
$enlace=enlace();

	$cod =$_GET["cod"];	

	$qryurl ="SELECT url FROM volvir WHERE codvol = '$cod'";
	$resurl =mysql_query ($qryurl, $enlace);
	$filurl = mysql_fetch_assoc($resurl);
	
	$qryupd = "UPDATE volvir SET hits = hits +1 WHERE codvol = '$cod'";
	$resupd =mysql_query ($qryupd, $enlace);

?>	
<script language = JavaScript>
window.location.href="http://<?php echo $filurl["url"]?>";
window.close();
</script>	



	


