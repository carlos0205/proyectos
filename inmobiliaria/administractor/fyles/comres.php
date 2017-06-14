<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

include("fckeditor/fckeditor.php") ;


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'comres.php';
$usu = $_SESSION["enlineadm"];
permisos($usu, $prog);

$enlace = enlace();

//capturo codigo de evento
$cod = $_GET["cod"];

$fectoday = date("Y-n-j H:i:s ");

if ($_SESSION["grupo"] == 2){

//consulto codigo de area que tiene asignada el usuario
$codusuadm = $_SESSION["enlineaadm"]; 

	$qrycom = "SELECT cw.*, p.cn, d.nomdep, c.nomciu, acd.nomarea, u.nomusu, HOUR(TIMEDIFF('$fectoday', cw.fecconweb)) AS tietra , ttd.nomtipter
		FROM conweb AS cw 
		LEFT JOIN ciudad  AS c ON cw.codciu = c.codciu
		LEFT JOIN deppro AS d ON c.coddep = d.coddep
		LEFT JOIN pais AS p ON d.ci = p.ci
		LEFT JOIN areacon AS ac ON cw.codarea = ac.codarea
		LEFT JOIN areacondet AS acd ON ac.codarea = acd.codarea AND acd.codidi = 1
		LEFT JOIN usuadm AS u  ON ac.codusuadm = u.codusuadm 
		LEFT JOIN tipter AS tt ON cw.codtipter = tt.codtipter 
		LEFT JOIN tipterdet AS ttd ON tt.codtipter = ttd.codtipter
		WHERE  cw.estcon ='Activo' AND u.codusuadm = $codusuadm AND cw.codconweb = $cod";

	$rescom = mysql_query($qrycom, $enlace);
	$numcom = mysql_num_rows($rescom);
	if($numcom ==0){
	?>
	<script language = JavaScript>
	location = "comlis.php";
	</script>
	<?php
	}
	
}else{
	$qrycom = "SELECT cw.*,  p.cn, d.nomdep, c.nomciu, acd.nomarea, u.nomusu, HOUR(TIMEDIFF('$fectoday', cw.fecconweb)) AS tietra, ttd.nomtipter 
		FROM conweb AS cw 
		LEFT JOIN ciudad  AS c ON cw.codciu = c.codciu
		LEFT JOIN deppro AS d ON c.coddep = d.coddep
		LEFT JOIN pais AS p ON d.ci = p.ci
		LEFT JOIN areacon AS ac ON cw.codarea = ac.codarea
		LEFT JOIN areacondet AS acd ON ac.codarea = acd.codarea AND acd.codidi = 1
		LEFT JOIN tipter AS tt ON cw.codtipter = tt.codtipter 
		LEFT JOIN tipterdet AS ttd ON tt.codtipter = ttd.codtipter
		LEFT JOIN usuadm AS u  ON ac.codusuadm = u.codusuadm 
		WHERE  cw.estcon ='Activo' AND cw.codconweb = $cod";
	$rescom = mysql_query($qrycom, $enlace);
}
$filcom = mysql_fetch_assoc($rescom);

//consulto parametros comentario
$qrypar= "SELECT * FROM compar";
$respar = mysql_query($qrypar, $enlace);
$filpar = mysql_fetch_assoc($respar);

?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
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


