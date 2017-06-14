<?
session_start();
header("Cache-control: private"); //IE 6 Fix. 

require '../administractor/fyles/general/conexion.php';
$enlace=enlace();

	$cod =$_GET["cod"];	
	
	$qryupd = "UPDATE banner SET clicks = clicks +1 WHERE codban = '$cod'";
	$resupd =mysql_query ($qryupd, $enlace);

?>	
<script language = JavaScript>
window.close();
</script>	

?>

	


