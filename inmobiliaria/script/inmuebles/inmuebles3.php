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


$pro = $_GET["codinm"];

$qrypro = "SELECT
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
	inmuebles.desinmueble
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
include("../base/menu.php") ;
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
}</style>
<link href="../../css/cliente.css" rel="stylesheet" type="text/css" />


<link href="../../css/cliente.css" rel="stylesheet" type="text/css" >
</head>
<body>
<table width="100%" border="0"  align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr>
    <td width="954" height="220" valign="top" align="left">
	
	<form method="post" name="form1" action="" id="form1" enctype="multipart/form-data" >
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegrominisitio" >
        <!--DWLayoutTable-->
      
				
	<tr><td width="12" height="14"></td>
	  <td width="954" valign="top"><table  width="100%" border="0" cellpadding="0" cellspacing="0"  class="textonegrominisitio">
                <!--DWLayoutTable-->
                                                                                                                                                                                                                                 
                <tr>
                  <td height="199" colspan="3" align="right" valign="top">
                  <div style="position:relative;top:10px"><img src="../../images/empresa.png" border="0"/></div></td>
                  <td colspan="2" valign="top"><div  id="horiz-menu" align="left"   class="nav" style="width:954px; background-image:url(../../images/fondomenu.png); background-repeat:no-repeat; height:52px; position:relative; padding-left:40px">
                    <?php
			 menu($idioma);
