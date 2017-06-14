<?php
session_start();
include("general/conexion.php") ;


//incluímos la clase ajax 
require ('xajax/xajax_core/xajax.inc.php');
//instanciamos el objeto de la clase xajax 
$xajax = new xajax();
$xajax->configure('javascript URI', 'xajax/');


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'usuregcre.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

function validaloguin($loguin){
      global $enlace;
	  $respuesta=new xajaxResponse ();
	  
	  $qryloguin= "SELECT logusu FROM usutercli WHERE logusu='$loguin' ";
	  $resloguin=mysql_query($qryloguin, $enlace);
	  
	  if (mysql_num_rows($resloguin)> 0){
		$respuesta->alert("El loguin de usuario ya existe el la base de datos");
		$respuesta->assign("txt2logususi","value","");
		//$respuesta->assign("txt2logususi","style.backgroundColor","red");
		return $respuesta;
	}

} 

function convertircontrasena($con){
      $respuesta=new xajaxResponse();
	  $respuesta->assign("hid2pasususi","value",md5($con));
	  return $respuesta;


}

$xajax->registerFunction("validaloguin");
$xajax->registerFunction("convertircontrasena");


//El objeto xajax tiene que procesar cualquier petición 
$xajax->processRequest();
$qryncli = "SELECT tc.codter FROM tercli tc WHERE tc.codter NOT IN (SELECT codter FROM usutercli) ORDER BY tc.nomter ";
$resncli = mysql_query($qryncli, $enlace);
$numncli = mysql_num_rows($resncli);
if($numncli < 1){
	?>
<script language = JavaScript>
	alert("No existen Clientes pendientes por usuario");
	location = "usureg.php";
	</script>
<?php
}
	

include("general/sesion.php");
include("general/operaciones.php");
sesion(1);

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

