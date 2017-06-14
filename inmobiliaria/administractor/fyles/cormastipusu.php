<?php
session_start();
include("general/conexion.php") ;

//incluímos la clase ajax 
require ('../../javascripts/xajax/xajax_core/xajax.inc.php');

//instanciamos el objeto de la clase xajax 
$xajax = new xajax();
$xajax->configure('javascript URI', '../../javascripts/xajax/');
$xajax->processRequest();

include("general/sesion.php");
sesion(1);
include("fckeditor/fckeditor.php") ;

include_once('class.phpmailer.php');

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'cormastipusu.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();
?>
<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<?php
//En el <head> indicamos al objeto xajax se encargue de generar el javascript necesario 
$xajax->printJavascript(); 
?>
<script type="text/javascript"  src="general/validaform.js"></script>
<script language="javascript" type="text/javascript">
function quitar() { 
//alert("No funciona"); 
return false; 
} 
document.oncontextmenu = quitar;
function enviacorreo(){
	xajax.$('correo').innerHTML='enviando mensaje por favor espere.<br><img src="../images/loader.gif" alt="cargando..." border="0">';
}
</script>
<style type="text/css">
body {
	font: small "Trebuchet MS";
}
#disclaimer {
	background-color: #fafafa;
	padding: 1em;
	border: 3px double #ccc;
}
/*************************/
/* Necesario para que se muestre bien los nuevos elementos agregados */
.file {
	display: block;
}
span a {
	margin-left: 1em;
}
/*************************/
input, textarea {
	border:3px double #ccc;
	background-color:#fafafa;
}
</style>
<script type="text/javascript">
var numero = 0;

// Funciones comunes
c= function (tag) { // Crea un elemento
   return document.createElement(tag);
}
d = function (id) { // Retorna un elemento en base al id
   return document.getElementById(id);
}
e = function (evt) { // Retorna el evento
   return (!evt) ? event : evt;
}
f = function (evt) { // Retorna el objeto que genera el evento
   return evt.srcElement ?  evt.srcElement : evt.target;
}

addField = function () {
   container = d('files');
   
   span = c('SPAN');
   span.className = 'file';
   span.id = 'file' + (++numero);

   field = c('INPUT');   
   field.name = 'archivos[]';
   field.type = 'file';
   
   a = c('A');
   a.name = span.id;
   a.href = '#';
   a.onclick = removeField;
   a.innerHTML = 'Quitar';

   span.appendChild(field);
   span.appendChild(a);
   container.appendChild(span);
}
removeField = function (evt) {
   lnk = f(e(evt));
   span = d(lnk.name);
   span.parentNode.removeChild(span);
}
</script>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" -->

<!-- InstanceEndEditable -->
<link href="../css/contenido.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
background-image:url(../images/fondomacaw.jpg);
background-position:center;
background-attachment:fixed;

}
-->
</style>

</head>

