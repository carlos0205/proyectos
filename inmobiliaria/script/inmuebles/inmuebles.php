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
$pro = $_GET["codinm"];

//actualizo carga de banner
			$qryactbanc = "UPDATE inmuebles SET clicks = clicks + 1 WHERE codinmueble =".$_GET["codinm"]."";
			$resactbanc = mysql_query($qryactbanc, $enlace);

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




$qrypro = "SELECT
    inmuebles.codinmueble,
	inmuebles.codigo
    , inmuebles.nominmueble
    , inmuebles.areainmueble
    , inmuebles.numerohab
	, inmuebles.imginmueble
	, inmuebletipo.nomtipinmueble
	, inmuebles.tiporesponsable
    , deppro.nomdep
    , ciudad.nomciu
	, pais.ci
	,pais.cn
    , barrio.nombar
    , zona.nomzona
	,inmuebles.pub
	,inmuebles.pubini
	,inmuebles.valor
	,u.nomusu,
	inmuebles.desinmueble
	,inmuebles.numeroban
	,inmuebles.nivel
	,inmuebles.clicks,
	pa.paraq
FROM
    inmuebles 
    LEFT JOIN barrio
     ON (inmuebles.codbar = barrio.codbar)
    LEFT JOIN ciudad 
        ON (inmuebles.codciu = ciudad.codciu)
    LEFT JOIN deppro 
        ON (ciudad.coddep = deppro.coddep)
	LEFT JOIN pais 
        ON (deppro.ci = pais.ci)	
    LEFT JOIN inmuebletipo 
        ON (inmuebles.codtipinmueble = inmuebletipo.codtipinmueble) 
    LEFT JOIN zona 
        ON (inmuebles.codzona = zona.codzona)
    LEFT JOIN usuadm AS u ON inmuebles.codusuadm = u.codusuadm
	LEFT JOIN inmuebleparaq AS pa 
	ON inmuebles.codparaq = pa.codparaq
     WHERE  inmuebles.codinmueble = '$pro'  ";
$respro = mysql_query($qrypro, $enlace);

$filpro = mysql_fetch_assoc($respro);


$qrycarro = "SELECT * FROM parprocar";
$rescarro = mysql_query($qrycarro, $enlace);
$filcarro = mysql_fetch_assoc($rescarro);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" >

<title>ESTABLECER INMOBILIARIA S.A.S - Venta de finca raiz colombia</title>
<?php
//En el <head> indicamos al objeto xajax se encargue de generar el javascript necesario 
$xajax->printJavascript();
include("../base/menu2.php") ;
?>

<link rel="stylesheet" href="../../javascripts/ligthbox/css/lightbox.css" type="text/css" media="screen" />
<script src="../../javascripts/ligthbox/js/jquery.min.js" type="text/javascript"></script>
<script src="../../javascripts/ligthbox/jquery.lightbox2.js" type="text/javascript"></script>
<script>
	$(document).ready(function(){
		$(".lightbox").lightbox({
			fitToScreen: true,
			imageClickClose: false
		});

	});

</script>
<link rel="stylesheet" href="../../javascripts/jquery.nyroModal/styles/nyroModal.css" type="text/css" media="screen" />
<script type="text/javascript" src="../../javascripts/jquery.nyroModal/js/jquery.nyroModal.custom.js"></script>
<!--[if IE 6]>
	<script type="text/javascript" src="../../javascripts/jquery.nyroModal/js/jquery.nyroModal-ie6.min.js"></script>
<![endif]-->
<script type="text/javascript" src="../../javascripts/menu/scripts.js"></script>
<script type="text/javascript" src="../../javascripts/menu/jquery.effects.core.js"></script>
<link rel="stylesheet" href="../../javascripts/menu/style.css" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption| Anton' rel='stylesheet' type='text/css'>

<script type="text/javascript">
function contadorimg(codban){
	xajax_contadorimg(codban);
}

function verbannermodal(){

$(function () {
  $('.nyroModal').nyroModal().nmCall();
});
}

