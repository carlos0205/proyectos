<?php
session_start();
include("administractor/fyles/general/conexion.php") ;
require 'administractor/fyles/general/useronline.php';	
$enlace=enlace();
online();


//incluímos la clase ajax 
require ('javascripts/xajax/xajax_core/xajax.inc.php');

//instanciamos el objeto de la clase xajax 
$xajax = new xajax();
$xajax->configure('javascript URI', 'javascripts/xajax/');

function contadorimg($codban){
	global $enlace;
	$qryupd = "UPDATE banner SET clicks = clicks +1 WHERE codban = '$codban'";
	$resupd =mysql_query ($qryupd, $enlace);
}

function registro($form_entrada){

	global $enlace;
	$respuesta = new xajaxResponse();
	//averiguo si email ya existe
	$qryexi = "SELECT codspam FROM spam WHERE emaspam ='".$form_entrada["txtema"]."'";
	$resexi = mysql_query($qryexi, $enlace);
	if(mysql_num_rows($resexi)>0){
		$respuesta->alert("La cuenta de correo ya existe en nuestra base de datos");
		$respuesta->assign("txtema","value","");
	}else{
		$qry="INSERT INTO spam VALUES('0','".$form_entrada["txtnom"]."','".$form_entrada["txtema"]."','1')";
		$res=mysql_query($qry, $enlace);
		$respuesta->alert("Su registro ha sido exitoso");
		$respuesta->assign("txtema","value","");
		$respuesta->assign("txtnom","value","");
	}
	return $respuesta;
}
//El objeto xajax tiene que procesar cualquier petición 
$xajax->registerFunction("contadorimg"); 
$xajax->registerFunction("registro"); 
$xajax->processRequest();

$fecha = date("Y-n-j H:i:s");
$link = "2";
$ip = $_SERVER['REMOTE_ADDR']; 

include("administractor/fyles/geoip.inc.php");

$sigpai = getCCfromIP($ip);
$insertSQL = sprintf("INSERT INTO vis (fecvis, linkvis, ipvis, sigpai) VALUES ('%s', '%s', '%s', '%s')",
$fecha,
$link,
$ip,
$sigpai);
$Result1 = mysql_query($insertSQL, $enlace) or die(mysql_error());

//valido si usuario en linea ha iniciado sesion
if (!isset($_SESSION['enlinea'])){ 
	$tipusuter=1;
	$codlispre = 1;
	$session = session_id();
}else{
	$qryter = "SELECT tc.nomter, tc.codtipusuter, utc.codusucli, tc.codlispre FROM tercli tc, usutercli utc WHERE tc.codter = '".$_SESSION['enlinea']."' AND tc.codter = utc.codter";
	$rester = mysql_query($qryter, $enlace);
	$filter = mysql_fetch_assoc($rester);
	$tipusuter=$filter["codtipusuter"];
	$codlispre = $filter["codlispre"];
	$session = $_SESSION["enlinea"];
}

//valido si selecciona idioma
if(!isset($_GET['idi'])){
	$idioma=1;
}else{
	$idioma = $_GET['idi'];

	//valido introduccion de idioma valido
	if ($idioma < 1 || $idioma > 2){
		$idioma = 1;
	}
}


//consulto banner o imagenn de seccion
$qryimg = "SELECT * FROM pagsiteint WHERE codidi = '$idioma' AND codpag = '$link'";
$resimg = mysql_query($qryimg, $enlace);
$filimg = mysql_fetch_assoc($resimg);


// averiguo informacion de empresa y licencia

$qryinfemp = "SELECT telemp, diremp, faxemp, telofiemp, imgfonreq, imgfon, imgfonx, imgfony, colfon, fondofijo, url  FROM licusu";
$resinfemp = mysql_query($qryinfemp, $enlace);
$filinfemp = mysql_fetch_assoc($resinfemp);

