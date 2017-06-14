<?php
session_start();
include("../../administrador/componentes/general/conexion.php") ;
require '../../administrador/componentes/general/useronline.php';	
include("../../administrador/componentes/general/operaciones.php") ;
require '../../administrador/componentes/class.inputfilter.php';
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


function departamentos($pais){
	global $enlace;
	global $filetiqueta;
	$respuesta = new xajaxResponse();
	
	$qrylis ="SELECT d.coddep, d.nomdep FROM deppro AS d 
WHERE d.ci= $pais ";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1coddepsi' id='cbo1coddepsi'  class='textonegro' onChange='xajax_ciudades(this.value)'  title='".$filetiqueta["departamento"]."'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["coddep"]."'>".$fillis["nomdep"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("departamentos","innerHTML",$lista); 
	
	return $respuesta;
}
function ciudades($dep){
	global $enlace;
	global $filetiqueta;
	$respuesta = new xajaxResponse();
	
	$qrylis ="SELECT c.codciu, c.nomciu FROM ciudad AS c
WHERE c.coddep = $dep ";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1codciusi' id='cbo1codciusi'  class='textonegro'  title='".$filetiqueta["pais"]."'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codciu"]."'>".$fillis["nomciu"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("ciudades","innerHTML",$lista); 
	
	return $respuesta;
}



//El objeto xajax tiene que procesar cualquier petición 
$xajax->registerFunction("contadorimg"); 
$xajax->registerFunction("registro"); 
$xajax->registerFunction("departamentos");
$xajax->registerFunction("ciudades");
 
$xajax->processRequest();

$fecha = date("Y-n-j H:i:s");
$link = "7";
$ip = $_SERVER['REMOTE_ADDR']; 

include("../../administrador/componentes/geoip.inc.php");

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

$qryetiqueta = "SELECT nombre, email, telefono, movil, percontacto, direccion, pais, ciudad, departamento, comentario, formatoinvalido, elcampo, novacio, porfavorseleccione, deseaenviarcontacto FROM etiquetas WHERE codidi = $idioma";
$resetiqueta = mysql_query($qryetiqueta, $enlace);
$filetiqueta = mysql_fetch_assoc($resetiqueta);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<html lang="es"><!-- InstanceBegin template="/Templates/pl01.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->

<title>SYSCOM  AGENCIA DE SEGUROS LTDA</title>
<?php 
//En el <head> indicamos al objeto xajax se encargue de generar el javascript necesario 
$xajax->printJavascript(); 

include("../../administrador/componentes/general/metatags.php") ;
include("../base/validaformidi.php") ;
include("../../script/base/menusec.php") ;
?>

<script type="text/javascript" src="../../script/publicaciones/js/mootools.js"></script>
<script type="text/javascript" src="../../script/publicaciones/js/efxMooSer.js"></script>


<link rel="stylesheet" href="../../javascripts/jquery.nyroModal/styles/nyroModal.css" type="text/css" media="screen" />
<script type="text/javascript" src="../../javascripts/jquery.nyroModal/js/jquery.min.js"></script>
<script type="text/javascript" src="../../javascripts/jquery.nyroModal/js/jquery.nyroModal.custom.js"></script>

<script type="text/javascript" src="../../javascripts/menu/scripts.js"></script>
<script type="text/javascript" src="../../javascripts/menu/jquery.effects.core.js"></script>
<link rel="stylesheet" href="../../javascripts/menu/style.css" type="text/css" />

<script type="text/javascript">


function contadorimg(codban){
	xajax_contadorimg(codban);
}

function verbannermodal(){

$(function () {
  $('.nyroModal').nyroModal().nmCall();
});
}


function crearregistro(){
var pasa = validaenviaidi()

	if(pasa == false ){
		return false;
	}else{
		
		if(document.form1.cbo1cisi.value==144 && document.form1.cbo1codciusi.value == 0){
			alert("<?php echo $filetiqueta["porfavorseleccione"]." ".$filetiqueta["ciudad"] ?>" );
			document.form1.cbo1codciusi.focus();
			return false;
			exit();
		}	
		var entrar = confirm("¿<?php echo $filetiqueta["deseaenviarcontacto"] ?>");
		if ( entrar ) 
		{
			return true;	
		}else{
			return false;
		}
	
	}
}


</script>

<script type="text/javascript" src="../../script/base/js/validaform.js"></script>
<script type="text/javascript" src="../../videos/js/flashembed.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../videos/css/common.css">

  
<script type="text/javascript" src="../../javascripts/swfobject.js"></script>
<script type="text/javascript" src="../../script/inicial/js/favoritos.js"></script>

