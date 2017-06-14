<?
session_start();
header("Cache-control: private"); //IE 6 Fix. 

require '../administractor/fyles/general/conexion.php';
$enlace=enlace();

	$cod =$_GET["cod"];	

	
	$qryurl ="SELECT url, abre FROM banner WHERE codban = '$cod'";
	$resurl =mysql_query ($qryurl, $enlace);
	$filurl = mysql_fetch_assoc($resurl);
	
	$qryupd = "UPDATE banner SET clicks = clicks +1 WHERE codban = '$cod'";
	$resupd =mysql_query ($qryupd, $enlace);

if($filurl["abre"]=="_parent"){		
?>	
<script language = JavaScript>
window.opener.location.href="http://<?php echo $filurl["url"]?>";
window.close();
</script>	
<?php
}else{
?>	
<script language = JavaScript>
location.href ="http://<?php echo $filurl["url"]?>" ;
</script>	
<?php
}
?>

	