//averiguo si existe imagen de seccion diaria
$qryimgdiaria = "SELECT *  FROM pagsiteimgdiaria WHERE codidi = '$idioma' AND codpag = '$link' AND coddiasemana = ".date("N")." AND codempresa= 1";
$resimgdiaria = mysql_query($qryimgdiaria, $enlace);


//averiguo si existe imagen de FONDO diaria
$qryimgfondo = "SELECT *  FROM pagsitefondodiario WHERE codidi = '$idioma' AND coddiasemana = ".date("N")." AND codempresa = 1";
$resimgfondo = mysql_query($qryimgfondo, $enlace);

$qryetiqueta = "SELECT regional FROM etiquetas WHERE codidi = $idioma";
$resetiqueta = mysql_query($qryetiqueta, $enlace);
$filetiqueta = mysql_fetch_array($resetiqueta);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html lang="es"><!-- InstanceBegin template="/Templates/pl01.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" >
<!-- InstanceBeginEditable name="doctitle" -->

<title>Comertex S.A.</title>
<?php
//En el <head> indicamos al objeto xajax se encargue de generar el javascript necesario 
$xajax->printJavascript(); 
?>
<META NAME="Author" CONTENT="tipoint ltda">
<META NAME="Subject" CONTENT="">
<META NAME="Description" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Language" CONTENT="Spanish">
<META NAME="Revisit" CONTENT="1 day">
<META NAME="Distribution" CONTENT="Global">
<META NAME="Robots" CONTENT="All">

<link rel="stylesheet" href="javascripts/jquery.nyroModal/styles/nyroModal.css" type="text/css" media="screen" />
<script type="text/javascript" src="javascripts/jquery.nyroModal/js/jquery.min.js"></script>
<script type="text/javascript" src="javascripts/jquery.nyroModal/js/jquery.nyroModal.custom.js"></script>
<!--[if IE 6]>
	<script type="text/javascript" src="javascripts/jquery.nyroModal/js/jquery.nyroModal-ie6.min.js"></script>
<![endif]-->
<script type="text/javascript">
function contadorimg(codban){
	xajax_contadorimg(codban);
}


function verbannermodal(){

$(function () {
  $('.nyroModal').nyroModal().nmCall();
});
}

</script>

<script type="text/javascript" src="script/base/js/validaform.js"></script>
<script type="text/javascript" src="videos/js/flashembed.min.js"></script>
<link rel="stylesheet" type="text/css" href="videos/css/common.css">
<script type="text/javascript" src="javascripts/swfobject.js"></script>


