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
$prog = 'cliedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();


function validaloguin($loguin){
      global $enlace;
	  global $cod;
	  global $filreg;
	  $respuesta=new xajaxResponse ();
	  
	  $qryloguin= "SELECT logusu FROM empleados WHERE logusu='$loguin' AND codusu <> $cod ";
	  $resloguin=mysql_query($qryloguin, $enlace);
	  
	  if (mysql_num_rows($resloguin)> 0){
		$respuesta->alert("El loguin de usuario ya existe el la base de datos");
		$respuesta->assign("txt2logususi","value",$filreg["logusu"]);
		//$respuesta->assign("txt2logususi","style.backgroundColor","red");
		return $respuesta;
	}

} 


$xajax->registerFunction("validaloguin");

//El objeto xajax tiene que procesar cualquier petición 
$xajax->processRequest();

//capturo accion a realizar 1=editar 0=actualizar
$acc = $_GET["acc"];

//capturo código de cliente
$cod  = $_GET["cod"];

if ($acc == 0){
	$hab = $_GET["hab"];
	
	$qryusu = "UPDATE usutercli SET estusu = '$hab' WHERE codusucli = '$cod'";
	$resusu = mysql_query($qryusu, $enlace);

	if($hab=="Activo"){
		
		//enviamos el mensaje solo si la habilidad =2
		
		//usuario
		$queryusu= "SELECT  t.emater, t.nomter, t.codidi FROM usutercli u, tercli t WHERE u.codter=t.codter AND codusucli = '$cod'";
		$resusu = mysql_query($queryusu, $enlace);
		$filusu= mysql_fetch_array($resusu);
		
		
		//ENVIO CORREO DE NOTIFICACION A CLIENTE
		include_once('class.phpmailer.php');
								
		$queryema = "SELECT nomemp FROM licusu ";
		$resema = mysql_query($queryema, $enlace);
		$filema= mysql_fetch_array($resema);
			
		//dirección del remitente 
		$envia=$filusu["emater"];	
						
		//direccion destino		
		$destinatario = $envia;
				
		$mail = new phpmailer (); # Crea una instancia
		$mail -> From = $envia;
		$mail -> FromName = $filema["nomemp"]; # Puede obtenerse del formulario, por facilidad se hace de esta manera
		$mail -> AddAddress ($destinatario);
		
		switch ($filusu["codidi"]){
			case 1:
				$asunto= "USUARIO HABILITADO";	
				$mail -> Subject = $asunto;
						
				$body = "<P><TABLE style=\"WIDTH: 529px; HEIGHT: 175px\" cellSpacing=1 cellPadding=0 width=529 border=0 class= textonegro>";
				$body .= "<TBODY>";
				$body .= "<TR>";
				$body .= "<TD>";
				$body .= "<p><FONT color=gray size=2>";
				$body .= "Hola ".$filusu["nomter"].",";
				$body .= "<BR><BR>Su cuenta de".$filema["nomemp"]." ha sido habilitada. Si ha olvidado su contraseña, puede recoldarla dando clic sobre el enlace recordar clave ubicado en la parte inferior del formulario de login <BR><BR><STRONG>Servicio al Cliente.</STRONG></FONT></P>";
				$body .= "</P></TD></TR></TBODY></TABLE></P>";
			break;
					
			case 2:
				$asunto= "ENABLED USER";	
				$mail -> Subject = $asunto;
							
				$body = "<P><TABLE style=\"WIDTH: 529px; HEIGHT: 175px\" cellSpacing=1 cellPadding=0 width=529 border=0 class= textonegro>";
				$body .= "<TBODY>";
				$body .= "<TR>";
				$body .= "<TD>";
				$body .= "<p><FONT color=gray size=2>";
				$body .= "Hello ".$filusu["nomter"];
				$body .= "<BR><BR>Your Account".$filema["nomemp"]." has been enabled. If you forgot your password, you can recoldarla by clicking on the link to recall key at the bottom of the login form<BR><BR><STRONG>Customer Service.</STRONG></FONT></P>";
				$body .= "</P></TD></TR></TBODY></TABLE></P>";
			break;
		}//fin switch
		$mail -> Body = $body;
		$mail -> IsHTML (true);
		$archivos = '';
		$msg = "Mensaje Enviado";
		
		if (!$mail -> Send ()){//if4
			$msg = "No se pudo enviar el email";
		}//fin 4
		
		echo '<script language = JavaScript>
		location = "usureg.php";
		</script>';
	
	}else{
		echo '<script language = JavaScript>
		location = "usureg.php";
		</script>';
	}	
}else{
	$codter  = $_GET["cod"];
	//capturo tipo deusuario si es dehoja de vida o cliente
	$tipo = $_GET["tipo"];
	
	if($tipo == 1){
	$qryreg = "SELECT tc.nitter AS cedula, tc.nomter, tu.nomtipusuter, utc.codusucli, utc.feccre, utc.estusu, utc.logusu, utc.ultvis FROM tercli tc, tipusuter tu, usutercli utc WHERE tc.codtipusuter = tu.codtipusuter AND  tc.codter = utc.codter AND tc.codter = '$codter' ORDER BY tc.nomter";
	}else{
	$qryreg = "SELECT tc.cedter AS cedula, tc.nomter, tu.nomtipusuter, utc.codusucli, utc.feccre, utc.estusu, utc.logusu, utc.ultvis FROM terclihojvda tc, tipusuter tu, usutercli utc WHERE tc.codtipusuter = tu.codtipusuter AND  tc.codhojvda = utc.codter AND tc.codhojvda = '$codter' ORDER BY tc.nomter";
	}
	$resreg = mysql_query($qryreg, $enlace);
	$filreg = mysql_fetch_assoc($resreg);
	
	
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
<script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
<SCRIPT type="text/javascript">


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
<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

<!-- Begin
<!--
function valida_datos(){
	var invalid = " "; // Invalid character is a space
	
	var con = document.form1.txtcon.value;
	var con1 = document.form1.txtcon1.value;
	var usu = document.form1.txtusu.value;
	var mm = con.length;
	var usu1 =usu.length;
		
		
	if(mm < 8) {
		alert("La contraseña debe ser mínimo de 8 caracteres");
		document.form1.txtcon.value = "";
		document.form1.txtcon.focus();
		return false;
		exit();
		}
	
	if (document.form1.txtcon.value.indexOf(invalid) > -1) {
		alert("No se permiten espacios en la contraseña.");
		document.form1.txtcon.value="";
		document.form1.txtcon.focus();
		return false;
		exit();
		}
		
	if (con != con1){
	alert("Las contraseñas no coinciden");
	document.form1.txtcon.value="";
	document.form1.txtcon1.value="";
	document.form1.txtcon.focus();
	return false;
	exit();
	}
	if (con == usu){
	alert("El nombre de Usuario y la Contraseña deben ser diferentes");
	document.form1.txtcon.value="";
	document.form1.txtcon1.value="";
	document.form1.txtcon.focus();
	return false;
	}
		
	else {
	return true;
	}
}
//-->
//  End -->
</script>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
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
              <td width="4" height="20">&nbsp;</td>
                  <td width="741">&nbsp;</td>
                  <td width="19">&nbsp;</td>
                  <td width="72" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
	<td width="60" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
	<td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="15">&nbsp;</td>
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
if (isset($_POST['guardarno']))
{
auditoria($_SESSION["enlineaadm"],'Usuario Cliente',$cod,'4');
actualizar("usutercli",2,$cod,"codter","usureg.php");
}


//boton aplicar cambios
if (isset($_POST['aplicarno']))
{
auditoria($_SESSION["enlineaadm"],'Usuario Cliente',$cod,'4');
actualizar("usutercli",2,$cod,"codter","usuregedi.php?cod=$cod&acc=1&tipo=$tipo");

 } 
 
//boton cambiar contraseña
if (isset($_POST['cambiarpw']))
{
$con = md5($_POST["txt1conno"]);
//actualizo contraseña de usuario	
$qryusuact = "UPDATE usutercli SET pasusu = '$con' WHERE codusucli = '$cod' ";
$resusuact = mysql_query($qryusuact, $enlace);

echo"Contraseña actualizada";

auditoria($_SESSION["enlineaadm"],'Usuario Cliente',$cod,'4');
}
 
//boton cancelar cambios
if (isset($_POST['cancelarno']))
{
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
          <td width="1379">&nbsp;</td>
          <td width="11">&nbsp;</td>
        </tr>
        
        <tr>
          <td height="52">&nbsp;</td>
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/usuarioregistrado.png" width="48" height="48" align="absmiddle" /> Usuario Registrado [ Edita ]   <strong>
              
                </strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="344">&nbsp;</td>
          <td valign="top"><table width="58%" height="383" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="12" height="13"></td>
                  <td width="102"></td>
                  <td width="49"></td>
                  <td width="159"></td>
                  <td width="114"></td>
                  <td width="537"></td>
                  <td width="166"></td>
            </tr>
            <tr>
              <td height="22"></td>
              <td valign="top" >Nit/C&eacute;dula</td>
              <td colspan="2" valign="top" class="titmenu"><p><strong>
                <?php echo $filreg["cedula"]; ?></strong></p></td>
                  <td valign="top" >Nombre Usuario  </td>
                  <td valign="top" class="titmenu"><strong><?php echo $filreg["nomter"]; ?></strong></td>
                  <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="4"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="23"></td>
              <td valign="top" >Tipo de Usuario</td>
              <td colspan="2" valign="top" class="titmenu"><strong><?php echo $filreg["nomtipusuter"]; ?></strong></td>
              <td colspan="2" valign="top" ><label>
              Estado 
              <select name="cbo2estususi" id="cbo2estususi" title="Estado">
                <?php
					  $qrypub="SELECT 'Activo' AS estado
						UNION
						SELECT 'Bloqueado' AS estado";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['estusu']."\">".$filreg['estusu']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["estado"] <> $filreg["estusu"]){
								echo "<option value=\"".$filpub["estado"]."\">".$filpub["estado"]."</option>\n";
							}
						}
					 ?>
              </select>
              </label>
              <label></label></td>
              <td></td>
            </tr>
            <tr>
              <td height="13"></td>
              <td>&nbsp;</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="18"></td>
              <td colspan="5" valign="top" class="textonegro">Creado:<strong><span class="textonegro"><?php echo $filreg["feccre"];?></strong></td>
              <td></td>
            </tr>
            <tr>
              <td height="17"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="18"></td>
              <td colspan="5" valign="top" class="textonegro">Ultima Visita: <strong><span class="textonegro"><?php echo $filreg["ultvis"];?></strong></td>
              <td></td>
            </tr>
            <tr>
              <td height="14"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="31"></td>
              <td colspan="2" valign="top">Login </td>
              <td colspan="3" valign="top"><input name="txt2logususi" type="text"   id="txt2logususi" value="<?php echo $filreg["logusu"];?>" size="30"maxlength="10" title="Loguin de usuario"  /></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="21"></td>
              <td colspan="5" valign="middle" bgcolor="#FFFF99">CAMBIAR CONTRASE&Ntilde;A </td>
              <td></td>
            </tr>
            <tr>
              <td height="1"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="19"></td>
              <td colspan="5" valign="top"><span class="titmenu">La contrase&ntilde;a debe ser m&iacute;nimo 8 caracteres</span></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="22"></td>
              <td colspan="2" valign="top">Contrase&ntilde;a </td>
              <td colspan="3" valign="top"><input name="txt1conno" type="password" id="txt1conno" size="30"maxlength="10" /></td>
              <td></td>
            </tr>
            <tr>
              <td height="22"></td>
              <td colspan="2" valign="top">Repetir Contrase&ntilde;a</td>
              <td colspan="3" valign="top"><input name="txt1con1no" type="password" id="txt1con1no" size="30"maxlength="10" /></td>
              <td></td>
            </tr>
            <tr>
              <td height="3"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="25"></td>
              <td colspan="5" valign="top">
                <input name="cambiarpw" type="submit" id="cambiarpw" value="Cambiar contrase&ntilde;a" onClick=" return valida_datos() "/>
             </td>
              <td></td>
            </tr>
            <tr>
              <td height="95"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
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