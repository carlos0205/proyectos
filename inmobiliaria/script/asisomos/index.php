<?php
session_start();
include("../../administractor/fyles/general/conexion.php") ;
require '../../administractor/fyles/general/useronline.php';	
$enlace=enlace();
online();


//incluímos la clase ajax 
require ('../../javascripts/xajax/xajax_core/xajax.inc.php');

//instanciamos el objeto de la clase xajax 
$xajax = new xajax();
$xajax->configure('javascript URI', '../../javascripts/xajax/');

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
$link = "1";
$ip = $_SERVER['REMOTE_ADDR']; 

include("../../administractor/fyles/geoip.inc.php");

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
$qryimg = "SELECT pin.*,ps.animspeed, ps.slices,ptr.nomtrascin FROM pagsiteint as pin 
INNER JOIN pagsite as ps ON ps.codpag=pin.codpag
INNER JOIN pagsitetransiciones as ptr ON ptr.codtrasc=ps.codtrasc
WHERE pin.codidi = '$idioma' AND pin.codpag = '$link'";
$resimg = mysql_query($qryimg, $enlace);
$filimg = mysql_fetch_assoc($resimg);


$qryinfemp = "SELECT telemp, diremp, faxemp, telofiemp, imgfonreq, imgfon, imgfonx, imgfony, colfon, fondofijo, url  FROM licusu";
$resinfemp = mysql_query($qryinfemp, $enlace);
$filinfemp = mysql_fetch_assoc($resinfemp);

//averiguo si existe imagen de seccion diaria
$qryimgdiaria = "SELECT *  FROM pagsiteimgdiaria WHERE codidi = '$idioma' AND codpag = '$link' AND coddiasemana = ".date("N")."";
$resimgdiaria = mysql_query($qryimgdiaria, $enlace);


//averiguo si existe imagen de FONDO diaria
$qryimgfondo = "SELECT *  FROM pagsitefondodiario WHERE codidi = '$idioma' AND coddiasemana = ".date("N")."";
$resimgfondo = mysql_query($qryimgfondo, $enlace);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">


<html lang="es"><!-- InstanceBegin template="/Templates/pl01.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>INVERTIMOS - Venta de finca raiz colombia</title>
<?php 
//En el <head> indicamos al objeto xajax se encargue de generar el javascript necesario 
$xajax->printJavascript(); 

include("../../administractor/fyles/general/metatags.php") ;
?>

<script type="text/javascript" src="../publicaciones/js/mootools.js"></script>
<script type="text/javascript" src="../publicaciones/js/efxMooSer.js"></script>

<link rel="stylesheet" href="../../javascripts/jquery.nyroModal/styles/nyroModal.css" type="text/css" media="screen" />
<script type="text/javascript" src="../../javascripts/jquery.nyroModal/js/jquery.min.js"></script>
<script type="text/javascript" src="../../javascripts/jquery.nyroModal/js/jquery.nyroModal.custom.js"></script>


<script type="text/javascript">
function mostrar(nombreCapa){ 
	if(document.getElementById(nombreCapa).style.visibility=="visible"){
	document.getElementById(nombreCapa).style.display="none"; 
	document.getElementById(nombreCapa).style.visibility="hidden"; 
	}else{
	document.getElementById(nombreCapa).style.display="block"; 
	document.getElementById(nombreCapa).style.visibility="visible"; 
	}
} 


function contadorimg(codban){
	xajax_contadorimg(codban);
}

function verbannermodal(){

$(function () {
  $('.nyroModal').nyroModal().nmCall();
});
}


function registro(){
	
	if(document.form1.txtnom.valu=="" || document.form1.txtema.value==""){
		alert("por favor ingrese su nombre y email");
		exit();
		return false;
	}
	var b=/^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/      
	//devuelve verdadero si validacion OK, y falso en caso contrario
	if (b.test(document.form1.txtema.value)==false)
	{
	alert("El e-mail tiene un formato invalido")
	exit();
	return false;
	}
	
	xajax_registro(xajax.getFormValues("form1"));

}

</script>

<script type="text/javascript" src="../../videos/js/flashembed.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../videos/css/common.css">

  
<script type="text/javascript" src="../../javascripts/swfobject.js"></script>