<style type="text/css">
img, div { behavior: url(javascripts/iepngfix.htc) }
A:LINK {text-decoration : none; color:#3333CC} 
A:VISITED {text-decoration : none; color : #3333CC} 
A:HOVER {text-decoration : none; color : #3333CC;} 
A:ACTIVE {text-decoration : none; color : #3333CC} 

A.clase1:LINK {text-decoration : none; color : #FFFFFF} 
A.clase1:VISITED {text-decoration : none; color : #FFFFFF} 
A.clase1:HOVER {text-decoration : none; color : #FFFFFF;} 
A.clase1:ACTIVE {text-decoration : none; color : #FFFFFF} 

body {
margin-top: 0mm;
<?php if($filinfemp["imgfonreq"]=="Si"){
if(mysql_num_rows($resimgfondo)>0){
$filimgfondo = mysql_fetch_assoc($resimgfondo);
?>
background-image: url(imgfondodiaria/<?php echo $filimgfondo["imgfondo"];?>);
<?php 
}else{
?>
background-image: url(images/<?php echo $filinfemp["imgfon"];?>);
<?php } ?>
background-position:center;
background-position:top;
<?php 
if($filinfemp["fondofijo"]=="No"){
?>
background-attachment:fixed;
<?php 
}
if($filinfemp["imgfonx"]=="Si" && $filinfemp["imgfony"]=="No"){
?>
background-repeat:repeat-x;
<?php }	?>
<?php } ?>	
background-color: #<?php echo $filinfemp["colfon"];?>;
}</style>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->

<link href="css/cliente.css" rel="stylesheet" type="text/css" >
</head>
<body>
<table width="954" border="0"  align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr>
    <td width="954" height="220" valign="top" align="left"><!-- InstanceBeginEditable name="contenido" -->
	
	<form method="post" name="form1" action="" id="form1" enctype="multipart/form-data" >
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro" >
        <!--DWLayoutTable-->
		
		<?php
			 $qrybanmenu = "SELECT b.* FROM banner b, bannerpag bp WHERE b.feciniban <= '$fecha' AND b.fecfinban > '$fecha' AND b.pub ='Si' AND b.codidi = '$idioma' AND bp.codpos = 6 AND bp.codpag = $link AND b.codban = bp.codban ORDER BY bp.orden ASC";
						  $resbanmenu = mysql_query($qrybanmenu, $enlace);
			
			  $numbanmenu = mysql_num_rows($resbanmenu);
			  
			  if($numbanmenu> 0){
?>
		  <tr>
        <td height="20" colspan="3" align="center" valign="top"><?php 
			  
							
				while($filbanmenu=mysql_fetch_assoc($resbanmenu)){
				
				if($filbanmenu["abre"]=="_parent"){
				$abresup = 1;
				}else{
				$abresup = 2;
				}
				echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
				echo "<tr><td valign=top>";
				//actualizo carga de banner
				$banizq = $filbanmenu["codban"];
				$qryactban = "UPDATE banner SET impban = impban + 1 WHERE codban = $banizq";
				$resactban = mysql_query($qryactban, $enlace);
				//
				$datos = GetImageSize('banner/'.$filbanmenu["imgban"].''); 
				$xsup = $datos[0]; 
				$ysup = $datos[1]; 
				if($filbanmenu["tipimg"]==1){

				?>
		                  <script type="text/javascript" language="javascript" >
		var params = {menu: "false", wmode: "transparent", loop: "false" };
		var attributes = {};
		swfobject.embedSWF("banner/<?php echo $filbanmenu["imgban"] ?>", "contenido<?php echo $filbanmenu["codban"]?>", "<?php echo $xsup?>", "<?php echo $ysup?>", "9.0.0", "javascripts/expressInstall.swf", "", params, attributes, wmode="transparent");
		                    </script>
		                  <div id="contenido<?php echo $filbanmenu["codban"];?>">
		          Requiere Flash<br>
		          <a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a>                    </div>
		              
                  <div id="myDiv<?php echo $filbanmenu["codban"]?>" >                        </div>		                  <?php 
				  
				  } else{  if ($filbanmenu["manvin"]=="2"){ echo"  <span onClick=contadorimg(".$filbanmenu["codban"].")>";
       echo "<a href=http://".$filbanmenu["url"]."  target=".$filbanmenu["abre"]."><img src=\"banner/".$filbanmenu["imgban"]."\" border=0 width=".$xsup." height=".$ysup." ></a></span>";}else{ echo "<img src=\"banner/".$filbanmenu["imgban"]."\"  width=".$xsup." height=".$ysup." >"; } }
;
					 
echo" </td>";
echo "</tr>";
}//fin while
echo "</table>" ;   
					 
							 
			  ?></td>
		  </tr>
<?php 
}

?>

		            <tr>
		              <td height="100" valign="top"  bgcolor="#FFFFFF" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
		                <!--DWLayoutTable-->
		              
					   <?php
						
					
			 $qrybanmodal = "SELECT b.* FROM banner b, bannerpag bp WHERE b.feciniban <= '$fecha' AND b.fecfinban > '$fecha' AND b.pub ='Si' AND b.codidi = '$idioma' AND bp.codpos = 5 AND bp.codpag = $link AND b.codban = bp.codban ORDER BY bp.orden ASC";
			 $resbanmodal = mysql_query($qrybanmodal, $enlace);

			  
			  if(mysql_num_rows($resbanmodal)> 0){
			  
			  $filbanmodal = mysql_fetch_assoc($resbanmodal);
			 
			  if($filbanmodal["abre"]=="_parent"){
				$abresup = 1;
				}else{
				$abresup = 2;
				}
			  	
				$datos = GetImageSize('banner/'.$filbanmodal["imgban"].''); 
				$xsup = $datos[0]; 
				$ysup = $datos[1]; 
				
				if($filbanmodal["tipimg"]==1){

				?>
				
				<a href="#bannerflashmodal" class="nyroModal"></a>
				<div id="bannerflashmodal" style="display:none">	
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="220" height="110">
                  <param name="movie" value="banner/1.swf" />
                  <param name="quality" value="high" />
                  <embed src="banner/<?php echo $filbanmodal["imgban"] ?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="<?php echo $xsup?>" height="<?php echo $ysup?>"></embed>
                </object>	
				 </div>
			          <?php 
				  
				  } else{ 
				 ?>
				 
				 <?php 
					 if ($filbanmodal["manvin"]=="2"){ 
					  echo"<a  href='#bannerimgmodal' class='nyroModal' ></a> <div id='bannerimgmodal' style='display:none'> <span onClick=contadorimg(".$filbanmodal["codban"].")>";
		   echo "<a  href=http://".$filbanmodal["url"]."  target=".$filbanmodal["abre"]."><img src=\"banner/".$filbanmodal["imgban"]."\" border=0 width=".$xsup." height=".$ysup." ></a></span></div>";
				   }else{
					echo "<div style='display:none'><a  href='banner/".$filbanmodal["imgban"]."' class='nyroModal' ><img src=\"banner/".$filbanmodal["imgban"]."\"  width=".$xsup." height=".$ysup."></a></div>"; 
					} 
				}
;
			?>

					<script language="javascript" type="text/javascript">
					verbannermodal();
					</script>
				
				<?php
			  } 
				   //si existe imagen diaria la pongo si no comparo contra la seccion
				   if(mysql_num_rows($resimgdiaria)>0){ //if 1
				   $filimgdiaria = mysql_fetch_assoc($resimgdiaria);
				   ?>
		            <tr>
		              <td height="78" colspan="3" valign="top"><?php 
			//averiguo extension de imagen
			// $ext = strrchr($filban["imgindex"],'.');
			//$ext = strtolower($ext); if ($ext == ".swf"){
			$datos = GetImageSize('imgsecciondiaria/'.$filimgdiaria["imgpag"].''); 
			$x = $datos[0]; 
			$y = $datos[1]; 
			if($filimgdiaria["tipimg"]==1){
			?>
		                <script type="text/javascript">
			var params = {menu: "false", wmode: "transparent", loop: "false" };
			var attributes = {};
			swfobject.embedSWF("imgsecciondiaria/<?php echo $filimgdiaria["imgpag"] ?>", "imgsecciondiaria", "<?php echo $x?>", "<?php echo $y?>", "9.0.0", "javascripts/expressInstall.swf","", params, attributes);
							</script>
		                <div  id="imgsecciondiaria" align="center"><a href="http://www.adobe.com/go/getflashplayer">requiere flash<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player"/><br></a>
		                  <?php } 
						else
						{  
							
							if ($filimgdiaria["manvin"]=="Si"){ 
							echo "<a href=http://".$filimgdiaria["url"]."  target=".$filimgdiaria["abre"]."><img src=\"imgsecciondiaria/".$filimgdiaria["imgpag"]."\" border=0 width=".$x." height=".$y." ></a>";
							}else{
							 echo "<img src=\"imgsecciondiaria/".$filimgdiaria["imgpag"]."\"  width=".$x." height=".$y." >"; 
							} 
						}

						?>
                      </div></td>
          </tr>
		            <?php
				   }else{
				   
				   if($filimg["tipimg"]<>3) { //if2 ?> <tr>
		              <td height="78" colspan="3" valign="top"><?php 
	//averiguo extension de imagen
	// $ext = strrchr($filban["imgindex"],'.');
	//$ext = strtolower($ext); if ($ext == ".swf"){
	$datos = GetImageSize('imgseccion/'.$filimg["imgpag"].''); 
	$x = $datos[0]; 
	$y = $datos[1]; 
	if($filimg["tipimg"]==1){
	?>
		                <script type="text/javascript">
	var params = {menu: "false", wmode: "transparent", loop: "false" };
	var attributes = {};
	swfobject.embedSWF("imgseccion/<?php echo $filimg["imgpag"] ?>", "imgseccion", "<?php echo $x?>", "<?php echo $y?>", "9.0.0", "javascripts/expressInstall.swf","", params, attributes);
	                    </script>
		                <div  id="imgseccion" align="center"><a href="http://www.adobe.com/go/getflashplayer">requiere flash<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player"/><br></a><?php } else{  if ($filimg["manvin"]==1){ echo "<a href=http://".$filimg["url"]."  target=".$filimg["abre"]."><img src=\"imgseccion/".$filimg["imgpag"]."\" border=0 width=".$x." height=".$y." ></a>";}else{ echo "<img src=\"imgseccion/".$filimg["imgpag"]."\"  width=".$x." height=".$y." >"; } }?>
                      </div></td>
                        </tr>
		            <?php } //fin si 2
					  
					  }//fin si 1
					  
					  ?>
					  
					  		               					   
<?php
			 $qrybansup = "SELECT b.* FROM banner b, bannerpag bp WHERE b.feciniban <= '$fecha' AND b.fecfinban > '$fecha' AND b.pub ='Si' AND b.codidi = '$idioma' AND bp.codpos = 4 AND bp.codpag = $link AND b.codban = bp.codban ORDER BY bp.orden ASC";
						  $resbansup = mysql_query($qrybansup, $enlace);
			
			  $numbansup = mysql_num_rows($resbansup);
			  
			  if($numbansup> 0){
?>
		  <tr>
        <td height="115" colspan="3" align="center" valign="top" bgcolor="#FFFFFF" class="pointer"><?php 
			  
							
				echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
				$contador = 0;		
				while($filbansup=mysql_fetch_assoc($resbansup)){
				
				if($filbansup["abre"]=="_parent"){
				$abresup = 1;
				}else{
				$abresup = 2;
				}
				if($contador == 4){
				echo"<tr><td height='5'></td></tr>";
				$contador=0;
				}
				echo "<td valign='top' width = '100' align='center' >";
				//actualizo carga de banner
				$bansup = $filbansup["codban"];
				$qryactban = "UPDATE banner SET impban = impban + 1 WHERE codban = $bansup";
				$resactban = mysql_query($qryactban, $enlace);
				//
				$datos = GetImageSize('banner/'.$filbansup["imgban"].''); 
				$xsup = $datos[0]; 
				$ysup = $datos[1]; 
				if($filbansup["tipimg"]==1){

				?>
		                  <script type="text/javascript" language="javascript" >
		var params = {menu: "false", wmode: "transparent", loop: "false" };
		var attributes = {};
		swfobject.embedSWF("banner/<?php echo $filbansup["imgban"] ?>", "contenido<?php echo $filbansup["codban"]?>", "<?php echo $xsup?>", "<?php echo $ysup?>", "9.0.0", "javascripts/expressInstall.swf", "", params, attributes, wmode="transparent");
		                    </script>
		                  <div id="contenido<?php echo $filbansup["codban"];?>">
		          Requiere Flash<br>
		          <a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a>                    </div>
		                 
		                 
              <div id="myDiv<?php echo $filbansup["codban"]?>"  >                        </div>              <?php 
				  
				  } else{  if ($filbansup["manvin"]==1){ echo"  <span onClick=contadorimg(".$filbansup["codban"].")>";
       echo "<a href=http://".$filbansup["url"]."  target=".$filbansup["abre"]."><img src=\"banner/".$filbansup["imgban"]."\" border=0 width=".$xsup." height=".$ysup." ></a></span>";}else{ echo "<img src=\"banner/".$filbansup["imgban"]."\"  width=".$xsup." height=".$ysup." >"; } }
;
					 
echo" </td>";
				 
$contador++;
}//fin while
  echo "</table>" ;   
							 
			  ?></td>
		  </tr>
<?php 
}

?>

		               
					    <tr>
						
		                    <?php 
			  $qrybanizq = "SELECT b.* FROM banner b, bannerpag bp WHERE b.feciniban <= '$fecha' AND b.fecfinban > '$fecha' AND b.pub = 'Si' AND b.codidi = '$idioma' AND bp.codpos = 3 AND bp.codpag = $link AND b.codban = bp.codban ORDER BY bp.orden ASC";
			  $resbanizq = mysql_query($qrybanizq, $enlace);
			
			  $numbanizq = mysql_num_rows($resbanizq);
			  
			  $qrybander = "SELECT b.* FROM banner b, bannerpag bp WHERE b.feciniban <= '$fecha' AND b.fecfinban > '$fecha' AND b.pub = 'Si' AND b.codidi = '$idioma' AND bp.codpos = 1 AND bp.codpag = $link AND b.codban = bp.codban ORDER BY bp.orden ASC";
			  $resbander = mysql_query($qrybander, $enlace);
			
			  $numbander = mysql_num_rows($resbander);
			  
			  if($numbanizq > 0 && $numbander > 0){
			  	$ancho = 514;
			  }else if( $numbanizq == 0 && $numbander == 0){
				$ancho = 954;
			  }else{
			 	$ancho = 734;
			  }
			  
			  
			  if($numbanizq> 0){
			  ?>
		          
		            <td  width="220" align="center" valign="top" class="textonegro"  ><?php 
				
				
				
							
				//echo "<table border='0' cellpadding='0' cellspacing='0'>";
						
				while($filbanizq=mysql_fetch_assoc($resbanizq)){
				
				if($filbanizq["abre"]=="_parent"){
				$abresup = 1;
				}else{
				$abresup = 2;
				}
				echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
				echo "<tr><td valign=top>";
				//actualizo carga de banner
				$banizq = $filbanizq["codban"];
				$qryactban = "UPDATE banner SET impban = impban + 1 WHERE codban = $banizq";
				$resactban = mysql_query($qryactban, $enlace);
				//
				$datos = GetImageSize('banner/'.$filbanizq["imgban"].''); 
				$xsup = $datos[0]; 
				$ysup = $datos[1]; 
				if($filbanizq["tipimg"]==1){

				?>
		                  <script type="text/javascript" language="javascript" >
		var params = {menu: "false", wmode: "transparent", loop: "false" };
		var attributes = {};
		swfobject.embedSWF("banner/<?php echo $filbanizq["imgban"] ?>", "contenido<?php echo $filbanizq["codban"]?>", "<?php echo $xsup?>", "<?php echo $ysup?>", "9.0.0", "javascripts/expressInstall.swf", "", params, attributes, wmode="transparent");
		                    </script>
		                  <div id="contenido<?php echo $filbanizq["codban"];?>">
		          Requiere Flash<br>
		          <a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a>                    </div>
		              
                  <div id="myDiv<?php echo $filbanizq["codban"]?>" >                        </div>		                  <?php 
				  
				  } else{  if ($filbanizq["manvin"]=="2"){ echo"  <span onClick=contadorimg(".$filbanizq["codban"].")>";
       echo "<a href=http://".$filbanizq["url"]."  target=".$filbanizq["abre"]."><img src=\"banner/".$filbanizq["imgban"]."\" border=0 width=".$xsup." height=".$ysup." ></a></span>";}else{ echo "<img src=\"banner/".$filbanizq["imgban"]."\"  width=".$xsup." height=".$ysup." >"; } }
;
					 
echo" </td>";
echo "</tr>";
echo "<tr>";
	echo"<td height='5'> </td>";
	echo "</tr>";			 

}//fin while
echo "</table>" ;   
							 
			  ?></td>
	                  
		          <?php } ?> 
						  
              <td width="<?php echo $ancho; ?>" valign="top" style="padding-left:10px; padding-right:10px"><?php echo html_entity_decode( $filimg["intpag"] );?></td>
	        <?php 

			  
			
			  if($numbander> 0){
			  ?>
		          
		            <td  width="220" align="center" valign="top" class="textonegro"  ><?php 
			
				//echo "<table border='0' cellpadding='0' cellspacing='0'>";
						
				while($filbander=mysql_fetch_assoc($resbander)){
				
				if($filbander["abre"]=="_parent"){
				$abresup = 1;
				}else{
				$abresup = 2;
				}
				echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
				echo "<tr><td valign=top>";
				//actualizo carga de banner
				$bander = $filbander["codban"];
				$qryactban = "UPDATE banner SET impban = impban + 1 WHERE codban = $bander";
				$resactban = mysql_query($qryactban, $enlace);
				//
				$datos = GetImageSize('banner/'.$filbander["imgban"].''); 
				$xsup = $datos[0]; 
				$ysup = $datos[1]; 
				if($filbander["tipimg"]==1){

				?>
		                  <script type="text/javascript" language="javascript" >
		var params = {menu: "false", wmode: "transparent", loop: "false" };
		var attributes = {};
		swfobject.embedSWF("banner/<?php echo $filbander["imgban"] ?>", "contenido<?php echo $filbander["codban"]?>", "<?php echo $xsup?>", "<?php echo $ysup?>", "9.0.0", "javascripts/expressInstall.swf", "", params, attributes, wmode="transparent");
		                    </script>
		                  <div id="contenido<?php echo $filbander["codban"];?>">
		          Requiere Flash<br>
		          <a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a>                    </div>
		                
                  <div id="myDiv<?php echo $filbander["codban"]?>" >                        </div>		                  <?php 
				  
				  } else{  if ($filbander["manvin"]==1){ echo"  <span onClick=contadorimg(".$filbander["codban"].")>";
       echo "<a href=http://".$filbander["url"]."  target=".$filbander["abre"]."><img src=\"banner/".$filbander["imgban"]."\" border=0 width=".$xsup." height=".$ysup." ></a></span>";}else{ echo "<img src=\"banner/".$filbander["imgban"]."\"  width=".$xsup." height=".$ysup." >"; } }
;
					 
echo" </td>";
echo "</tr>";
echo "<tr>";
	echo"<td height='5'> </td>";
	echo "</tr>";			 

}//fin while
echo "</table>" ;   
							 
			  ?></td>
	                  
		          <?php } ?> 
		  </tr>
		               					   
<?php
			 $qrybaninf = "SELECT b.* FROM banner b, bannerpag bp WHERE b.feciniban <= '$fecha' AND b.fecfinban > '$fecha' AND b.pub = 'Si' AND b.codidi = '$idioma' AND bp.codpos = 2 AND bp.codpag = $link AND b.codban = bp.codban ORDER BY bp.orden ASC";
						  $resbaninf = mysql_query($qrybaninf, $enlace);
			
			  $numbaninf = mysql_num_rows($resbaninf);
			  
			  if($numbaninf> 0){
?>
		  <tr>
        <td height="115" colspan="3" align="center" valign="top" bgcolor="#FFFFFF"><?php 
			  
							
				echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
				$contador = 0;		
				while($filbaninf=mysql_fetch_assoc($resbaninf)){
				
				if($filbaninf["abre"]=="_parent"){
				$abresup = 1;
				}else{
				$abresup = 2;
				}
				if($contador == 4){
				echo"<tr><td height='5'></td></tr>";
				$contador=0;
				}
				echo "<td valign='top' width = '100' align='center' >";
				//actualizo carga de banner
				$baninf = $filbaninf["codban"];
				$qryactban = "UPDATE banner SET impban = impban + 1 WHERE codban = $baninf";
				$resactban = mysql_query($qryactban, $enlace);
				//
				$datos = GetImageSize('banner/'.$filbaninf["imgban"].''); 
				$xsup = $datos[0]; 
				$ysup = $datos[1]; 
				if($filbaninf["tipimg"]==1){

				?>
		                  <script type="text/javascript" language="javascript" >
		var params = {menu: "false", wmode: "transparent", loop: "false" };
		var attributes = {};
		swfobject.embedSWF("banner/<?php echo $filbaninf["imgban"] ?>", "contenido<?php echo $filbaninf["codban"]?>", "<?php echo $xsup?>", "<?php echo $ysup?>", "9.0.0", "javascripts/expressInstall.swf", "", params, attributes, wmode="transparent");
		                    </script>
		                  <div id="contenido<?php echo $filbaninf["codban"];?>">
		          Requiere Flash<br>
		          <a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a>                    </div>
		                 
              <div id="myDiv<?php echo $filbaninf["codban"]?>"  >                        </div>              <?php 
				  
				  } else{  if ($filbaninf["manvin"]==1){ echo"  <span onClick=contadorimg(".$filbaninf["codban"].")>";
       echo "<a href=http://".$filbaninf["url"]."  target=".$filbaninf["abre"]."><img src=\"banner/".$filbaninf["imgban"]."\" border=0 width=".$xsup." height=".$ysup." ></a></span>";}else{ echo "<img src=\"banner/".$filbaninf["imgban"]."\"  width=".$xsup." height=".$ysup." >"; } }
;
					 
echo" </td>";
				 
$contador++;
}//fin while
  echo "</table>" ;   
							 
			  ?></td>
		  </tr>
<?php 
}

?>
		                </table>		              </td>
          </tr>		           

 <tr>
		      <td height="22" valign="top"  ><img src="images/piepagina.png" width="954" height="20" /></td>
		  </tr>					 
				    
					  
					  <tr>
					    <td height="99" valign="top"  ><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textoblanco">
					      <!--DWLayoutTable-->
					      <tr>
					        <td width="954" height="13"  ></td>
	                      </tr>
					      <tr>
					        <td height="23" align="center" valign="top"  > 
							<div>
							   <strong><?PHP echo $filetiqueta["regional"];?></strong>:
                                 <span class="textoazuloscuro">
								 <?php $qrysuc = "SELECT  s.codsuc, s.nomsuc FROM sucemp AS s ORDER BY s.nomsuc";
												$ressuc = mysql_query($qrysuc, $enlace);
												$numsuc = mysql_num_rows($ressuc);
												$contador = 1;
												while($filsuc=mysql_fetch_assoc($ressuc)){ 
												echo "<a class='clase1' href=script/asisomos/regionales.php?cod=".$filsuc["codsuc"].">".$filsuc["nomsuc"]."</a>";
												if($contador < $numsuc){
												echo "- ";
												}
												$contador ++;
												}
												?>
    </span>    &nbsp;</td>
	                      </tr>
					      
					      
					      <tr>
					        <td height="36" align="center" valign="top"  ><span class="textoblancop">Desarrollador por</span> <a href="http://www.ti-point.com" target="_blank"><img src="images/por.png" width="21" height="21" border="0" align="absmiddle" title="Tipoint ltda." /></a></td>
	                      </tr>
					      <tr>
					        <td height="16"  ></td>
				          </tr>
					      
					      
				        </table></td>
          </tr>
      </table>
	  
	  </form>
	
    <!-- InstanceEndEditable --></td>
  </tr>
</table>
</body>
<!-- InstanceEnd --></html>