<style type="text/css">
img, div { behavior: url(../../javascripts/iepngfix.htc) }

A:LINK {text-decoration : none; color : #000000;  font-family: Verdana,Arial, Helvetica, sans-serif;
	font-size: 9px;
	font-style: normal;
	font-weight: normal;
	color:#333333;} 
A:VISITED {text-decoration : none; color : #003366} 
A:HOVER {text-decoration : none; color : #1F98DD;} 
A:ACTIVE {text-decoration : none; color : #000000} 

A.clase1:LINK {text-decoration : none; color:#1F98DD} 
A.clase1:VISITED {text-decoration : none; color : #1F98DD} 
A.clase1:HOVER {text-decoration : none; color : #1F98DD;} 
A.clase1:ACTIVE {text-decoration : none; color : #1F98DD} 


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
}
</style>
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
	<form method="post" name="form1" action="" id="form1"  enctype="multipart/form-data" >
      <table width="100%" border="0" cellpadding="0" cellspacing="0"  class="textoblanco" >
        <!--DWLayoutTable-->
		
		<tr>
		  <td height="25" colspan="3" valign="top"  ><!--DWLayoutEmptyCell-->&nbsp;</td>
		  </tr>
		<tr>
		  <td width="270" height="100"   ><script type="text/javascript">
		  	var params = {logo: "false", wmode: "transparent", loop: "false" };
			var attributes = {};
			swfobject.embedSWF("../../swf/logo.swf", "logo", "270", "100", "9.0.0", "../../javascripts/expressInstall.swf","", params, attributes);
		          </script>
		                  <div  class="textonegro"  id="logo">
		                    Requiere Flash
            <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" 		alt="Get Adobe Flash player" class="textonegro" ></a>		        </div></td>
		  <td colspan="2" valign="top"  ><!--DWLayoutEmptyCell-->&nbsp;</td>
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
				echo "<a href=http://".$filslider["url"]."  target=".$filslider["abre"]." alt=''><img src=\"../../imgsecciondiariaslider/".$filslider["imgslider"]."\" border=0 width=".$x." height=".$y."  title='".$filslider["intslider"]."'></a>";
				}else{
				 echo "<a ><img src='../../imgsecciondiariaslider/".$filslider["imgslider"]."'  width=".$x." height=".$y." alt='' title='".$filslider["intslider"]."' ></a>"; 
				} 
	  };

	  ?>
	    </div>
      </div></td> 
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
	swfobject.embedSWF("imgseccion/<?php echo $filimg["imgpag"] ?>", "imgseccion", "<?php echo $x?>", "<?php echo $y?>", "9.0.0", "../../javascripts/expressInstall.swf","", params, attributes);
	                    </script>
		                <div  id="imgseccion" align="center"><a href="http://www.adobe.com/go/getflashplayer">requiere flash<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player"/><br></a><?php } else{  if ($filimg["manvin"]==1){ echo "<a href=http://".$filimg["url"]."  target=".$filimg["abre"]."><img src=\"../../imgseccion/".$filimg["imgpag"]."\" border=0 width=".$x." height=".$y." ></a>";}else{ echo "<img src=\"../../imgseccion/".$filimg["imgpag"]."\"  width=".$x." height=".$y." >"; } }?>
                        </div></td>
                        </tr>
		            <?php
								}else{
								
								?>
								<tr>
		              <td height="19" colspan="3" valign="top" bgcolor="#FFFFFF">
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
				echo "<a href=http://".$filslider["url"]."  target=".$filslider["abre"]." alt=''><img src=\"../../imgseccionslider/".$filslider["imgslider"]."\" border=0 width=".$x." height=".$y."  title='".$filslider["intslider"]."' ></a>";
				}else{
				 echo "<a ><img src='../../imgseccionslider/".$filslider["imgslider"]."'  width=".$x." height=".$y." alt='' title='".$filslider["intslider"]."' ></a>"; 
				} 
	  };

	  ?>
	    </div>
      </div></td> 
						</tr>
                       
                           <?php
								
								}//fin si3
					
						 } //fin si 2
					  
					  }//fin si 1
					  
					  ?>
								<tr>
								   <td  height="44"  colspan="3" valign="top" style="background-image:url(../../images/fondomenu.png); background-repeat:no-repeat"><div id="horiz-menu" class="nav" align="left" >
		             <?php
					 menu($idioma);
					  ?>            
                                  </div></td>
		  </tr>
								<tr>
								  <td height="14" colspan="3" valign="top" bgcolor="#FFFFFF" class="textonegro" style="background-image:url(../../images/contenedor.png); background-repeat:no-repeat; padding-left:10px"><span >
								    <?php 
						
							echo "<span class='textoazulinmuebles'><strong><br>&nbsp;<img src='../../images/lineaseccion6.png' border='0' align='absmiddle'>&nbsp;&nbsp;".$filimg["nompag"]."<br></strong><br></span>";
			                echo "<br>";
							echo html_entity_decode( $filimg["intpag"] );
				
							
							 ?>
                                  </span></td>
		  </tr>
								
					   
					 
					  <tr>
					    <td height="305" colspan="3" valign="top"  ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF"  class="textonegro" style="background-image:url(images/fondocontacto.png); background-repeat:no-repeat" >
                            <!--DWLayoutTable-->
                            
                            
                            <tr align="center">
                              <td width="10" height="17"></td>
					          <td width="375"  ></td>
                              <td width="292"  ></td>
                              <td width="277"  ></td>
                            </tr>
                            
                            <tr>
                              <td height="29"></td>
					          <td valign="top"  ><?php echo $filetiqueta["nombre"]; ?> (*)</td>
                              <td valign="top" ><?php echo $filetiqueta["email"]; ?> (*)</td>
                              <td valign="top" ><?php echo $filetiqueta["telefono"]; ?> (*) </td>
                            </tr>
                            
                            
                            
                            <tr>
                              <td height="36"></td>
					          <td valign="top" ><input name="txt2nomconwebsi"  type="text"  id="txt2nomconwebsi" tabindex="0" size="40" maxlength="100" style="background-color:#FFFFFF;   height:20px" class="textonegro" onFocus="style.backgroundColor='#F5F5F5'" onBlur="style.backgroundColor='#FFFFFF'"  title="<?php echo $filetiqueta["nombre"];?>"/></td>
                              <td valign="top" ><input name="txt2emaconwebsi" type="text" id="txt2emaconwebsi" tabindex="0" size="30" maxlength="100"  style="background-color:#FFFFFF;  height:20px" class="textonegro" onFocus="style.backgroundColor='#F5F5F5'" onBlur="style.backgroundColor='#FFFFFF'"  title="<?php echo $filetiqueta["email"];?>"/></td>
                              <td valign="top" ><input name="txt2telconwebsi" type="text"  style="background-color:#FFFFFF;  height:20px" class="textonegro" id="txt2telconwebsi" tabindex="0" value="" size="18" maxlength="20" onFocus="style.backgroundColor='#F5F5F5'" onBlur="style.backgroundColor='#FFFFFF'" title="<?php echo $filetiqueta["telefono"];?> " /></td>
                            </tr>
                            
                            
                            <tr>
                              <td height="23"></td>
					          <td valign="top" ><?php echo $filetiqueta["percontacto"]; ?></td>
                              <td valign="top" ><?php echo $filetiqueta["direccion"]; ?></td>
                              <td valign="top" ><!--DWLayoutEmptyCell-->&nbsp;</td>
                            </tr>
                            <tr>
                              <td height="38"></td>
					          <td valign="top" ><input name="txt1conwebsi"  type="text"  style="background-color:#FFFFFF;  height:20px" class="textonegro" id="txt1conwebsi" tabindex="0" size="40" maxlength="100" onFocus="style.backgroundColor='#F5F5F5'" onBlur="style.backgroundColor='#FFFFFF'" title="<?php echo $filetiqueta["percontacto"];?> "  /></td>
					          <td valign="top" ><input name="txt1dirconwebsi"  type="text"  style="background-color:#FFFFFF;  height:20px" class="textonegro"  id="txt1dirconwebsi" tabindex="0" size="30" maxlength="100" onFocus="style.backgroundColor='#F5F5F5'" onBlur="style.backgroundColor='#FFFFFF'" title="<?php echo $filetiqueta["direccion"];?> " /></td>
	                          <td valign="top" ><!--DWLayoutEmptyCell-->&nbsp;</td>
                            </tr>
                            
                            
                            
                            
                            <tr>
                              <td height="22"></td>
                              <td valign="top" ><?php echo $filetiqueta["pais"]; ?></td>
					           
                  <td valign="top" ><?php echo $filetiqueta["departamento"]; ?></td>
	                          <td valign="top" ><?php echo $filetiqueta["ciudad"]; ?></td>
                            <tr>
					          <td height="29"></td>
                              <td valign="top"  ><Select   name="cbo1cisi"  class="textonegro" id="cbo1cisi" title="<?php echo $filetiqueta["pais"];?>" onChange="xajax_departamentos(this.value)" >
                                <option value="144" >Colombia</option>
                                <?
					
					$qrypais= "SELECT p.ci, p.cn FROM pais AS p WHERE ci <> 144
					ORDER BY p.cn ";
					$respais = mysql_query($qrypais, $enlace);
					while ($filpais = mysql_fetch_array($respais))
					echo "<option value=\"".$filpais["ci"]."\">".$filpais["cn"]."</option>\n";
					mysql_free_result($respais);
				?>
                              </select></td>
                              <td valign="top"  id="departamentos" > <select  name="cbo1coddepsi" class="textonegro" id="cbo1coddepsi" title="<?php echo $filetiqueta["departamento"]?>"  onChange="xajax_ciudades(this.value)" >
                                <option value="0">Elige</option>
                                <?
					
					$qrydep= "SELECT d.* FROM deppro AS d WHERE ci = 144
					ORDER BY d.nomdep ";
					$resdep = mysql_query($qrydep, $enlace);
					while ($fildep = mysql_fetch_array($resdep))
					echo "<option value=\"".$fildep["coddep"]."\">".$fildep["nomdep"]."</option>\n";
					mysql_free_result($resdep);
				?>
                              </select></td>
                              <td valign="top"  id="ciudades" ><select name="cbo1codciusi" class="textonegro" id="cbo1codciusi" title="<?php echo $filetiqueta["ciudad"]?>" >
	                              <option value="0">Elige</option>
                              </select></td>
                            <tr>
					          <td height="91"></td>
                              <td colspan="3" valign="top" ><br />
                                <?php echo $filetiqueta["comentario"]; ?>(*)<br />
                                <textarea name="txt2desconwebsi"   cols="94"  rows="2"  style="background-color:#FFFFFF; " class="textonegro"   id="txt2desconwebsi"  onfocus="style.backgroundColor='#F5F5F5'" onBlur="style.backgroundColor='#FFFFFF'" title="<?php echo $filetiqueta["comentario"]?>"></textarea>
                                <input name="hid1fecconwebsi" type="hidden" id="hid1fecconwebsi" value="<?php echo date("Y-n-j H:i:s");?> " /> 
                                <input name="hid1estconsi" type="hidden" id="hid1estconsi" value="Activo" />                              
                                <input name="hid1codtiptersi" type="hidden" id="hid1codtiptersi" value="1" />                            
                                <input name="enviar"  type="submit" id="enviar" onClick=" return crearregistro() " value="Enviar" >
                                <?php

				if (isset($_POST['enviar'])){//if1
					$continua = TRUE;	

					$siguiente=guardar("conweb",1,"codconweb",2);

					//capturo informacion de cajas de texto
					$nom = $_POST["txt2nomconwebsi"];
					$ema = $_POST["txt2emaconwebsi"];		
					$tel = $_POST["txt2telconwebsi"];
					$percon = $_POST["txt1conwebsi"];	
					$dir = $_POST["txt1dirconwebsi"];
					/*$area = $_POST["cbo2codareasi"];*/
					$pais = $_POST["cbo1cisi"];
					$departamento = $_POST["cbo1coddepsi"];
					$ciudad = $_POST["cbo1codciusi"];
					$com =$_POST["txt2desconwebsi"];
				
					if ( get_magic_quotes_gpc() ){
						$com = htmlspecialchars( stripslashes($com));
					}else{
						$com = htmlspecialchars( $com ) ;
					}
					
					$fecconweb = date("Y-n-j H:i:s ");
							
					////ENVIO DE CORREO
					include_once('../../administrador/componentes/class.phpmailer.php');
					
					// Indica si los datos provienen del formulario
					$asunto= "Contacto Web";	
					$qryema = "SELECT nomemp, emaemp, url FROM licusu ";
					$resema = mysql_query($qryema, $enlace);
					$filema= mysql_fetch_assoc($resema);
					
					/*//direccion de usuario responsable del area	
					$qyremaarea = "SELECT u.emausu FROM areacon ac, usuadm u WHERE ac.codarea = $area AND ac.codusuadm = u.codusuadm ";	
					$resemaarea = mysql_query($qyremaarea, $enlace);
					$filemaarea = mysql_fetch_assoc($resemaarea);*/
				
					/*//consulto nombre de areea contactada
					$qrynomarea = "SELECT acd.nomarea FROM areacondet acd WHERE acd.codarea = $area AND acd.codidi = 1";
					$resnomarea = mysql_query($qrynomarea, $enlace);
					$filnomarea = mysql_fetch_assoc($resnomarea);*/
				
					//direcci&oacute;n del remitente 
					$envia=$filema["emaemp"];	
		
					//direccion destino		
					/*$destinatario =  $filemaarea["emausu"];*/
					$destinatario = $filema["emaemp"];
					
					$mail = new phpmailer (); # Crea una instancia
					$mail -> From = $envia;
					$mail -> FromName = $filema["nomemp"]; # Puede obtenerse del formulario, por facilidad se hace de esta manera
					$mail -> AddAddress ($destinatario);
					$mail -> AddBCC($envia);
					$mail -> Subject = $asunto;
					
					$body = "<P><TABLE border=0>";
					$body .= "<TBODY>";
					$body .= "<TR>";
					$body .= "<TD>";
					$body .= "Hola. Se ha enviado un contacto Web, para ampliar la informaci&oacute;n ingrese al administrador del sitio Web";
					$body .= "<BR><BR>Contacto";
					$body .= "Remitente:".$nom."<BR>";
					/*$body .= "Area Contactada:".$filnomarea["nomarea"]."<BR>";*/
					$body .= "e-mail:".$ema."<BR>";
					$body .= "Teléfono:".$tel."<BR>";
					$body .= "Dirección:".$dir."<BR>";
					$body .= "Comentarios:".html_entity_decode($com)."<BR>";
					$body .= "Pais:".$pais."<BR>";
					$body .= "Departamento:".$departamento."<BR>";
					$body .= "Ciudad:".$ciudad."<BR>";
					$body .= "<A href=\"http://".$filema["url"]."/administrador/componentes\"><FONT size=2>Ir al administrador</FONT></A><BR>";
					$body .= "</TBODY></TABLE></P>";
					
					$mail -> Body = $body;
					$mail -> IsHTML (true);
					$archivos = '';
					$msg = "Mensaje Enviado";
					
					if (!$mail -> Send ()){
						$msg = "No se pudo enviar el email";
					}
					/*?>
				   <script language = JavaScript>
					alert("<?php echo $filetiqueta["contactoexito"]?>");
					location="index.php";
					</script>
				   <?php	*/		

		}//fin if1	
				?>
                                <br />
                                <br />
                                <br /></td>
                            <tr>
                                <td height="10"></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                        </table></td>
		  </tr>
					  
					  
					  <tr>
					    <td height="90" colspan="2" valign="middle" class="textonegro" style="background-image:url(../../images/piepagina.png); background-repeat:no-repeat; padding-left:10px" ><span class="textoazul"><strong>&nbsp;&nbsp;<br>
				        SUCURSALES</strong> :<br>
                        </span><span class="textogris">&nbsp;&nbsp;
                        <br>
                        <?php $qrysuc = "SELECT  s.codsuc, s.nomsuc FROM sucemp AS s
