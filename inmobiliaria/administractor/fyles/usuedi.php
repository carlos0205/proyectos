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
$prog = 'usuedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//capturo accion a realizar 1=editar 0=actualizar
$acc = $_GET["acc"];
//capturo codigo de usuario
$cod = $_GET["cod"];

if ($acc == 0)
{

$hab = $_GET["hab"];

$qryusu = "UPDATE usuadm SET estusu = '$hab' WHERE codusuadm = '$cod'";
$resusu = mysql_query($qryusu, $enlace);

echo '<script language = JavaScript>
	location = "usu.php";
	</script>';
	
}else{

$qryreg = "SELECT u.*, g.nomgru FROM usuadm u, gruusu g WHERE u.codusuadm = '$cod' AND u.codgru = g.codgru";
$resreg = mysql_query($qryreg, $enlace);
$filreg = mysql_fetch_assoc($resreg);

}


//defino funciones
function validaemail($ema){
	global $enlace;
	global $cod;
	$respuesta = new xajaxResponse();
	
	$qryema = "SELECT emausu FROM usuadm WHERE emausu = '$ema' AND codusuadm <> $cod ";
	$resema = mysql_query($qryema);
	
	if (mysql_num_rows($resema)> 0){
		$respuesta->alert("La cuenta de correo ya existe el la base de datos");
		$respuesta->assign("txt2emaususi","value","");
		return $respuesta;
	}
	
}


function validaloguin($loguin){
      global $enlace;
	  global $cod;
	  $respuesta=new xajaxResponse ();
	  
	  $qryloguin= "SELECT logusu FROM usuadm WHERE logusu='$loguin' AND codusu <> $cod ";
	  $resloguin=mysql_query($qryloguin, $enlace);
	  
	  if (mysql_num_rows($resloguin)> 0){
		$respuesta->alert("El loguin de usuario ya existe el la base de datos");
		$respuesta->assign("txt2logususi","value",$filreg["logusu"]);
		//$respuesta->assign("txt2logususi","style.backgroundColor","red");
		return $respuesta;
	}

} 


$xajax->registerFunction("validaemail");
$xajax->registerFunction("validaloguin");

//El objeto xajax tiene que procesar cualquier petición 
$xajax->processRequest();

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
	
	var con = document.form1.txt1conno.value;
	var con1 = document.form1.txt1con1no.value;
	var usu = document.form1.txtusu.value;
	var mm = con.length;
	var usu1 =usu.length;
		
		
	if(mm < 8) {
		alert("La contraseña debe ser mínimo de 8 caracteres");
		document.form1.txt1conno.value = "";
		document.form1.txt1conno.focus();
		return false;
		exit();
		}
	
	if (document.form1.txt1conno.value.indexOf(invalid) > -1) {
		alert("No se permiten espacios en la contraseña.");
		document.form1.txt1conno.value="";
		document.form1.txt1conno.focus();
		return false;
		exit();
		}
		
	if (con != con1){
	alert("Las contraseñas no coinciden");
	document.form1.txt1conno.value="";
	document.form1.txt1con1no.value="";
	document.form1.txt1conno.focus();
	return false;
	exit();
	}
	if (con == usu){
	alert("El nombre de Usuario y la Contraseña deben ser diferentes");
	document.form1.txt1conno.value="";
	document.form1.txt1con1no.value="";
	document.form1.txt1conno.focus();
	return false;
	}
		
	else {
	return true;
	}
}
//-->
//  End -->
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
	  <form id="form1" name="form1" method="post" action=""  onSubmit="" enctype="multipart/form-data">

      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="61" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="8" height="20"></td>
                  <td width="278">&nbsp;</td>
                  <td width="807"></td>
                  <td width="32">&nbsp;</td>
                  <td width="76" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
	<td width="63" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
	<td width="76" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="12">&nbsp;</td>
            </tr>
            <tr>
              <td height="15"></td>
                  <td valign="top" >Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
                  <td></td>
                  <td></td>
            </tr>
            <tr>
              <td height="26" colspan="3" valign="top" class="textoerror"><div align="right">
                <?php


//boton guardar cambios
if (isset($_POST['guardarno']))
{
	actualizar("usuadm",2,$cod,"codusuadm","usu.php");
}

//boton aplicar cambios
if (isset($_POST['aplicarno']))
{
 	actualizar("usuadm",2,$cod,"codusuadm","usuedi.php?cod=$cod&acc=1");
} 
 
