<?php
session_start();
include("../../administractor/fyles/general/conexion.php") ;
require '../../administractor/fyles/general/useronline.php';	
include("../../administractor/fyles/general/operaciones.php") ;
require '../../administractor/fyles/class.inputfilter.php';
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
	$lista = "<select name='cbo2coddepsi' id='cbo2coddepsi'  class='contactenos-form-select2' onChange='xajax_ciudades(this.value)'  title='".$filetiqueta["departamento"]."'>/n";
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
	$lista = "<select name='cbo2codciusi' id='cbo2codciusi'  class='contactenos-form-select2'  title='".$filetiqueta["pais"]."'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codciu"]."'>".$fillis["nomciu"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("ciudades","innerHTML",$lista); 
	
	return $respuesta;
}


function ciudades2($dep){
	global $enlace;
	global $filetiqueta;
	$respuesta = new xajaxResponse();
	
	$qrylis ="SELECT c.codciu, c.nomciu FROM ciudad AS c
WHERE c.coddep = $dep ";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo2codciusi' id='cbo2codciusi'  class='contactenos-form-select2'  title='".$filetiqueta["pais"]."'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codciu"]."'>".$fillis["nomciu"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("ciudades2","innerHTML",$lista); 
	
	return $respuesta;
}



//El objeto xajax tiene que procesar cualquier petición 
$xajax->registerFunction("contadorimg"); 
$xajax->registerFunction("registro"); 
$xajax->registerFunction("departamentos");
$xajax->registerFunction("ciudades");
$xajax->registerFunction("ciudades2");
 
$xajax->processRequest();

$fecha = date("Y-n-j H:i:s");
$link = "5";
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

$qryetiqueta = "SELECT * FROM etiquetas WHERE codidi = $idioma";
$resetiqueta = mysql_query($qryetiqueta, $enlace);
$filetiqueta = mysql_fetch_assoc($resetiqueta);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" >

<title>ESTABLECER INMOBILIARIA S.A.S - Venta de finca raiz colombia</title>
<?php 
//En el <head> indicamos al objeto xajax se encargue de generar el javascript necesario 
$xajax->printJavascript(); 

include("../../administractor/fyles/general/metatags.php") ;
include("../base/validaformidi.php") ;
include("../../script/base/menu2.php") ;
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
var pasa = validaenvia()

	if(pasa == false ){
		return false;
	}else{
		
			
		var entrar = confirm("¿Desea enviar la Publicacion?")
		if ( entrar ) 
		{
			return true;	
		}else{
			return false;
		}
	
	}
}

</script>


<script type="text/javascript" src="../../javascripts/validaform.js"></script>
<script type="text/javascript" src="../../videos/js/flashembed.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../videos/css/common.css">
<script type="text/javascript" src="../../javascripts/swfobject.js"></script>


<style type="text/css">

#horiz-menu {
	width: 924px;
	z-index: 99999;
	height: 52px;
	position: relative;
	background-repeat:no-repeat;
	left:350px; top:3px;
	
}

.contactenos-form-textfield {
    background: url("../../images/contactenos-form-textfield.png") no-repeat scroll 0 0 transparent;
    border: 0 none;
    font-family: 'Dosis', sans-serif;
	font-size: 14px;
	font-style: normal;
	font-weight: normal;
	color:#666666;

    height: 28px;
    width: 100px;
	z-index:9999999;
}

.contactenos-form-select2 {
    background: url("../../images/contactenos-form-textfield.png") no-repeat scroll 0 0 transparent;
    border: 0 none;
    color: #666666;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 15px;
    height: 32px;
    padding: 2px 2px 2px 10px;
    width: 205px;
}

.contactenos-form-textfield2 {
    background: url("../../images/contactenos-form-textfield.png") no-repeat scroll 0 0 transparent;
    border: 0 none;
    color: #666666;
    font-size: 15px;
    height: 28px;
    padding: 2px 10px;
    width: 190px;
}

.textogrisb{
font:Arial, Helvetica, sans-serif; font-size:16px; color:#666666;

}

.textonegrob{
font:Arial, Helvetica, sans-serif; font-size:19px; color:#000000;

}


#bljaIMGte{
position:relative left:-35px;
}