function crearregistro(){

var pasa = validaenvia()

	if(pasa == false ){
		return false;
	}else{	

	var invalid = " "; // Invalid character is a space
	
	var con = document.form1.txt2conno.value;
	var con1 = document.form1.txt2con1no.value;
	var usu = document.form1.txt2logususi.value;
	var mm = con.length;
	var usu1 =usu.length;
	

	if(usu1 < 5) {
			alert("El nombre de usuario debe contener entre 5 y 10 caracteres");
			document.form1.txt2logususi.focus();
			return false;
			exit();
		}
		
	if (document.form1.txt2logususi.value.indexOf(invalid) > -1) {
			alert("No se permiten espacios en el usuario.");
			document.form1.txt2logususi.value="";
			document.form1.txt2logususi.focus();
			return false;
			exit();
		}	
		
	if(mm < 8) {
			alert("La contraseña debe ser mínimo de 8 caracteres");
			document.form1.txt2conno.value = "";
			document.form1.txt2conno.focus();
			return false;
			exit();
		}
	
	if (document.form1.txt2conno.value.indexOf(invalid) > -1) {
			alert("No se permiten espacios en la contraseña.");
			document.form1.txt2conno.value="";
			document.form1.txt2conno.focus();
			return false;
			exit();
		}
		
	
		if (con != con1){
			alert("Las contraseñas no coinciden");
			document.form1.txt2conno.value="";
			document.form1.txt2con1no.value="";
			document.form1.txt2conno.focus();
			return false;
			exit();
		}
		if (con == usu){
			alert("El nombre de Usuario y la Contraseña deben ser diferentes");
			document.form1.txt2conno.value="";
			document.form1.txt2con1no.value="";
			document.form1.txt2conno.focus();
			return false;
			exit();
		}
	
			
		var entrar = confirm("¿Desea crear el registro?")
		if ( entrar ) 
		{
			return true;	
		}else{
			return false;
		}
	
	}
	
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
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <form id="form1" name="form1" method="post" action=""  onSubmit="" enctype="multipart/form-data">
        <!--DWLayoutTable-->
        <tr>
          <td height="61" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="5" height="20">&nbsp;</td>
                  <td width="808">&nbsp;</td>
                  <td width="16">&nbsp;</td>
                  <td width="69" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
                  <td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="13">&nbsp;</td>
            </tr>
            <tr>
              <td height="19">&nbsp;</td>
              <td valign="top" >Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            
            <tr>
              <td height="26" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php
				//boton guardar cambios
				if (isset($_POST['guardarno'])){
					
					$siguiente = guardar("usutercli",1,"codusucli",2);
					
					auditoria($_SESSION["enlineaadm"],'Usuario Cliente',$siguiente,'3');
					
							if($_POST["cbo2envcorno"]=="Si"){
								//selecciono e-mail de cliente
								$qrycli = "select emater, codidi, nomter  from tercli WHERE codter = '".$_POST["cbo2codtersi"]."' ";
								$rescli = mysql_query($qrycli, $enlace);
								$filcli= mysql_fetch_assoc($rescli);
								$idi = $filcli["codidi"];
						
								//ENVIO DE CORREO A CLIENTE
								include_once('class.phpmailer.php');
										
								// Indica si los datos provienen del formulario
								$qryema = "SELECT nomemp, emaemp, telemp, url FROM licusu ";
								$resema = mysql_query($qryema, $enlace);
								$filema= mysql_fetch_array($resema);
									
								//dirección del remitente 
								$envia=$filema["emaemp"];	
													
								//direccion destino		
								$destinatario =  $filcli["emater"];
								
								$mail = new phpmailer (); # Crea una instancia
								$mail -> From = $envia;
								$mail -> FromName = $filema["nomemp"]; # Puede obtenerse del formulario, por facilidad se hace de esta manera
								$mail -> AddAddress ($destinatario);
										
								switch ($idi){
									case 1:
										$asunto= "Usuario de Acceso ".$filema["nomemp"]."";	
										$mail -> Subject = $asunto;
													
										$body = "<P><TABLE style=\"WIDTH: 529px; HEIGHT: 175px\" cellSpacing=1 cellPadding=0 width=529 border=0>";
										$body .= "<TBODY>";
										$body .= "<TR>";
										$body .= "<TD>";
										$body .= "<p><FONT color=gray size=2>";
										$body .= "Bienvenido : ".$filcli["nomter"];
												
										$body .= "<BR><BR>Este es su nombre de usuario y contraseña para acceder a nuestro sitio web ".$filema["url"].", donde encontrará toda la información de productos que ".$filema["nomemp"]." tiene para usted.<BR>Usuario: ".$_POST["txt1logusuno"]."<BR>Contraseña: ".$_POST["txt2conno"]."<BR>Esto es lo único que necesita para acceder a ".$filema["nomemp"].". <BR>Estamos para servirle.<BR><BR>Servicio al Cliente ".$filema["nomemp"].".</FONT></P>";
										$body .= "<P><FONT color=#808080></FONT>&nbsp;</P>";
										$body .= "<P><FONT color=gray size=2>Si deseas contactarnos puede llamar a nuestra/s línea/s de Servicio al<BR>Cliente: ".$filema["telemp"]." </FONT>"; 
										$body .= "<BR><IMG style=\"WIDTH: 125px; HEIGHT: 54px\" hspace=0 src=\"http://".$filema["url"]."/msg/logo.jpg\" align=textTop border=0></P></TD></TR></TBODY></TABLE></P>";
										$body .= "<P align=left ><A href=\"http://".$filema["url"]."\"><FONT size=2>".$filema["url"]."</FONT></A></P>";
										$body .= "</TD></TR>";
										$body .= "</TBODY></TABLE></P>";
									break;	
									case 2:
										$asunto= "User access. ".$filema["nomemp"]."";	
												
										$mail -> Subject = $asunto;
										$body = "<P><TABLE style=\"WIDTH: 529px; HEIGHT: 175px\" cellSpacing=1 cellPadding=0 width=529 border=0>";
										$body .= "<TBODY>";
										$body .= "<TR>";
										$body .= "<TD>";
										$body .= "<p><FONT color=gray size=2>";
										$body .= "Welcome : ".$nom;
													
										$body .= "<BR><BR>These are your login and password to log into our web site where you will find all product information that ".$filema["nomemp"]." has for you. This is the only you need to access to all that ".$filema["nomemp"]." has prepared for you. <strong>User:</strong> ".$usu." -- <strong>Password: </strong>".$nuevacontrasena."<BR>.<BR><BR><STRONG>Customer Service ".$filema["nomemp"].".</STRONG></FONT></P>";
										$body .= "<P><STRONG><FONT color=#808080></FONT></STRONG>&nbsp;</P>";
										$body .= "<P><FONT color=gray size=2>If you wish to contact us please call to: ".$filema["telemp"]." or send us an email to : </FONT>"; 
										$body .= "<A href=\"\"><FONT color=#999999 size=2></FONT></A><BR></P><BR></P></TD></TR></TBODY></TABLE></P>";
										$body .= "<P align=left ><A href=\"http://".$filema["url"]."\"><FONT size=2>".$filema["url"]."</FONT></A></P>";
										$body .= "</TD></TR>";
										$body .= "</TBODY></TABLE></P>";
									break;	
								}//fin switch
								$mail -> Body = $body;
								$mail -> IsHTML (true);
								$archivos = '';
								$msg = "Mensaje Enviado";
								if (!$mail -> Send ()){//if4
									$msg = "No se pudo enviar el email";
								}//fin 4
							}// fin sicrea usuario	
							
							echo '<script language = JavaScript>
						location = "usureg.php";
						</script>';
						
					}
					//boton cancelar cambios
					if (isset($_POST['cancelarno'])){
						echo '<script language = JavaScript>
						location = "usureg.php";
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
          <td width="1099">&nbsp;</td>
          <td width="10">&nbsp;</td>
        </tr>
        
        <tr>
          <td height="52">&nbsp;</td>
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/usuarioregistrado.png" width="48" height="48" align="absmiddle" /> Usuarios Registrados [ Crea ]  <strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
                </strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="210">&nbsp;</td>
          <td valign="top"><table width="58%" height="207" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="17" height="13"></td>
                  <td width="137"></td>
                  <td width="794"></td>
                  <td width="22"></td>
            </tr>
            <tr>
              <td height="22"></td>
                <td valign="top" ><p>Seleccione el Cliente </p></td>
                  <td valign="top">
                    <select name="cbo2codtersi" id="cbo2codtersi" title="Cliente">
                      <option value="0">Elige</option>
      <?
	
	$qrycli = "SELECT tc.codter, tc.nomter FROM tercli tc WHERE tc.codter NOT IN (SELECT codter FROM usutercli) ORDER BY tc.nomter ";
	$rescli = mysql_query($qrycli, $enlace);
	while ($filcli = mysql_fetch_array($rescli))
	echo "<option value=\"".$filcli["codter"]."\">".$filcli["nomter"]."</option>\n";
	mysql_free_result($rescli);
	
	?>
                    </select>
                  </span></td>
                  <td></td>
            </tr>
            <tr>
              <td height="24"></td>
                <td colspan="2" valign="top">Envia Correo de Cuenta a Usuario? 
                  <label>
                  <select name="cbo2envcorno" id="cbo2envcorno" title="Envia Correo">
                    <option value="0">Elige</option>
                    <option value="Si">Si</option>
                    <option value="No">No</option>
                  </select>
                  </label></td>
                <td></td>
            </tr>
            <tr>
              <td height="22"></td>
                <td valign="top" >Login                </td>
                <td valign="top"><input name="txt2logususi" type="text" id="txt2logususi" size="30"maxlength="100" onBlur="xajax_validaloguin(this.value)" title="Loguin" />
                  </span></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="14"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td colspan="2" valign="top" class="titmenu"><span class="titmenu">La contrase&ntilde;a debe ser m&iacute;nimo 8 caracteres</span></td>
                <td></td>
            </tr>
            <tr>
              <td height="16"></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="22"></td>
                <td valign="top" >Contrase&ntilde;a </td>
                <td valign="top"><input name="txt2conno" type="password" id="txt2conno" size="30"maxlength="10" onBlur="xajax_convertircontrasena(this.value)" title="Contrase&ntilde;a"/>
                  </span>
                  <input name="hid2pasususi" type="hidden" id="hid2pasususi" title="Contrase&ntilde;a">
                  <input name="hid1estususi" type="hidden" id="hid1estususi" title="Contrase&ntilde;a" value="Activo">
                  <input name="hid1feccresi" type="hidden" id="hid1feccresi" title="Contrase&ntilde;a" value="<?php echo date("Y-m-j H:i:s") ; ?>"></td>
                <td></td>
            </tr>
            <tr>
              <td height="22"></td>
                <td valign="top" >Repetir Contrase&ntilde;a </td>
                <td valign="top"><input name="txt2con1no" type="password" id="txt2con1no" size="30"maxlength="10"  title="Contrase&ntilde;a1"/>
                  </span></td>
                <td></td>
            </tr>
            <tr>
              <td height="34"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            
                    </table></td>
          <td>&nbsp;</td>
        </tr>
		</form>
      </table>
    <!-- InstanceEndEditable --></td>
  </tr>
  
  <tr>
    <td height="32" colspan="2" valign="top"><div align="center" class="textonegro"><img src="../images/guacamayo.png" width="40" height="81" align="middle">  <strong>ADMIN-WEB</strong> , </div></td>
  </tr>
</table>
</form>
</body>
<!-- InstanceEnd --></html>