function agregarcanasta(pro)
{
	if(eval("document.form1.txt"+pro+".value==0")){
	alert("Debe ingresar una cantidad");
	eval("document.form1.txt"+pro+".focus()")
	return false;
	}else{
	xajax_agregarcanasta(pro, eval("document.form1.txt"+pro+".value"),xajax.getFormValues("form1"));
	return false;
	}
}

</script>


<script type="text/javascript" src="../../base/js/validaform.js"></script>
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
    width: 100px;
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
		  <div style="position:relative;top:10px; width:100%; padding-right:20px"><a href="index.php" border="0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="../../images/empresa.png" border="0"/></a><span style="position:relative; top:-20px; padding-right:30px" align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="../../images/slogan.gif" /></span><div align="right" style="position:relative; float:right; top:-100px; padding-right:10px"><img src="../../images/contact.png" /></div></div>
		  <div  id="horiz-menu" align="left"   class="nav" style="width:954px; background-image:url(../../images/fondomenu.png); background-repeat:no-repeat; height:52px; position:relative; padding-left:40px">
		<?php
			 menu($idioma);
?>
		
		</div>
		  </td>
		  </tr>
					   
					  <tr>
		      <td height="262" valign="top" align="center"  ><div style="background-image:url(../../images/fondobusqueda.png); background-repeat:no-repeat; width:954px; height:253px" align="center">
			  
			  <div style="position:relative; padding-top:50px; padding-left:10px">
			  
			  <table cellpadding="0" cellspacing="0" align="left" width="954" >
			    <!--DWLayoutTable-->
			  
			  <tr><td width="171" height="18" valign="top" class="textogrisb">Codigo del Inmueble </td>
			  <td width="138" class="textogrisb">Inmueble Para</td>
			    <td width="346" class="textogrisb">Rango de Precios</td>
			  <td width="297"></td>
			  </tr>
			  <tr>
			    <td height="63" valign="top" class="textogrisb"><br>
			      <input name="txt2codigoinmueblesi" type="text" class="contactenos-form-textfield2" id="txt2codigoinmueblesi" maxlength="100"></td>
			  <td><select name="cbo2codparaqsi" id="cbo2codparaqsi" title="Inmueble para" class="contactenos-form-textfield3"  >
                <option value="0">Elige</option>
                <?
					$qryinmueble= "SELECT * FROM inmuebleparaq ORDER BY codparaq ";
					$resinmueble = mysql_query($qryinmueble, $enlace);
					while ($filinmueble = mysql_fetch_array($resinmueble ))
					echo "<option value=\"".$filinmueble["codparaq"]."\">".$filinmueble["paraq"]."</option>\n";
					mysql_free_result($resinmueble );
				?>
              </select></td>
			    <td class="textogrisb">Entre $
			      <input name="txt2precio1si" type="text" class="contactenos-form-textfield2" id="txt2precio1si" maxlength="40">
                        y
                          $
                      <input name="txt2precio2si" type="text" class="contactenos-form-textfield2" id="txt2precio2si" maxlength="40"></td>
							  
							  <td><button class="textonegro"   name="buscar" type="submit" value="Buscar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer" onClick="return validacajas()"><span class="textonegro" style="margin: 0px; background-color: transparent; border: none;cursor: pointer"><span class="textonegro" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img border="0"  src="../../images/boton.png"  /></span></span><br>
                  Buscar</button>      		  </td>
                    </tr>
			  <tr>
			    <td height="18" colspan="2" valign="top" class="textogrisb"> Tipo de Inmueble</td>
			    <td valign="top" class="textogrisb">Ubicacion</td>
				<td rowspan="3" align="center"  valign="bottom" style="padding-right:20px;background-image:url(../../images/sigue.png); background-repeat:no-repeat; background-position:top; height:60px"><a href="https://www.facebook.com/pages/Establecer-Inmobiliaria-SAS/394724840665230?fref=ts" target="_blank"><img src="../../images/facebook.png" width="50" border="0" /></a>&nbsp;<a href="https://twitter.com/EstablecerInmob" target="_blank"><img src="../../images/twetter.png" width="50" border="0" /></a>&nbsp;<a href="https://www.youtube.com/channel/UCqfIUrxOGs003a48dJ-zbQg" target="_blank"><img src="../../images/youtube.png" width="50" border="0" /></a>&nbsp;<a href="https://plus.google.com/u/0/105434771678483605104/posts" target="_blank"><img src="../../images/google.png" width="50" border="0" /></a><span style="visibility:hidden"><a href='#Ancla' name="Ancla">Ancla</a></span></td>
			    </tr>
			  <tr>
			    <td height="32" colspan="2" valign="top" class="textogrisb"><select  name="cbo1codinmueblesi"  class="contactenos-form-select2"  id="cbo1codinmueblesi">
                    <option value="0">Elige</option>
                    <?
					$qryinmueble= "SELECT inm.* FROM inmuebletipo AS inm ORDER BY inm.nomtipinmueble ";
					$resinmueble = mysql_query($qryinmueble, $enlace);
					while ($filinmueble = mysql_fetch_array($resinmueble ))
					echo "<option value=\"".$filinmueble["codtipinmueble"]."\">".$filinmueble["nomtipinmueble"]."</option>\n";
					mysql_free_result($resinmueble );
				?>
                  </select></td>
			      <td valign="top" class="textogrisb"><select name="cbo2codareasi" class="contactenos-form-select2" id="cbo2codareasi" title="area de contacto">
                               <option value="0" >Elige</option>
                <?
					
					$qryzona= "SELECT zn.codzona, zn.nomzona FROM zona AS zn ORDER BY zn.nomzona ";
					$reszona = mysql_query($qryzona, $enlace);
					while ($filzona = mysql_fetch_array($reszona))
					echo "<option value=\"".$filzona["codzona"]."\">".$filzona["nomzona"]."</option>\n";
					mysql_free_result($reszona);
				?>
                        </select></td>
			      </tr>
			  
			  
			  <tr>
			    <td height="28" colspan="2"><span class="textogrisb">Departamento</span><br>
                  <select  name="cbo1coddepsi" id="cbo1coddepsi" title="departamentos" class="contactenos-form-select2"  onChange="xajax_ciudades(this.value)" >
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
			    <td id="ciudades"><span class="textogrisb">Ciudad</span><br>
                  <select name="cbo1codciusi" class="contactenos-form-select2"  id="cbo1codciusi" title="ciudades" >
                    <option value="0">Elige</option>
                  </select></td>
				  
				  
				   <?php

			if(isset($_POST["buscar"])){

			 $paraq = $_POST["cbo2codparaqsi"];
			 $codigoinm = $_POST["txt2codigoinmueblesi"];
			 $precio1 = $_POST["txt2precio1si"];
			 $precio2 = $_POST["txt2precio2si"];
			 $tipo = $_POST["cbo1codinmueblesi"];
			 $ubicacion = $_POST["cbo2codareasi"];
			 $ciudad = $_POST["cbo1codciusi"];
			 $departamento = $_POST["cbo1coddepsi"];
				
				$query_registros = "SELECT
      inmuebles.codinmueble
    , inmuebles.nominmueble
    , inmuebles.areainmueble
    , inmuebles.numerohab
	, inmuebles.imginmueble
	, inmuebletipo.nomtipinmueble
	, inmuebles.tiporesponsable
    , deppro.nomdep
    , ciudad.nomciu
	, pais.ci
    , barrio.nombar
    , zona.nomzona
	,inmuebles.pub
	,inmuebles.pubini
	,inmuebles.valor
	,u.nomusu,
	inmuebles.clicks,
	pa.paraq
	,inmuebles.codigo
FROM
    inmuebles 
    LEFT JOIN barrio
     ON (inmuebles.codbar = barrio.codbar)
    LEFT JOIN ciudad 
        ON (inmuebles.codciu = ciudad.codciu)
    LEFT JOIN deppro 
        ON (ciudad.coddep = deppro.coddep)
	LEFT JOIN pais 
        ON (deppro.ci = pais.ci)	
    LEFT JOIN inmuebletipo 
        ON (inmuebles.codtipinmueble = inmuebletipo.codtipinmueble) 
    LEFT JOIN zona 
        ON (inmuebles.codzona = zona.codzona)
    LEFT JOIN usuadm AS u ON inmuebles.codusuadm = u.codusuadm
	LEFT JOIN inmuebleparaq AS pa 
	    ON inmuebles.codparaq = pa.codparaq
	
     WHERE inmuebles.codinmueble > 0 AND inmuebles.pubini='Si' ";


                  if($codigoinm<>''){
						$query_registros .= " AND inmuebles.codigo = '$codigoinm'";
					}

                 /* if($precio1<>''){
						$query_registros .= " AND inmuebles.valor <= '$precio1'";
					}
					
					if($precio2<>''){
						$query_registros .= " AND inmuebles.valor <= '$precio2'";
					}*/
					
					 if($precio1<>'' && $precio2<>'' ){
						$query_registros .= " AND inmuebles.valor between '$precio1' AND '$precio2'";
					}


                    if($tipo<>'0'){
						$query_registros .= " AND inmuebles.codtipinmueble = '$tipo'";
					}


					if($paraq<>"0"){
						$query_registros.= " AND inmuebles.codparaq '$paraq'";
					}
					
					if($ubicacion<>'0'){
						$query_registros .= " AND inmuebles.codzona = '$ubicacion'";
					}
					
					if($departamento<>'0'){
						$query_registros .= " AND ciudad.coddep = '$departamento'";
					}	
					
					
	                if($ciudad<>'0'){
						$query_registros .= " AND inmuebles.codciu = '$ciudad'";
					}	
					

			  	$query_registros.= "  ORDER BY deppro.nomdep, zona.nomzona ";
					$_SESSION["qryfiltroproductos"] = $query_registros;
			

				?>
				
				<script language="javascript" type="text/javascript">

				location='../../index.php';
				target="_blank";
				
				//window.location="http://localhost/establecer/index.php"; target="_blank"; done=1;

				</script>

				<?php

			}

			?>		
			    </tr>
			  </table>
			  </div>
			  
			  
			  </div></td>
	      </tr>
					  
				 <div style="954px; height:100%" align="center">  	  