#bljaIMGte .bljaIMGtex 
{ 
width:131px;position:absolute;top:2px;left:54px;
}

img, div { behavior: url(../../javascripts/iepngfix.htc)}
A:LINK {text-decoration : none; color:#000000} 
A:VISITED {text-decoration : none; color : #000000} 
A:HOVER {text-decoration : none; color:#666666;} 
A:ACTIVE {text-decoration : none; color : #000000} 

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
overflow-x:hidden;
}
</style>
<link href="../../css/cliente.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="100%" height="177" border="0"  align="center" cellpadding="0" cellspacing="0">


  <tr>
    <td height="84" valign="top" width="100%">
	<form method="post" name="form1" action="" id="form1" enctype="multipart/form-data" >
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro" >
        <!--DWLayoutTable-->
		
		<tr>
		  <td width="954" height="155" valign="middle"  >
		  <div style="position:relative;top:10px; width:100%; padding-right:20px"><a href="index.php" border="0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="../../images/empresa.png" border="0"/></a><span style="position:relative; top:-20px; padding-right:20px" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="../../images/slogan.gif" /></span><div align="right" style="position:relative; float:right; top:10px; padding-right:10px"><img src="../../images/contact.png" /></div></div>
		  <div  id="horiz-menu" align="left"   class="nav" style="width:954px; background-image:url(../../images/fondomenu.png); background-repeat:no-repeat; height:52px; position:relative; padding-left:40px">
		<?php
			 menu($idioma);
?>
		
		</div>
		  </td>
		  </tr>
					   
					  <tr>
		      <td height="12" valign="top" align="center"  ></td>
	      </tr>
					  
				 <div style="954px; height:100%" align="center">  	
				 
				 
				 
				 <tr><td align="center"><img src="../../images/publicacion.png" border="0" /></td></tr>  
<tr><td width="954px" height="404" style="padding-left:20px"><table width="953" border="0" align="center" cellpadding="0" cellspacing="0"  class="textonegro" style="background-image:url(images/fondocontacto.png); background-repeat:no-repeat" >
                            <!--DWLayoutTable-->
                            
                            
                            <tr align="center">
                              <td width="22" height="17"></td>
					          <td width="363"  ></td>
                              <td width="292"  ></td>
                              <td width="276"  ></td>
                            </tr>
                            
                            <tr>
                              <td height="29"></td>
					          <td valign="top" style="padding-left:20px" class="textogrisb">Persona Contacto </td>
                              <td valign="top" class="textogrisb" >E-mail</td>
                              <td valign="top" class="textogrisb">Tel&eacute;fono - M&oacute;vil </td>
                            </tr>
                            
                            
                            
                            <tr>
                              <td height="36"></td>
					          <td valign="top" ><input name="txt2nomconwebsi"  type="text"  id="txt2nomconwebsi" tabindex="0" size="40" maxlength="100" style="background-color:#FFFFFF; padding-left:20px;   height:20px" class="contactenos-form-textfield2" onFocus="style.backgroundColor='#F5F5F5'" onBlur="style.backgroundColor='#FFFFFF'"  title="<?php echo $filetiqueta["nombre"];?>"/></td>
                              <td valign="top" ><input name="txt2emaconwebsi" type="text" id="txt2emaconwebsi" tabindex="0" size="30" maxlength="100"  style="background-color:#FFFFFF;  height:20px" class="contactenos-form-textfield2" onFocus="style.backgroundColor='#F5F5F5'" onBlur="style.backgroundColor='#FFFFFF'"  title="<?php echo $filetiqueta["email"];?>"/></td>
                              <td valign="top" ><input name="txt2telconwebsi" type="text"  style="background-color:#FFFFFF;  height:20px" class="contactenos-form-textfield2" id="txt2telconwebsi" tabindex="0" value="" size="18" maxlength="20" onFocus="style.backgroundColor='#F5F5F5'" onBlur="style.backgroundColor='#FFFFFF'" title="<?php echo $filetiqueta["telefono"];?> " /></td>
                            </tr>
                            
                            
                            <tr>
                              <td height="23"></td>
					          <td valign="top"style="padding-left:20px" class="textogrisb">Inmueble Para </td>
                              <td valign="top" class="textogrisb">Tipo de Inmueble </td>
                              <td valign="top" ><span class="textogrisb">Direccion</span></td>
                            </tr>
                            <tr>
                              <td height="48"></td>
					          <td valign="top" ><select name="cbo2codareasi" id="cbo2codareasi" title="Inmueble para" class="contactenos-form-select2"  >
                                <option value="0">Elige</option>
                                <?
					$qryinmueble= "SELECT * FROM inmuebleparaq ORDER BY codparaq ";
					$resinmueble = mysql_query($qryinmueble, $enlace);
					while ($filinmueble = mysql_fetch_array($resinmueble ))
					echo "<option value=\"".$filinmueble["codparaq"]."\">".$filinmueble["paraq"]."</option>\n";
					mysql_free_result($resinmueble );
				?>
                              </select></td>
					          <td valign="top" ><span class="textogrisb">
					            <select  name="cbo2carconwebsi"  class="contactenos-form-select2"  id="cbo2carconwebsi">
                                  <option value="0">Elige</option>
                                  <?
					$qryinmueble= "SELECT inm.* FROM inmuebletipo AS inm ORDER BY inm.nomtipinmueble ";
					$resinmueble = mysql_query($qryinmueble, $enlace);
					while ($filinmueble = mysql_fetch_array($resinmueble ))
					echo "<option value=\"".$filinmueble["codtipinmueble"]."\">".$filinmueble["nomtipinmueble"]."</option>\n";
					mysql_free_result($resinmueble );
				?>
                                </select>
					          </span></td>
	                          <td valign="top" ><input name="txt2dirconwebsi"  type="text"  style="background-color:#FFFFFF;  height:20px" class="contactenos-form-textfield2"  id="txt2dirconwebsi" tabindex="0" size="30" maxlength="100" onfocus="style.backgroundColor='#F5F5F5'" onblur="style.backgroundColor='#FFFFFF'" title="<?php echo $filetiqueta["direccion"];?> " /></td>
                            </tr>
                            
                            
                            
                            
                            <tr>
                              <td height="22"></td>
                              <td valign="top" style="padding-left:20px" class="textogrisb">Pais</td>
					           
                              <td valign="top" class="textogrisb" >Departamento</td>
	                          <td valign="top" class="textogrisb" >Ciudad</td>
                            <tr>
					          <td height="29"></td>
                              <td valign="top"  ><Select   name="cbo1cisi"  class="contactenos-form-select2" id="cbo1cisi" title="<?php echo $filetiqueta["pais"];?>" onChange="xajax_departamentos(this.value)" >
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
                              <td valign="top"  id="departamentos" > <select  name="cbo2coddepsi" class="contactenos-form-select2" id="cbo2coddepsi" title="<?php echo $filetiqueta["departamento"]?>"  onChange="xajax_ciudades(this.value)" >
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
                              <td valign="top"  id="ciudades" ><select name="cbo2codciusi" class="contactenos-form-select2" id="cbo2codciusi" title="<?php echo $filetiqueta["ciudad"]?>" >
	                              <option value="0">Elige</option>
                              </select></td>
                            <tr>
					          <td height="91"></td>
                              <td colspan="3" valign="top" style="padding-left:20px" class="textogrisb" ><br />
                                Detalles del Inmueble(*)<br>
                                <textarea name="txt2desconwebsi"   cols="54"  rows="2"  style="background-color:#FFFFFF; " class="textogrisb"   id="txt2desconwebsi"  onfocus="style.backgroundColor='#F5F5F5'" onBlur="style.backgroundColor='#FFFFFF'" title="<?php echo $filetiqueta["comentario"]?>"></textarea>
                                <input name="hid1fecconwebsi" type="hidden" id="hid1fecconwebsi" value="<?php echo date("Y-n-j H:i:s");?> " /> 
                                <input name="hid1estconsi" type="hidden" id="hid1estconsi" value="Activo" />                              
                                <input name="hid1codtiptersi" type="hidden" id="hid1codtiptersi" value="1" />                            
                               
								<button class="textonegro" name="enviar" type="submit" id="enviar" value="Enviar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer"   onClick="return crearregistro();"><span class="textonegro" style="margin: 0px; background-color: transparent; border: none;cursor: pointer"><span class="textonegro" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img border="0"  src="../../images/boton2.png"  /></span></span><br>
                  Enviar</button>     
                                <?php

				if (isset($_POST['enviar'])){//if1
					$continua = TRUE;	

					$siguiente=guardar("conweb",1,"codconweb",2);

					//capturo informacion de cajas de texto
				    $percon = $_POST["txt2nomconwebsi"];
					$ema = $_POST["txt2emaconwebsi"];		
					$tel = $_POST["txt2telconwebsi"];
						
					$dir = $_POST["txt2dirconwebsi"];
                    $para = $_POST["cbo2codareasi"];
                    $tipoinm = $_POST["cbo2carconwebsi"];

					/*$area = $_POST["cbo2codareasi"];*/
					$pais = $_POST["cbo1cisi"];
					$departamento = $_POST["cbo2coddepsi"];
					$ciudad = $_POST["cbo2codciusi"];
					$com =$_POST["txt2desconwebsi"];
					
					
					$qrylist ="SELECT inm.* FROM inmuebletipo AS inm ORDER BY inm.nomtipinmueble";
$reslist = mysql_query($qrylist, $enlace);
$filist = mysql_fetch_assoc($reslist);
					
					$qrylisp ="SELECT * FROM inmuebleparaq";
$reslisp = mysql_query($qrylisp, $enlace);
$filisp = mysql_fetch_assoc($reslisp);
					
					
					$qrylis ="SELECT d.coddep, d.nomdep FROM deppro AS d 
WHERE d.coddep= $departamento ";
$reslis = mysql_query($qrylis, $enlace);
$filis = mysql_fetch_assoc($reslis);

	$qrylisc ="SELECT c.codciu, c.nomciu FROM ciudad AS c
WHERE c.codciu = $ciudad ";
	$reslisc = mysql_query($qrylisc, $enlace);
    $filisc = mysql_fetch_assoc($reslisc);


				
					if ( get_magic_quotes_gpc() ){
						$com = htmlspecialchars( stripslashes($com));
					}else{
						$com = htmlspecialchars( $com ) ;
					}
					
					$fecconweb = date("Y-n-j H:i:s ");
							
					////ENVIO DE CORREO
					include_once('../../administractor/fyles/class.phpmailer.php');
					
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
					$body .= "Se ha enviado una nueva publicacion inmueble Web";
					$body .= "<BR><BR>Contacto";
					$body .= "Remitente:".$percon."<BR>";
					/*$body .= "Area Contactada:".$filnomarea["nomarea"]."<BR>";*/
					$body .= "e-mail:".$ema."<BR>";
					$body .= "Teléfono:".$tel."<BR>";
					$body .= "Dirección:".$dir."<BR>";
					$body .= "Para:".$filisp["paraq"]."<BR>";
					$body .= "Tipo Inmueble:".$filist["nomtipinmueble"]."<BR>";
					$body .= "Comentarios:".html_entity_decode($com)."<BR>";
					$body .= "Pais:".$pais."<BR>";
					$body .= "Departamento:".$filis["nomdep"]."<BR>";
					$body .= "Ciudad:".$filisc["nomciu"]."<BR>";
					$body .= "<A href=\"http://".$filema["url"]."/administractor/fyles\"><FONT size=2>Ir al administrador</FONT></A><BR>";
					$body .= "</TBODY></TABLE></P>";
					
					$mail -> Body = $body;
					$mail -> IsHTML (true);
					$archivos = '';
					$msg = "Mensaje Enviado";
					
					if (!$mail -> Send ()){
						$msg = "No se pudo enviar el email";
					}
					?>
				   <script language = JavaScript>
					alert("La Publicacion se ha sido enviada con exito ");
					location="publicar.php";
					</script>
				   <?php			

		}//fin if1	
				?>
                                <br />
                                <br />
                                <br /></td>
                            <tr>
                                <td height="53"></td>
                                <td ></td>
                                <td ></td>
                                <td ></td>
                        </table></td></tr>
					  
					  
					  
					  </div>
					  

					  
				

 <tr>
		      <td height="22" valign="top"  ></td>
		  </tr>					 
      </table>
	  </form>
	
    </td>
  </tr>
</table>
			  <div>
 <script language="javascript" type="text/jscript" > 
 window.location.href='#Ancla'; 
 </script></div>
		
</body>
</html>