//boton cambiar contraseña
if (isset($_POST['cambiarpw']))
{
$con = md5($_POST["txt1conno"]);
//actualizo contraseña de usuario	
$qryusuact = "UPDATE usuadm SET pasusu = '$con' WHERE codusuadm = '$cod' ";
$resusuact = mysql_query($qryusuact, $enlace);

echo"Contraseña actualizada";
}
 
//boton cancelar cambios
if (isset($_POST['cancelarno']))
{
echo '<script language = JavaScript>
location = "usu.php";
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
          <td width="1039">&nbsp;</td>
          <td width="9">&nbsp;</td>
        </tr>
        
        <tr>
          <td height="52">&nbsp;</td>
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/usuarios.png" width="48" height="48" align="absmiddle" /> Usuarios del sistema [ Edita ]  <strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
                </strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="316">&nbsp;</td>
          <td valign="top"><table width="58%" height="350" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="7" height="13"></td>
                  <td width="140"></td>
                  <td width="704"></td>
                  <td width="247"></td>
                  <td width="12"></td>
            </tr>
            <tr>
              <td height="22"></td>
                <td valign="top" ><p>Nombre de Usuario  
                  
                  </p></td>
                  <td valign="top"><input name="txt2nomususi" type="text" id="txt2nomususi"  title="Nombre" value="<?php echo $filreg["nomusu"]?>" size="40"maxlength="100"/></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="12"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            
            <tr>
              <td height="22"></td>
                <td valign="top" >e-mail                </td>
                <td valign="top">
                  <input name="txt2emaususi" type="text" id="txt2emaususi" title="e-mail"  onBlur="xajax_validaemail(this.value)" value="<?php echo $filreg["emausu"]?>" size="40"maxlength="100" />
                  </span></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="13"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="22"></td>
                <td valign="top" >Grupo de Usuario                </td>
                <td valign="top">
                  <select name="cbo2codgrusi" id="cbo2codgrusi" title="Grupo">
                    <?
	$gru =  $filreg["codgru"];
	$qrygru = "SELECT * FROM gruusu WHERE codgru <> '$gru' AND codgru <> 1 AND codgru <> 3 ORDER BY nomgru ";
	$resgru = mysql_query($qrygru, $enlace);
	echo "<option selected value=\"".$filreg["codgru"]."\">".$filreg["nomgru"]."</option>\n";
	while ($filgru = mysql_fetch_array($resgru))
	echo "<option value=\"".$filgru["codgru"]."\">".$filgru["nomgru"]."</option>\n";
	mysql_free_result($resgru);
?>
                    </select>
                  </span></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="15"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="22"></td>
                <td valign="top" >Estado</td>
                <td valign="top"><select name="cbo2estususi" id="cbo2estususi" title="Estado">
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
                                                </select></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="14"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="22"></td>
                <td valign="top" >Login                </td>
                <td valign="top">
                  <input name="txt2logususi" type="text"   id="txt2logususi" value="<?php echo $filreg["logusu"];?>" size="30"maxlength="10" onBlur="xajax_validaloguin(this.value)"  title="Loguin de usuario"/>
                  </span></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="16"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="31"></td>
              <td colspan="3" valign="middle" bgcolor="#FFFF99" >CAMBIAR CONTRASE&Ntilde;A </td>
                <td>&nbsp;</td>
            </tr>
            
            <tr>
              <td height="19"></td>
                <td colspan="2" valign="top" class="titmenu"><span class="titmenu">La contrase&ntilde;a debe ser m&iacute;nimo 8 caracteres</span></td>
                <td></td>
                <td></td>
            </tr>
            
            <tr>
              <td height="25"></td>
                <td valign="top" >Contrase&ntilde;a </td>
                <td valign="top">
                  <input name="txt1conno" type="password" id="txt1conno" size="30"maxlength="10" />
                  </span></td>
                <td></td>
                <td></td>
            </tr>
            
            <tr>
              <td height="31"></td>
                <td valign="top" >Repetir Contrase&ntilde;a </td>
                <td valign="top">
                  <input name="txt1con1no" type="password" id="txt1con1no" size="30"maxlength="10" />
                  </span></td>
                <td></td>
                <td></td>
            </tr>
            
            
            <tr>
              <td height="28"></td>
                <td colspan="2" valign="top"><span class="textonegro">
                  <input name="cambiarpw" type="submit" id="cambiarpw" value="Cambiar contrase&ntilde;a" onClick=" return valida_datos() "/>
                  </span></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="21"></td>
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