<tr><td style="padding-left:0px" width="954px"><table width="954" border="0" cellpadding="0" cellspacing="0" align="center" bgcolor="#FBFBFB" >
								    <!--DWLayoutTable-->
								    <tr>
								      <td width="8" height="10">&nbsp;</td>
                                      <td width="300" align="center" valign="top"  ><div style="width:400; background-color:#FFFFFF; padding:10px"><img  src="../../administractor/inmuebles/<?php echo  $filpro["imginmueble"]; ?>" name="pro"  width="400" height="300" border="0" ></div></td>
                      <td width="823" rowspan="2" align="left" valign="top" class="textonegro" style="padding-left:10px; padding-right:25px" >                                                                                                                  <strong><br />
                        <img src="../../images/caracteristicas.png" border="0" title="Caracteristicas del inmueble" /></strong><br />
						 <table cellpadding="0" width="489" cellspacing="0">
						   <!--DWLayoutTable-->
						 <tr><td width="177" height="14"></td>
						   <td width="5"></td>
						   <td width="155"></td>
						   
						   <td width="136" align="right" style="padding-right:3px"><strong>Visitas:</strong><?php echo $filpro["clicks"]; ?></td>
						 </tr>
						 <tr>
						   <td height="38" colspan="2" valign="top">
						<strong>Codigo:</strong>&nbsp;<?php echo $filpro["codigo"]; ?></td>
						   <td colspan="2" align="left" valign="top"><strong>Inmueble Para:</strong>&nbsp;<?php echo $filpro["paraq"]; ?></td>
						   <td width="14"></td>
						   </tr>
						
						<tr>
						  <td height="33" valign="top"><strong>Pais:</strong><?php echo $filpro["cn"]; ?></td>
						  <td colspan="2" valign="top"><strong>Departamento:</strong><?php echo $filpro["nomdep"]; ?></td>
						  <td valign="top"><strong>Ciudad:</strong><?php echo $filpro["nomciu"]; ?></td>
						  <td></td>
						</tr>
						
						<tr><td height="42"><strong>Zona:</strong><?php echo $filpro["nomzona"]; ?></td>
						<td colspan="2"><strong>Barrio:</strong> <?php echo $filpro["nombar"]; ?></td>
						<td>&nbsp;</td>
						<td style="visibility:hidden"> </td>
						</tr>
						
							<tr><td height="43"><strong>Num.Alcobas:</strong><?php echo $filpro["numerohab"]; ?></td>
							<td colspan="2"><strong>Num.Ba&ntilde;os:</strong> <?php echo $filpro["numeroban"]; ?></td>
							<td align="right"><div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script><div class="fb-follow" data-href="https://www.facebook.com/pages/Establecer-Inmobiliaria-SAS/394724840665230?fref=ts" data-width="40" data-height="40" data-colorscheme="light" data-layout="button" data-show-faces="true"></div></td>
							<td></td>
							</tr>
							
							<tr><td height="43"><strong>Area:</strong><?php echo $filpro["areainmueble"]; echo "&nbsp;";echo "MT2" ?></td>
							<td colspan="2"><strong>Niveles:</strong> <?php echo $filpro["nivel"]; ?></td>
							<td align="right"><div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script><div class="fb-send" data-href="https://www.facebook.com/pages/Establecer-Inmobiliaria-SAS/394724840665230?fref=ts" data-width="60" data-height="50" data-colorscheme="light"></div></td>
							<td></td>
							</tr>
						
					<tr><td height="14"></td>
					  <td></td>
					  <td></td>
					  <td></td>
					  <td></td>
					</tr>
					<tr>
					  <td height="14" colspan="4" valign="top" align="justify" >  <div align="justify" ></strong><?php echo "<span class='textonegro' align='justify'>".html_entity_decode($filpro["desinmueble"])."</span>";?>     </div>                                                                 					  
					  <td></td>
					</tr>
                            </table></td>                  
								    </tr>
								    
								    <tr>
								      <td height="3" >&nbsp;</td>
                                      <td align="center" valign="top"   class = "textonegrot" >
							           <?php 
	echo "<table width='300'>
	<tr><td height='10' width='350'><img src='../../images/cam.png' />FOTOGRAFIAS</td></tr>
	
	<tr>";
		echo"<td align = 'center' valign='top'><a  href=\"../../administractor/inmuebles/".$filpro["imginmueble"]."\" rel='lightbox' class='lightbox'> <img  src=\"../../administractor/inmuebles/mini/".$filpro["imginmueble"]."\"  border=\"0\" width=\"120\" height=\"100\" title ='fachada'  /> </a>";
		echo "<p>";
		echo "</td>";
		$qryvis = "SELECT codinmueblevis, imginmueble,comfot FROM inmueblesvis WHERE codinmueble = '$pro'";
		$resvis = mysql_query($qryvis, $enlace);
		$numvis = mysql_num_rows($resvis);
		if ($numvis > 0){
		$contador=1;
			while($filvis=mysql_fetch_assoc($resvis)){
			if($contador == 3){
			echo"<tr><td height='0'  colspan='6'></td></tr>";
			$contador =0;
			}
				echo "<td valign='top'><a  href=\"../../administractor/inmuebles/vistas/".$filvis["imginmueble"]."\" rel='lightbox' class='lightbox'> <img  src=\"../../administractor/inmuebles/vistas/".$filvis["imginmueble"]."\"  border=\"0\" width=\"120\" height=\"100\"  title= \"".$filvis["comfot"]."\"/> </a>";
				
				echo "<p>";
				echo "</td>";
				$contador++;
			}
		}
		echo"</table>";
	?></td>
                                    </tr>
								    
								    
            </table></td></tr>
					  
					 <tr><td> <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Establecer Inmobiliaria -->
<ins class="adsbygoogle"
     style="display:inline-block;width:336px;height:280px"
     data-ad-client="ca-pub-7544925198521753"
     data-ad-slot="3224988026"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script></td></tr> 
					  
					  </div>
					  

					  
				

 <tr>
		      <td height="22" valign="top"  ><img src="../../images/piepagina.png" width="100%" height="20" /></td>
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