<style type="text/css">
img, div { behavior: url(../../javascripts/iepngfix.htc)}

A:LINK {text-decoration : none; color : #663399} 
A:VISITED {text-decoration : none; color : #663399} 
A:HOVER {text-decoration : none; color : #663399;} 
A:ACTIVE {text-decoration : none; color : #663399} 

A.clase1:LINK {text-decoration : none; color:#FFFFFF} 
A.clase1:VISITED {text-decoration : none; color : #FFFFFF} 
A.clase1:HOVER {text-decoration : none; color : #FFCC00;} 
A.clase1:ACTIVE {text-decoration : none; color : #FFFFFF} 


body {
margin-top: 0mm;
<?php if($filinfemp["imgfonreq"]=="Si"){
if(mysql_num_rows($resimgfondo)>0){
$filimgfondo = mysql_fetch_assoc($resimgfondo);
?>
background-image: url(../../imgfondodiaria/<?php echo $filimgfondo["imgfondo"];?>);
<?php 
}else{
?>
background-image: url(../../images/<?php echo $filinfemp["imgfon"];?>);
<?php } ?>
background-position:center;
background-position:top;
<?php 
if($filinfemp["fondofijo"]=="Si"){
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
<link href="../../css/cliente.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="954" height="177" border="0"  align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->

  <tr>
    <td height="84" valign="top"><!-- InstanceBeginEditable name="contenido" -->
	<form method="post" name="form1" action="" id="form1" enctype="multipart/form-data" >
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro" >
        <!--DWLayoutTable-->
		
		<tr>
		  <td height="155" colspan="3" valign="middle"  ><script type="text/javascript">
		  	var params = {menu: "false", wmode: "transparent", loop: "false" };
			var attributes = {};
			swfobject.embedSWF("../../swf/menusec<?php echo $idioma?>.swf", "menuindex", "954", "155", "9.0.0", "../../javascripts/expressInstall.swf","", params, attributes);
		          </script>
		                  <div align="center" class="textonegro"  id="menuindex">
		                    Requiere Flash
            <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" 		alt="Get Adobe Flash player" class="textonegro" ></a>		        </div></td>
		  </tr>
		
		     <?php 
				   //si existe imagen diaria la pongo si no comparo contra la seccion
				   if(mysql_num_rows($resimgdiaria)>0){ //if 1
				   $filimgdiaria = mysql_fetch_assoc($resimgdiaria);
				   
				   if($filimgdiaria["tipimg"]<>3){
				   ?>
		            <tr>
		              <td height="78" colspan="3" valign="top"><?php 
			//averiguo extension de imagen
			// $ext = strrchr($filban["imgindex"],'.');
			//$ext = strtolower($ext); if ($ext == ".swf"){
			$datos = GetImageSize('../../imgsecciondiaria/'.$filimgdiaria["imgpag"].''); 
			$x = $datos[0]; 
			$y = $datos[1]; 
			if($filimgdiaria["tipimg"]==1){
			?>
		                <script type="text/javascript">
			var params = {menu: "false", wmode: "transparent", loop: "false" };
			var attributes = {};
			swfobject.embedSWF("../../imgsecciondiaria/<?php echo $filimgdiaria["imgpag"] ?>", "imgsecciondiaria", "<?php echo $x?>", "<?php echo $y?>", "9.0.0", "../../javascripts/expressInstall.swf","", params, attributes);
							</script>
		                <div  id="imgsecciondiaria" align="center"><a href="http://www.adobe.com/go/getflashplayer">requiere flash<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player"/><br></a>
		                  <?php } 
						else
						{  
							
							if ($filimgdiaria["manvin"]=="Si"){ 
							echo "<a href=http://".$filimgdiaria["url"]."  target=".$filimgdiaria["abre"]."><img src=\"../../imgsecciondiaria/".$filimgdiaria["imgpag"]."\" border=0 width=".$x." height=".$y." ></a>";
							}else{
							 echo "<img src=\"../../imgsecciondiaria/".$filimgdiaria["imgpag"]."\"  width=".$x." height=".$y." >"; 
							} 
						}

						?>
                      </div></td>
          </tr>
		  
		  <?php
		   } else {
		   
		   //es slider
		  ?>
		  <tr>
		              <td height="18" colspan="3" valign="top" bgcolor="#FFFFFF">
	<link rel="stylesheet" href="../../javascripts/slider/styles/nivo-slider.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../../javascripts/slider/styles/style.css" type="text/css" media="screen" />

	<script src="../../javascripts/slider/scripts/jquery.nivo.slider.pack.js" type="text/javascript"></script>
	
	<script type="text/javascript">

	$(window).load(function() {
		
		setTimeout(function(){
			$('#slider2').nivoSlider({ pauseTime:5000, pauseOnHover:false,effect:'<?php echo $filimg["nomtrascin"]?>',slices:'<?php echo $filimg["slices"]?>',animSpeed:'<?php echo $filimg["animspeed"]?>'});
		}, 1000);
		
	/*slices: maneja el recorrido de la transicion de la imagen*/	
	/*animSpeed: velocidad en que se muestra la animacion */	
		
		
	});
	</script>


	<div id="wrapper" >
      <div id="slider2" class="nivoSlider" align="center" >
	  <?php
	  $qryslider= "SELECT f.* FROM pagsiteimgdiariaslider AS f WHERE f.codpagimg = '".$filimgdiaria["codpagimg"]."' ORDER BY orden ASC"; 
	  $resslider = mysql_query($qryslider, $enlace);
	  while($filslider=mysql_fetch_assoc($resslider)){
	  			
				$datos = GetImageSize('../../imgsecciondiariaslider/'.$filslider["imgslider"].''); 
				$x = $datos[0]; 
				$y = $datos[1]; 
	
				if ($filslider["manvin"]=="Si"){ 
				echo "<a href=http://".$filslider["url"]."  target=".$filslider["abre"]." alt='' title='".$filslider["intslider"]."'><img src=\"../../imgsecciondiariaslider/".$filslider["imgslider"]."\" border=0 width=".$x." height=".$y." ></a>";
				}else{
				 echo "<a href=href='#'><img src='../../imgsecciondiariaslider/".$filslider["imgslider"]."'  width=".$x." height=".$y." alt='' title='".$filslider["intslider"]."' ></a>"; 
				} 
	  };

	  ?>
    </div>
</div>	</td> 
                        </tr>
								
		  <?php
		  }
		  ?>
		            <?php
				   }else{
				   
				   if($filimg["tipimg"]<>3) { //if2 
				   
				   		if($filimg["tipimg"]<>4){ //if3?> <tr>
		              <td height="78" colspan="3" valign="top"><?php 
	//averiguo extension de imagen
	// $ext = strrchr($filban["imgindex"],'.');
	//$ext = strtolower($ext); if ($ext == ".swf"){
	$datos = GetImageSize('../../imgseccion/'.$filimg["imgpag"].''); 
	$x = $datos[0]; 
	$y = $datos[1]; 
	if($filimg["tipimg"]==1){
	?>
		                <script type="text/javascript">
	var params = {menu: "false", wmode: "transparent", loop: "false" };
	var attributes = {};
	swfobject.embedSWF("../../imgseccion/<?php echo $filimg["imgpag"] ?>", "imgseccion", "<?php echo $x?>", "<?php echo $y?>", "9.0.0", "../../javascripts/expressInstall.swf","", params, attributes);
	                    </script>
		                <div  id="imgseccion" align="center"><a href="http://www.adobe.com/go/getflashplayer">requiere flash<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player"/><br></a><?php } else{  if ($filimg["manvin"]==1){ echo "<a href=http://".$filimg["url"]."  target=".$filimg["abre"]."><img src=\"../../imgseccion/".$filimg["imgpag"]."\" border=0 width=".$x." height=".$y." ></a>";}else{ echo "<img src=\"../../imgseccion/".$filimg["imgpag"]."\"  width=".$x." height=".$y." >"; } }?>
</div></td>
                        </tr>
		            <?php
								}else{
								
								?>
								<tr>
		              <td height="18" colspan="3" valign="top" bgcolor="#FFFFFF">
	<link rel="stylesheet" href="../../javascripts/slider/styles/nivo-slider.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../../javascripts/slider/styles/style.css" type="text/css" media="screen" />

	<script src="../../javascripts/slider/scripts/jquery.nivo.slider.pack.js" type="text/javascript"></script>
	
	<script type="text/javascript">

	$(window).load(function() {
		
		setTimeout(function(){
			$('#slider2').nivoSlider({ pauseTime:5000, pauseOnHover:false,effect:'<?php echo $filimg["nomtrascin"]?>',slices:'<?php echo $filimg["slices"]?>',animSpeed:'<?php echo $filimg["animspeed"]?>' });
		}, 1000);
		
	});
	</script>


	<div id="wrapper1">
      <div id="slider2" class="nivoSlider" >
	  <?php
	  $qryslider= "SELECT f.* FROM pagsiteintslider AS f WHERE f.codpag = '$link' ORDER BY orden ASC"; 
	  $resslider = mysql_query($qryslider, $enlace);
	  while($filslider=mysql_fetch_assoc($resslider)){
	  			
				$datos = GetImageSize('../../imgseccionslider/'.$filslider["imgslider"].''); 
				$x = $datos[0]; 
				$y = $datos[1]; 
	
				if ($filslider["manvin"]=="Si"){ 
				echo "<a href=http://".$filslider["url"]."  target=".$filslider["abre"]." alt='' title='".$filslider["intslider"]."'><img src=\"../../imgseccionslider/".$filslider["imgslider"]."\" border=0 width=".$x." height=".$y." ></a>";
				}else{
				 echo "<a href=href='#'><img src='../../imgseccionslider/".$filslider["imgslider"]."'  width=".$x." height=".$y." alt='' title='".$filslider["intslider"]."' ></a>"; 
				} 
	  };

	  ?>
    </div>
</div>	</td> 
                        </tr>
								<?php
								
								}//fin si3
					
						 } //fin si 2
					  
					  }//fin si 1
					  
					  ?>
					   
					  <tr>
		      <td width="17" height="22" bgcolor="#FFFFFF" ></td>
              <td width="920" valign="top"   bgcolor="#FFFFFF"  ><br>                <?php 
			   echo "<br><span class='textoazul'>".$filimg["nompag"]."</span><br><img src='../../images/lineaseccion.png' width='315' height='2'><br>";
			  echo html_entity_decode( $filimg["intpag"] );
			  	
			  ?>			  		       </td>
	      <td width="17" bgcolor="#FFFFFF" ></td>
		  </tr>
					  <tr>
					    <td height="14" bgcolor="#FFFFFF" ></td>
					    <td   bgcolor="#FFFFFF"  ></td>
					    <td bgcolor="#FFFFFF" ></td>
	      </tr>
					  
					  <tr>
					    <td height="64" bgcolor="#FFFFFF" ></td>
					    <td valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textogris" style="background-image:url(../../images/piedireccion.png); background-repeat:no-repeat">
					      <!--DWLayoutTable-->
					      <tr>
					        <td width="19" height="13"  ></td>
		                    <td width="286"  ></td>
		                    <td width="335"  ></td>
		                    <td width="268"></td>
				            <td width="12"></td>
					      </tr>
					      <tr>
					        <td height="36"  >&nbsp;</td>
		                    <td valign="top"  ><?php echo $filinfemp["diremp"]."<br>".$filinfemp["telemp"]?></td>
		                <td align="center" valign="middle"  > 2011</td>
		                    <td align="right" valign="middle"  >	                    Desarrollado por <a  href="http://www.ti-point.com" target="_blank"><img src="../../images/por.png" width="21" height="21" border="0" align="absmiddle" title="Tipoint ltda." /></a></td>
	                      <td  >&nbsp;</td>
					      </tr>
					      <tr>
					        <td height="15"  ></td>
					        <td  ></td>
					        <td  ></td>
				            <td  ></td>
					        <td  ></td>
					      </tr>
					      
				        </table></td>
					    <td bgcolor="#FFFFFF" ></td>
	      </tr>
					  
					  
					  
					  
					  
					  
					  
				


 <tr>
		      <td height="22" colspan="3" valign="top"  ><img src="../../images/piepagina.png" width="954" height="20" /></td>
		  </tr>					 
      </table>
	  </form>
	
    <!-- InstanceEndEditable --></td>
  </tr>
</table>
</body>
<!-- InstanceEnd --></html>