ORDER BY s.nomsuc";
	$ressuc = mysql_query($qrysuc, $enlace);
	$numsuc = mysql_num_rows($ressuc);
	$contador = 1;
	while($filsuc=mysql_fetch_assoc($ressuc)){ 
	echo "<a href='../../script/sucursales/suc.php?cs=".$filsuc["codsuc"]."' ><span  >".$filsuc["nomsuc"]." </span></a>";
	if($contador < $numsuc){
	echo "- ";
	}
	$contador ++;
	}
	?>
                        <br>
                        <span style="padding-right:10px"><br>
Todos los derechos reservados 2011                        </span></span></td>
		  <td width="224" align="right" valign="middle" style="background-image:url(../../images/contenedor2.png);   " ><span class="textogris" >Desarrollado por <a  href="http://www.ti-point.com" target="_blank"><img src="../../images/por.png" alt="desarrollado por" width="21" height="21" border="0" align="absmiddle" title="Tipoint ltda." />&nbsp;&nbsp;&nbsp;</a></span></td>
		  </tr>
					  <tr>
					    <td height="0"></td>
					    <td width="460"></td>
					    <td></td>
	      </tr>
      </table>
	  </form>
	
    <!-- InstanceEndEditable --></td>
  </tr>
</table>
</body>
<!-- InstanceEnd --></html>