function crearregistro(){

var pasa = validaenvia()

	if(pasa == false ){
		return false;
	}else{	
			
		var entrar = confirm("¿Desea actualizar el registro?")
		if ( entrar ) 
		{
			return true;	
		}else{
			return false;
		}
	
	}
	
}

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
<style type="text/css">
<!--
.Estilo1 {color: #000000}
-->
</style>
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
	  <form id="form1" name="form1" method="post" action=""  onSubmit="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="61" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="5" height="22"></td>
                  <td width="965">&nbsp;</td>
                  <td width="25">&nbsp;</td>
                  <td width="57" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"  onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Enviar</button></td>
                  <td width="78" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="14"></td>
            </tr>
            <tr>
              <td height="15"></td>
              <td valign="top" >Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="26" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php
				//boton aplicar cambios
				if (isset($_POST['aplicarno'])){//1
					$continua = TRUE;
					//texto corto
					
					$res = $_POST["txtres"];
					if ($res ==""){
						//refresco contenido
						echo"El campo respuesta no puede estar vacio";
						$continua = FALSE;
					}else{
						if (get_magic_quotes_gpc()){
							$res = htmlspecialchars( stripslashes( $res) ) ;
						}else{
							$res = htmlspecialchars( $res ) ;
						}
					}
					if($continua){//2
						$fecres = date("Y-n-j H:i:s ");
						$qryres = "INSERT INTO resconweb VALUES ('0', '$cod', '$fecres', '$res', '$usu')";
						$resres = mysql_query($qryres, $enlace);
							
						$qrycomest = "UPDATE conweb SET estcon = 'Respondido' WHERE codconweb = '$cod' ";
						$rescomest = mysql_query($qrycomest, $enlace);
						
						auditoria($_SESSION["enlineaadm"],'Contacto Web',$cod,'8');
							
						$idi = $_POST["selidi"];
							
						//valido seleccion de envio de respuesta a cliente via email
						$envcor = $_POST["envcor"];
							
						if($envcor == 2){
							////////ENVIO DE MENSAJE////////
							include_once('class.phpmailer.php');
												
							$consultaema = "SELECT nomemp, emaemp, telemp, url FROM licusu ";
							$resultadoema = mysql_query($consultaema, $enlace);
							$filaema= mysql_fetch_array($resultadoema);
							
							//dirección del remitente 
							$envia=$filaema["emaemp"];	
										
							//direccion destino		
							$destinatario = $filcom["emaconweb"];
								
							$mail = new phpmailer (); # Crea una instancia
							$mail -> From = $envia;
							$mail -> FromName = $filaema["nomemp"]; # Puede obtenerse del formulario, por facilidad se hace de esta manera
							$mail -> AddAddress ($destinatario);
								
							switch ($idi){
								case 1://español
									$asunto= "Respuesta a contacto Web";	
									$mail -> Subject = $asunto;
									$body = "<P><TABLE style=\"WIDTH: 529px; HEIGHT: 175px\" cellSpacing=1 cellPadding=0 width=529 border=0 class=textonegro>";
									$body .= "<TBODY>";
									$body .= "<TR>";
									$body .= "<TD>";
									$body .= "<p><FONT color=gray size=2><IMG style=\"WIDTH: 245px; HEIGHT: 168px\" hspace=0 src=\"http://".$filaema["url"]."/msg/sercli.jpg\" align=left border=0>";
									$body .= "Hola.";
									$body .= "<BR><BR>Gracias por ponerte en contacto con ".$filaema["nomemp"]." Te estamos respondiendo en atención a tu contacto realizado el día ".$filcom["fecconweb"].".<BR>Respuesta Servicio al Cliente: <BR>".html_entity_decode($res)."<BR>Estamos para servirte,.<BR><BR><STRONG>Servicio al Cliente.</STRONG></FONT></P>";
									$body .= "<P><STRONG><FONT color=#808080></FONT></STRONG>&nbsp;</P>";
									$body.="</TD></TR></TBODY></TABLE></P>";
									$body .= "<P align=left ><A href=\"http://".$filaema["url"]."\"><FONT size=2>Visita ".$filaema["url"]."</FONT></A></P>";
									$body .= "</TD></TR>";
									$body .= "</TBODY></TABLE></P>";
								break;
									
								case 2://ingles
									$asunto= "Web contact reply:";	
									$mail -> Subject = $asunto;
									$body = "<P><TABLE style=\"WIDTH: 529px; HEIGHT: 175px\" cellSpacing=1 cellPadding=0 width=529 border=0 class=textonegro>";
									$body .= "<TBODY>";
									$body .= "<TR>";
									$body .= "<TD>";
									$body .= "<p><FONT color=gray size=2><IMG style=\"WIDTH: 245px; HEIGHT: 168px\" hspace=0 src=\"http://".$filaema["url"]."/msg/sercli.jpg\" align=left border=0>";
									$body .= "Hi.";
									$body .= "<BR><BR>Thanks for contact ".$filaema["nomemp"]."Our customer service staff has received your request dated on".$filcom["fecconweb"].".<BR>Customer service reply: <BR>".html_entity_decode($res)."<BR>.<BR><BR><STRONG>customer service.</STRONG></FONT></P>";
									$body .= "<P><STRONG><FONT color=#808080></FONT></STRONG>&nbsp;</P>";
									$body .="<BR><IMG style=\"WIDTH: 125px; HEIGHT: 54px\" hspace=0 src=\"http://".$filaema["url"]."/msg/logo.jpg\" align=textTop border=0></P></TD></TR></TBODY></TABLE></P>";
									$body .= "<P align=left ><A href=\"http://".$filaema["url"]."\"><FONT size=2>".$filaema["url"]."</FONT></A></P>";
									$body .= "</TD></TR>";
									$body .= "</TBODY></TABLE></P>";
								break;
							}//fin switch
							$mail -> Body = $body;
							$mail -> IsHTML (true);
							$archivos = '';
							$msg = "Mensaje Enviado";
							if (isset ($_FILES["archivos"])) { # Si es que se subió algún archivo
								$msg .= "<ul>";
								foreach ($_FILES["archivos"]["error"] as $key => $error) { # Iterar sobre la colección de archivos
									if ($error == UPLOAD_ERR_OK) { // Si no hay error
										$tmp_name = $_FILES["archivos"]["tmp_name"][$key];
										$name = $_FILES["archivos"]["name"][$key];
										$msg .= "<li>$name</li>";
										$name = uniqid('bc') . '_' . $name; # Generar un nombre único para el archivo
										$mail -> AddAttachment ($tmp_name, $name); # Añade el archivo adjunto
										/*
										Si se van a guardar los archivos en un directorio, deberían descomentarse
										las siguientes líneas, si se van a guardar los nombres 
										de los archivos en una base de datos, aquí debería realizarse algo...					
										move_uploaded_file($tmp_name, "ruta/directorio/$name"); # Guardar el archivo en una ubicación, debe tener los permisos necesarios
										*/
									} #if
								} # foreach
								$msg .= '</ul>';
							} # if
							echo '<script language = JavaScript>
							location = "comlis.php";
							</script>';
							if (!$mail -> Send ()){//if4
								$msg = "No se pudo enviar el email";
							}//fin 4
						}else{
							echo '<script language = JavaScript>
							location = "comlis.php";
							</script>';
						}//fin si envia correo
					}
				}
				//boton cancelar cambios
				if (isset($_POST['cancelarno'])){
					echo '<script language = JavaScript>
					location = "comlis.php";
					</script>';
				}
				//boton redireccionar area
				if (isset($_POST['redirecciona'])){
					$area = $_POST["selarea"];
					if($area == 0){
						echo "Seleccione el area responsable";
					}else{
						$qryact = "UPDATE conweb SET codarea = '$area' WHERE codconweb = '$cod'";
						$resact = mysql_query($qryact, $enlace);
						
						////ENVIO DE CORREO
						include_once('class.phpmailer.php');
												
						// Indica si los datos provienen del formulario
						$asunto= "Contacto Web";	
						$consultaema = "SELECT nomemp, emaemp, url FROM licusu ";
						$resultadoema = mysql_query($consultaema, $enlace);
						$filaema= mysql_fetch_array($resultadoema);
							
						//direccion de usuario responsable del area	
						$qyremaarea = "SELECT u.emausu FROM areacon ac, usuadm u WHERE ac.codarea = $area AND ac.codusuadm = u.codusuadm ";	
						$resemaarea = mysql_query($qyremaarea, $enlace);
						$filemaarea = mysql_fetch_assoc($resemaarea);
							
						//consulto nombre de areea contactada
						$qrynomarea = "SELECT acd.nomarea FROM areacondet acd WHERE acd.codarea = $area AND acd.codidi = 1";
						$resnomarea = mysql_query($qrynomarea, $enlace);
						$filnomarea = mysql_fetch_assoc($resnomarea);
						
						//dirección del remitente 
						$envia=$filaema["emaemp"];	
										
						//direccion destino		
						$destinatario = $filcom["emaconweb"];
							
						$mail = new phpmailer (); # Crea una instancia
						$mail -> From = $envia;
						$mail -> FromName = $filaema["nomemp"]; # Puede obtenerse del formulario, por facilidad se hace de esta manera
						$mail -> AddAddress ($destinatario);
						$mail -> AddBCC($envia);
						$mail -> Subject = $asunto;
							
						$body = "<P><TABLE border=0>";
						$body .= "<TBODY>";
						$body .= "<TR>";
						$body .= "<TD>";
						$body .= "Hola. Se ha enviado un contacto Web, para ampliar la información ingrese al administractor del sitio Web";
						$body .= "<BR><BR>Contacto";
						$body .= "Remitente:".$filcom["nomconweb"]."<BR>";
						$body .= "Area Contactada:".$filcom["nomarea"]."<BR>";
						$body .= "<A href=\"http://".$filaema["url"]."/administractor/fyles\"><FONT size=2>Ir al administractor</FONT></A><BR>";
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
						location="comlis.php";
						</script>
						<?php
					}
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
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/contacto.png" width="48" height="48" align="absmiddle" />Contacto Web [Responde] -   <strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
                Tiempo transcurrido</span> <span class="textoerror"><?php echo $filcom["tietra"];?> Horas </span></strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="341">&nbsp;</td>
          <td valign="top"><table width="58%" height="398" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="14" height="13"></td>
                <td width="217"></td>
                <td width="238"></td>
                <td width="7"></td>
                <td width="157"></td>
                <td width="437"></td>
                <td width="31"></td>
            </tr>
            <tr>
              <td height="18"></td>
              <td rowspan="2" valign="top" >Fecha de Contacto </td>
                <td rowspan="2" valign="top" class="textonegro"><?php echo $filcom["fecconweb"];?>&nbsp;</td>
                <td></td>
                <td valign="top"><span <?php if($filpar["ced"] == 1){?> style=" visibility:hidden" <?php }?>>Nit/C&eacute;dula</span></td>
                <td valign="top" <?php if($filpar["ced"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["nitconweb"];?></td>
                <td></td>
            </tr>
            <tr>
              <td height="2"></td>
              <td></td>
              <td rowspan="2" valign="top" <?php if($filpar["percon"] == 1){?> style=" visibility:hidden" <?php }?>>Persona de Contacto </td>
                <td rowspan="2" valign="top" class="textonegro"><?php echo $filcom["conweb"];?></td>
                <td></td>
            </tr>
            <tr>
              <td height="3"></td>
              <td rowspan="2" valign="top" ><p>Remitente</p></td>
                  <td rowspan="2" valign="top" class="textonegro"><?php echo $filcom["nomconweb"];?></td>
                  <td></td>
                  <td></td>
            </tr>
            <tr>
              <td height="3"></td>
              <td></td>
              <td rowspan="2" valign="top" <?php if($filpar["tipcli"] == 1){?> style=" visibility:hidden" <?php }?>>Tipo de Cliente </td>
                  <td rowspan="2" valign="top" class="textonegro"<?php if($filpar["tipcli"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["nomtipter"];?></td>
                  <td></td>
            </tr>
            <tr>
              <td height="18"></td>
              <td valign="top"<?php if($filpar["emp"] == 1){?> style=" visibility:hidden" <?php }?>>Empresa</td>
              <td valign="top"<?php if($filpar["emp"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["empconweb"];?></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="18"></td>
              <td valign="top"<?php if($filpar["car"] == 1){?> style=" visibility:hidden" <?php }?>>Cargo</td>
              <td valign="top"<?php if($filpar["car"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["carconweb"];?></td>
              <td></td>
              <td valign="top"  <?php if($filpar["are"] == 1){?> style=" visibility:hidden"<?php }?>>Area</td>
                <td valign="top" <?php if($filpar["are"] == 1){?> style=" visibility:hidden"<?php }?>><?php echo $filcom["nomarea"];?></td>
              <td></td>
            </tr>
            <tr>
              <td height="18"></td>
              <td rowspan="2" valign="top"  <?php if($filpar["dir"] == 1){?>style=" visibility:hidden" <?php }?>>Direcci&oacute;n</td>
              <td rowspan="2" valign="top" <?php if($filpar["dir"] == 1){?> style=" visibility:hidden" <?php }?>class="textonegro"><?php echo $filcom["dirconweb"];?></td>
              <td></td>
              <td valign="top"  <?php if($filpar["are"] == 1){?> style=" visibility:hidden" <?php }?>>Responsable</td>
                  <td valign="top" <?php if($filpar["are"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["nomusu"];?></td>
                  <td></td>
            </tr>
            <tr>
              <td height="2"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            
            
            <tr>
              <td height="3"></td>
              <td rowspan="2" valign="top"  <?php if($filpar["tel"] == 1){?> style=" visibility:hidden" <?php }?>>Tel&eacute;fono</td>
              <td rowspan="2" valign="top" <?php if($filpar["tel"] == 1){?> style=" visibility:hidden"<?php }?>class="textonegro"><?php echo $filcom["telconweb"];?></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="15"></td>
              <td></td>
              <td colspan="2" valign="top" >Comentarios</td>
                <td></td>
            </tr>
            
            
            <tr>
              <td height="18"></td>
              <td valign="top"  <?php if($filpar["mov"] == 1){?> style=" visibility:hidden" <?php }?>>Movil</td>
              <td valign="top" class="textonegro" <?php if($filpar["mov"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["movconweb"];?></td>
              <td></td>
              <td colspan="2" rowspan="5" valign="top"><?php echo html_entity_decode( $filcom["desconweb"] );?></td>
                  <td></td>
            </tr>
            <tr>
              <td height="18"></td>
              <td valign="top" >e-mail</td>
              <td valign="top" class="textonegro"><?php echo $filcom["emaconweb"];?></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="18"></td>
              <td valign="top"  <?php if($filpar["pai"] == 1){?> style=" visibility:hidden"<?php }?>>Pa&iacute;s</td>
              <td valign="top"  <?php if($filpar["pai"] == 1){?> style=" visibility:hidden" <?php }?>class="textonegro"><?php echo $filcom["cn"];?></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="18"></td>
              <td valign="top"  <?php if($filpar["estpro"] == 1){?> style=" visibility:hidden" <?php }?>>Estado/Provincia</td>
              <td valign="top"  <?php if($filpar["estpro"] == 1){?> style=" visibility:hidden" <?php }?>class="textonegro"><?php echo $filcom["nomdep"];?></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="8"></td>
              <td rowspan="3" valign="top"  <?php if($filpar["ciu"] == 1){?> style=" visibility:hidden" <?php }?>>Ciudad</td>
              <td rowspan="2" valign="top" class="textonegro" <?php if($filpar["ciu"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["nomciu"];?></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="10"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="1"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="8"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="23"></td>
              <td colspan="2" valign="top" >
                <label></label><label></label>
               Enviar respuesta en formato 
                <select name="selidi" id="selidi">
                  <?
					if (isset($_POST['selidi'])){
						$idi=$_POST['selidi'];
						$qryidi = "SELECT * FROM idipub WHERE codidi <> '$idi' ORDER BY nomidi ";
						$qryidi1 = "SELECT * FROM idipub WHERE codidi = '$idi' ";
						$residi1 = mysql_query($qryidi1,$enlace);
						$filidi1 = mysql_fetch_array($residi1);
						echo "<option selected value=\"".$filidi1['codidi']."\">".$filidi1['nomidi']."</option>\n";
						mysql_free_result($residi1);
					}
					else
					{
						$qryidi = "SELECT * FROM idipub ORDER BY nomidi ";
					}
					$residi = mysql_query($qryidi, $enlace);
					while ($filidi = mysql_fetch_array($residi))
					echo "<option value=\"".$filidi["codidi"]."\">".$filidi["nomidi"]."</option>\n";
					mysql_free_result($residi);
				?>
                </select></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="21"></td>
              <td>&nbsp;</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="43"></td>
              <td valign="top" class="titmenu"><strong>Respuesta</strong></td>
              <td valign="top">
                <label>Enviar email de respuesta?
                <input type="radio" name="envcor" value="2">
Si</label>
                <label>
                <input name="envcor" type="radio" value="1" checked>
                </label>
              No</td>
              <td colspan="3" align="right" valign="top"  <?php if($filpar["are"] == 1){?> style=" visibility:hidden" <?php }?>><span class="textoerror">Nota:</span> Si este cont&aacute;cto no es para su &aacute;rea puede redireccionarlo al &aacute;rea correspondiente.<br>
                Redireccionar contacto a &aacute;rea
                <select name="selarea" id="selarea">
                    <option value="0">Elige</option>
                    <?
						$qryarea = "SELECT ac.codarea, acd.nomarea  FROM areacon ac, areacondet acd  WHERE ac.estarea = 2 AND ac.codarea = acd.codarea AND acd.codidi = 1 ";
						$resarea = mysql_query($qryarea, $enlace);
						while ($filarea = mysql_fetch_array($resarea))
						echo "<option value=\"".$filarea["codarea"]."\">".$filarea["nomarea"]."</option>\n";
						mysql_free_result($resarea);
					?>
                  </select>
                <span class="textonegro">
                <input name="redirecciona" type="submit" class="botonverde" id="redirecciona" value="Enviar"  />
                </span></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="49"></td>
              <td colspan="5" valign="top"><?php
				// Automatically calculates the editor base path based on the _samples directory.
				// This is usefull only for these samples. A real application should use something like this:
				// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
				
				$oFCKeditor = new FCKeditor('txtres') ;
				$oFCKeditor->BasePath = '../fyles/fckeditor/';
				
				if (isset($_POST['txtres'])){
					$oFCKeditor->Value = $_POST['txtres'] ;
				}
				else
				{
					$oFCKeditor->Value = "" ;
				}
				$oFCKeditor->Create() ;
				?></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="22"></td>
              <td colspan="5" valign="top"><label>Archivos Adjuntos:</label>
&nbsp;&nbsp;&nbsp;<a href="#" class="titmenu" accesskey="5" onClick="addField()">A&ntilde;adir Archivo</a><br /><div id="files"></div></td>
              <td></td>
            </tr>
            <tr>
              <td height="12"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
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