<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="width: 100%; height: 400px ">
  <!--DWLayoutTable-->
  <tr>
    <td width="300" height="49" valign="top" bgcolor="#000000"><img src="../images/encabezado.png" width="300" height="49" /></td>
    <td width="100%" valign="bottom" bgcolor="#000000" class="textogris" style="background-image:url(../images/fon_adm.png)"><div align="right"><a href="general/cerrar_sesion.php"><img src="../images/cerses.png" alt="Cerrar Ses&oacute;n de Usuario" width="150" height="32" border="0" /></a></div></td>
  </tr>
  <tr>
    <td height="19" colspan="2" valign="top" bgcolor="#F5F5F5"><?php if ($_SESSION["grupo"] == 1){ ?><script type="text/javascript" language="JavaScript1.2" src="../js/mnusuperadm.js"></script><?php }else{ ?><script type="text/javascript" language="JavaScript1.2" src="../js/mnuadm.js"></script><?php } ?></td>
  </tr>
  <tr>
    <td height="313" colspan="2" valign="top"><!-- InstanceBeginEditable name="contenido" -->
	<form id="form1" name="form1" method="post" action=""  onSubmit="enviacorreo()" enctype="multipart/form-data">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<!--DWLayoutTable-->
	<tr>
	<td height="59" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
	<!--DWLayoutTable-->
	<tr>
	<td width="15" height="22"></td>
	<td width="943">&nbsp;</td>
	<td width="50"></td>
	<td width="54" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="enviarno" type="submit" value="enviarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"  onClick=""><img width="32" src="../images/aplicar.png"  /><br>
                  Enviar</button></td>
	<td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
	<td width="15"></td>
	</tr>
	<tr>
	  <td height="15"></td>
	  <td valign="top" >Usuario: <?php echo $_SESSION["logueado"]?></td>
	<td></td>
	  <td></td>
	  </tr>
	
	
	<tr>
	<td height="22" colspan="2" valign="top" class="textoerror"><div align="right">
	<?php
	//boton aplicar cambios
	if (isset($_POST['enviarno'])){
		$asunto = $_POST["txtasu"];
		$men = $_POST["txtmen"];
		$tipusu = $_POST["seltip"];
	
		$continua = TRUE;
		if ($men ==""){
			//refresco contenido
			echo"El campo descripción de mensaje no puede estar vacio";
			$continua = FALSE;
		}else{
			if(get_magic_quotes_gpc()){
				$men = htmlspecialchars( stripslashes( $men ) ) ;
			}else{
				$men = htmlspecialchars( $men ) ;
			}
		}
		if($continua)//1
		{	
			//$men = $men."<br><br><br><br> Programa Elaborado por: TIPOINT Ltda. Teléfono: (7) 6523756 <a href=\"http://www.ti-point.com\" target=\"_blank\">www.ti-point.com</a>";	
			$qryema = "SELECT  nomemp, emaemp, telemp, url  FROM licusu ";
			$resema = mysql_query($qryema, $enlace);
			$filema= mysql_fetch_array($resema);
	
			$idi = $_POST["selidi"];				
			$qryter = "SELECT emater FROM tercli WHERE envpro = '2' AND codtipusuter='$tipusu' AND codidi = '$idi'";
			$rester = mysql_query($qryter, $enlace); 
	
			//amplio tiempo de ejecucion del script
			if(mysql_num_rows($rester) > 50){
				set_time_limit (mysql_num_rows($rester)*2);
			}
			//almaceno mensaje
			$qrymen = "insert into men value ('$men')";
			$resmen = mysql_query($qrymen, $enlace);
	
			//consulto mensaje
			$qrymen1 = "SELECT desmen FROM men ";
			$resmen1 = mysql_query($qrymen1, $enlace); 
			$filmen = mysql_fetch_array($resmen1);
		
			//dirección del remitente 
			$envia=$filema["emaemp"];
			$enviados = 0;
			$sinenviar = 0;	
	
			//ciclo de envio de correo a clientes
			while($filter=mysql_fetch_array($rester)){	
				//direccion destino		
				$destinatario =  $filter["emater"];
				$mail = new phpmailer (); # Crea una instancia
				$mail -> From = $envia;
				$mail -> FromName = $filema["nomemp"]; # Puede obtenerse del formulario, por facilidad se hace de esta manera
				$mail -> AddAddress ($destinatario);
				$mail -> Subject = $asunto;
				$body = "<P><TABLE style='WIDTH:100%' border=0>";
				$body .= "<TBODY>";
				$body .= "<TR>";
				$body .= "<TD>";
				$body .= html_entity_decode($filmen["desmen"]);
				$body .= "</TD></TR>";
				$body .= "</TBODY></TABLE></P>";
				$mail -> Body = $body;
				$mail -> IsHTML (true);
				$archivos = '';
				$msg = "Mensaje Enviado";
				//borro mensaje
				$consultamen1 = "delete FROM men ";
				$resultadomen1 = mysql_query($consultamen1, $enlace); 
				if(isset($_FILES["archivos"])) { # Si es que se subió algún archivo
					$msg .= "<ul>";
					foreach ($_FILES["archivos"]["error"] as $key => $error) { # Iterar sobre la colección de archivos
						if ($error == UPLOAD_ERR_OK) { // Si no hay error
							$tmp_name = $_FILES["archivos"]["tmp_name"][$key];
							$name = $_FILES["archivos"]["name"][$key];
							$msg .= "<li>$name</li>";
							$name = uniqid('bc') . '_' . $name; # Generar un nombre único para el archivo
							$mail -> AddAttachment ($tmp_name, $name); # Añade el archivo adjunto
							/*Si se van a guardar los archivos en un directorio, deberían descomentarse
							las siguientes líneas, si se van a guardar los nombres 
							de los archivos en una base de datos, aquí debería realizarse algo...					
							move_uploaded_file($tmp_name, "ruta/directorio/$name"); # Guardar el archivo en una ubicación, debe tener los permisos necesarios*/
						} #if
					} # foreach
					$msg .= '</ul>';
				} # if
				if (!$mail -> Send ()){
					//$msg = "No se pudo enviar el email";
					$sinenviar ++;
				}else{
					//$msg = "se han enviado Mensaje enviado con éxito";	
					$enviados ++;	
				}				
			} // fin while
			$total = mysql_num_rows($rester);
			echo "Se han enviado $enviados correos de $total. No pudo enviarse $sinenviar correos.";
		}//FIN $CONTINUA
	} 
	//boton cancelar cambios
	if (isset($_POST['cancelarno'])){
		echo '<script language = JavaScript>
		location = "index1.php";
		</script>';
	}
	?>                
	</div></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	</table></td>
	</tr>
	<tr>
	<td width="4" height="25">&nbsp;</td>
	<td width="1379">&nbsp;</td>
	<td width="11">&nbsp;</td>
	</tr>
	<tr>
	<td height="52">&nbsp;</td>
	<td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
	<!--DWLayoutTable-->
	<tr>
	<td width="1390" height="52" valign="top" class="titulos"><img src="../images/correo.png" width="48" height="48" align="absmiddle" /> Correo masivo por tipo de usuario [Envia]  </td>
	</tr>
	</table></td>
	</tr>
	<tr>
	<td height="344">&nbsp;</td>
	<td valign="top"><table width="58%" height="339" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
	<!--DWLayoutTable-->
	<tr>
	<td width="10" height="13"></td>
	<td width="55"></td>
	<td width="689"></td>
	<td width="121"></td>
	<td width="275"></td>
	<td width="19"></td>
	</tr>
	<tr>
	<td height="28"></td>
	<td colspan="2" valign="top" ><p>Asunto del Mensaje  
	<input name="txtasu" type="text" id="txtasu" size="40" value = "<?php if (isset($_POST['txtnom'])) echo $_POST['txtnom']; ?>"maxlength="50" />
	Tipo de Usuario 
	<select name="seltip" id="seltip">
	<?
	if (isset($_POST['seltip'])){
		$tip=$_POST['seltip'];
		$qrytip = "SELECT codtipusuter, nomtipusuter FROM tipusuter WHERE codtipusuter <> '$tip' AND codtipusuter <> '1' ORDER BY nomtipusuter ";
		$qrytip1 = "SELECT codtipusuter, nomtipusuter FROM tipusuter WHERE codtipusuter = '$tip' ";
		$restip1 = mysql_query($qrytip1,$enlace);
		$filtip1 = mysql_fetch_array($restip1);
		echo "<option selected value=\"".$filtip1['codtipusuter']."\">".$filtip1['nomtipusuter']."</option>\n";
		mysql_free_result($restip1);
	}else{
		$qrytip = "SELECT codtipusuter, nomtipusuter FROM tipusuter WHERE codtipusuter <> '1' ORDER BY nomtipusuter ";
	}
	$restip = mysql_query($qrytip, $enlace);
	while ($filtip = mysql_fetch_array($restip))
		echo "<option value=\"".$filtip["codtipusuter"]."\">".$filtip["nomtipusuter"]."</option>\n";
		mysql_free_result($restip);
	?>
	</select>
	</p></td>
	<td colspan="2" rowspan="3" valign="top"><div id="correo"  align="right"></div></td>
	<td></td>
	</tr>
	<tr>
	<td height="17"></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr>
	<td height="23"></td>
	<td valign="top" >Mensaje</td>
	<td valign="top">Idioma para envio de correspondencia
	<select name="selidi" id="selidi">
	<?
	if (isset($_POST['selidi'])){
		$idi=$_POST['selidi'];
		$qryidi = "SELECT codidi, nomidi FROM idipub WHERE codidi <> '$idi' ORDER BY nomidi ";
		$qryidi1 = "SELECT codidi, nomidi FROM idipub WHERE codidi = '$idi' ";
		$residi1 = mysql_query($qryidi1,$enlace);
		$filidi1 = mysql_fetch_array($residi1);
		echo "<option selected value=\"".$filidi1['codidi']."\">".$filidi1['nomidi']."</option>\n";
		mysql_free_result($residi1);
	}else{
		$qryidi = "SELECT codidi, nomidi FROM idipub ORDER BY nomidi ";
	}
	$residi = mysql_query($qryidi, $enlace);
	while ($filidi = mysql_fetch_array($residi))
		echo "<option value=\"".$filidi["codidi"]."\">".$filidi["nomidi"]."</option>\n";
		mysql_free_result($residi);
	?>
	</select>
	</span></td>
	<td></td>
	</tr>
	<tr>
	<td height="17"></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	<tr>
	<td height="182"></td>
	<td colspan="4" valign="top"><?php
	// Automatically calculates the editor base path based on the _samples directory.
	// This is usefull only for these samples. A real application should use something like this:
	// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
	
	$oFCKeditor = new FCKeditor('txtmen') ;
	$oFCKeditor->BasePath = '../fyles/fckeditor/';
	
	if (isset($_POST['txtdes'])){
		$oFCKeditor->Value = $_POST['txtmen'] ;
	}else{
		$oFCKeditor->Value = "" ;
	}
	$oFCKeditor->Create() ;
	?></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td height="28"></td>
	<td colspan="3" valign="top"><label>Archivos Adjuntos</span>:</label>
	&nbsp;&nbsp;&nbsp;<a href="#" class="titmenu" accesskey="5" onClick="addField()">A&ntilde;adir Archivo</a><br /><div id="files"></div> </td>
	<td></td>
	<td></td>
	</tr>
	<tr>
	<td height="25"></td>
	<td colspan="3" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
	<td></td>
	<td></td>
	</tr>
	<tr>
	<td height="32"></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td></td>
	<td></td>
	</tr>
	</table></td>
	<td>&nbsp;</td>
	</tr>
	</table>
	</form>
    <!-- InstanceEndEditable --></td>
  </tr>
  
  <tr>
    <td height="32" colspan="2" valign="top"><div align="center" class="textonegro"><img src="../images/guacamayo.png" width="40" height="81" align="middle">  <strong>ADMIN-WEB</strong> , </div></td>
  </tr>
</table>
</form>
</body>
<!-- InstanceEnd --></html>