?>
                            </div></td>
		
		
		
		
                    <td width="141" colspan="3" align="left" valign="bottom" ><?php echo "<div align='right' class='textogris'>Esta en - ".$filpro["nominmueble"]."</div>";?></td>
                </tr>
                <tr>
                  <td width="25" height="49">&nbsp;</td>
                  <td rowspan="2" align="center"  valign="top" bgcolor=""     ><br>               
			  
			  <div style="background-image:url(../../images/fondobusqueda.png); background-repeat:no-repeat; width:954px; height:248px" align="center">
			  
			  <div style="position:relative;">
			  
			  <table cellpadding="0" cellspacing="0" align="left" width="954" >
			  
			  <tr><td width="253" class="textogrisb">Ubicacion</td>
			  <td width="340" class="textogrisb">Rango de Precios</td>
			  </tr>
			  <tr><td width="253" class="textogrisb">  <Select    name="cbo1zonasi"  class="contactenos-form-select2" id="cbo1zonasi" title= "Zona de la Ciudad">
                <option value="0" >Elige</option>
                <?
					
					$qryzona= "SELECT zn.codzona, zn.nomzona FROM zona AS zn ORDER BY zn.nomzona ";
					$reszona = mysql_query($qryzona, $enlace);
					while ($filzona = mysql_fetch_array($reszona))
					echo "<option value=\"".$filzona["codzona"]."\">".$filzona["nomzona"]."</option>\n";
					mysql_free_result($reszona);
				?>
              </select></td><td class="textogrisb">Entre $
			    <input name="txt2nombresi" type="text" class="contactenos-form-textfield2" id="txt2nombresi" maxlength="40">
                                y
                                  $
                              <input name="txt2nombresi2" type="text" class="contactenos-form-textfield2" id="txt2nombresi2" maxlength="40"></td>
							  
							  <td><button class="textonegro"   name="filtrar" type="submit" value="filtrar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer"><span class="textonegro" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img border="0"  src="images/boton.png"  /></span><br>
                  Buscar</button></td>
					  </tr>
			  <tr>
			    <td>&nbsp;</td>
			    <td></td>
				<td></td>
			  </tr>
			  
			  <tr><td class="textogrisb">Tipo de Inmueble</td><td class="textogrisb">Zona</td><td width="329"></td>
			  </tr>
			    <tr>
			      <td class="textogrisb"><select  name="cbo1codinmueblesi" id="cbo1codinmueblesi" class="contactenos-form-select2">
                    <option value="0">Elige</option>
                    <?
					$qryinmueble= "SELECT inm.* FROM inmuebletipo AS inm ORDER BY inm.nomtipinmueble ";
					$resinmueble = mysql_query($qryinmueble, $enlace);
					while ($filinmueble = mysql_fetch_array($resinmueble ))
					echo "<option value=\"".$filinmueble["codtipinmueble"]."\">".$filinmueble["nomtipinmueble"]."</option>\n";
					mysql_free_result($resinmueble );
				?>
                  </select></td>
			      <td class="textogrisb"><select name="cbo2codareasi" class="contactenos-form-select2" id="cbo2codareasi" title="Área de contacto">
                                <option value="0">Elige</option>
                                <?
	$qryarea = "SELECT ac.codarea, acd.nomarea  FROM areacon ac, areacondet acd  WHERE ac.estado = 'Activa' AND ac.codarea = acd.codarea AND acd.codidi = $idioma ORDER BY acd.nomarea";
	$resarea = mysql_query($qryarea, $enlace);
	while ($filarea = mysql_fetch_array($resarea))
		echo "<option value=\"".$filarea["codarea"]."\">".$filarea["nomarea"]."</option>\n";
		mysql_free_result($resarea);
	?>
                              </select></td><td></td></tr>
			  </table>
			  </div>
			  </div>			  </td>
                  <td width="120">&nbsp;</td>
                </tr>
                <tr>
                  <td height="341" colspan="2" valign="top" bgcolor="#F9F9F9"><table width="66%" border="0" cellpadding="0" cellspacing="0" class="textonegrominisitio">
                    <!--DWLayoutTable-->
                    <tr>
                      <td width="1267" height="288" align="center" valign="top" bgcolor="#F9F9F9" style="padding-top:10px"><?php echo $filpro["nominmueble"]?><br /><img  src="../../administractor/inmuebles/<?php echo  $filpro["imginmueble"]; ?>" name="pro"  width="300" ><br />                        
                        <br /></td>
                    </tr>
                    
                    
                    <tr>
                      <td height="53" align="center" valign="middle"  class = "textonegrot" ><?php 
	echo "<table width='100%'><tr>";
		echo"<td align = 'center' valign='top'><a  href=\"../../administractor/inmuebles/".$filpro["imginmueble"]."\" rel='lightbox' class='lightbox'> <img  src=\"../../administractor/inmuebles/mini/".$filpro["imginmueble"]."\"  border=\"0\" width=\"100\" </a>";
		echo "<p>";
		echo "</td>";
		$qryvis = "SELECT codinmueblevis, imginmueble FROM inmueblesvis WHERE codinmueble = '$pro'";
		$resvis = mysql_query($qryvis, $enlace);
		$numvis = mysql_num_rows($resvis);
		if ($numvis > 0){
		$contador=1;
			while($filvis=mysql_fetch_assoc($resvis)){
			if($contador == 3){
			echo"<tr><td height='0'  colspan='6'></td></tr>";
			$contador =0;
			}
				echo "<td valign='top'><a  href=\"../../administractor/inmuebles/vistas/".$filvis["imginmueble"]."\" rel='lightbox' class='lightbox'> <img  src=\"../../administractor/inmuebles/vistas/".$filvis["imginmueble"]."\"  border=\"0\" width=\"100\" </a>";
				
				echo "<p>";
				echo "</td>";
				$contador++;
			}
		}
		echo"</table>";
	?></td>
	                </tr>
                  </table></td>
                    <td colspan="3" align="left" valign="top" class="textonegrominisitio" style="padding-left:10px" ><br />
                      CARACTERISTICAS
                      <?php echo html_entity_decode($filpro["desinmueble"]);?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                  <td height="1"></td>
                  <td width="275"></td>
                  <td width="252"></td>
                  <td width="874"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                </table></td>
	  <td width="54"></td>
	</tr>	               					   
	                  </table>
     
					      <tr>
					        <td height="36" align="center" valign="top"  ><!--DWLayoutEmptyCell-->&nbsp;</td>
	                      </tr>
					      <tr>
					        <td height="16"  ></td>
			              </tr>
					      


					      
    </table></td>
          </tr>
      </table>
	  
	  </form>
	
   </td>
  </tr>
</table>
</body>
</html>
