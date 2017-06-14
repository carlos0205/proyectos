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
$prog = 'usucre.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//defino funciones
function validaemail($ema){
	global $enlace;
	$respuesta = new xajaxResponse();
	
	$qryema = "SELECT emausu FROM usuadm WHERE emausu = '$ema'";
	$resema = mysql_query($qryema);
	
	if (mysql_num_rows($resema)> 0){
		$respuesta->alert("La cuenta de correo ya existe el la base de datos");
		$respuesta->assign("txt2emaususi","value","");
		return $respuesta;
	}
	
}

function validaloguin($loguin){
      global $enlace;
	  $respuesta=new xajaxResponse ();
	  
	  $qryloguin= "SELECT logusu FROM usuadm WHERE logusu='$loguin' ";
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


$xajax->registerFunction("validaemail");
$xajax->registerFunction("validaloguin");
$xajax->registerFunction("convertircontrasena");

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
<script type="text/javascript"  src="general/validaform.js"></script>
<script type="text/javascript">

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
	 <form id="form1" name="form1" method="post" action=""  onSubmit="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
	 
        <!--DWLayoutTable-->
        <tr>
          <td height="61" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="6" height="20">&nbsp;</td>
                  <td width="874">&nbsp;</td>
                  <td width="34">&nbsp;</td>
                  <td width="76" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
	<td width="63" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
	<td width="76" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
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
if (isset($_POST['guardarno']))
{
	
	$qryusu = "SELECT COUNT(u.codusuadm) AS usuarios
FROM usuadm u WHERE u.codgru <> 1 AND  u.codgru <> 2 AND u.estusu = 'Activo'";
	$resusu = mysql_query($qryusu, $enlace);
	$filusu = mysql_fetch_assoc($resusu);
	
	$qrylic = "SELECT licusu FROM licusu";
	$reslic = mysql_query($qrylic, $enlace);
	$fillic = mysql_fetch_assoc($reslic);
	
	
	if ($filusu["usuarios"] < $fillic["licusu"]){
			$siguiente = guardar("usuadm",1,"codusuadm",2);
			
			?>
			<script type="text/javascript" language="javascript">
			location="usu.php";
			</script>
			<?php	
		}//fin si licencias
	else{
		echo "Se ha llegado al limite de licencias de usuario (".$fillic["licusu"].") Contacte con ADMIN-WEB para adquirir nuevas licencias";
	}
}

//boton aplicar cambios
if (isset($_POST['aplicarno']))
{
	
	$qryusu = "SELECT COUNT(u.codusuadm) AS usuarios
FROM usuadm u WHERE u.codgru <> 1 AND  u.codgru <> 2 AND u.estusu = 'Activo'";
	$resusu = mysql_query($qryusu, $enlace);
	$filusu = mysql_fetch_assoc($resusu);
	
	$qrylic = "SELECT licusu FROM licusu";
	$reslic = mysql_query($qrylic, $enlace);
	$fillic = mysql_fetch_assoc($reslic);
	
	
	if ($filusu["usuarios"] < $fillic["licusu"]){
	 	$siguiente = guardar("usuadm",1,"codusuadm",2);
		?>
		<script type="text/javascript" language="javascript">
		location="usuedi.php?cod=<?php echo $siguiente?>&acc=1";
		</script>
		<?php	
		}//fin si licencias
	else{
		echo "Se ha llegado al limite de licencias de usuario (".$fillic["licusu"].") Contacte con ADMIN-WEB para adquirir nuevas licencias";
	}
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
          <td width="1172">&nbsp;</td>
          <td width="10">&nbsp;</td>
        </tr>
        
        <tr>
          <td height="52">&nbsp;</td>
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/usuarios.png" width="48" height="48" align="absmiddle" /> Usuarios del Sistema [Crea] <strong></strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="261">&nbsp;</td>
          <td valign="top"><table width="58%" height="260" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="12" height="13"></td>
                  <td width="130"></td>
                  <td width="642"></td>
                  <td width="252"></td>
                </tr>
            <tr>
              <td height="22"></td>
                <td valign="top" ><p>Nombre de Usuario  
                  
                  </p></td>
                  <td valign="top"><input name="txt2nomususi" type="text" id="txt2nomususi" size="40"maxlength="100" title="Nombre" /></td>
                <td></td>
              </tr>
            <tr>
              <td height="20"></td>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
              </tr>
            
            <tr>
              <td height="20"></td>
                <td valign="top" >e-mail                </td>
                <td valign="top">
                  <input name="txt2emaususi" type="text" id="txt2emaususi" size="40"maxlength="100"  title="e-mail" onBlur="xajax_validaemail(this.value)"/>
                  </span></td>
                <td></td>
              </tr>
            <tr>
              <td height="19"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td></td>
              </tr>
            
            <tr>
              <td height="19"></td>
                <td valign="top" >Grupo de Usuario                </td>
                <td valign="top">
                  <select name="cbo2codgrusi" id="cbo2codgrusi" title="Grupo de usuario">
                    <option value="0">Elige</option>
                    <?
	
	$qrygru = "SELECT * FROM gruusu WHERE codgru <> 1 AND codgru <> 3 ORDER BY nomgru ";
	$resgru = mysql_query($qrygru, $enlace);
	while ($filgru = mysql_fetch_array($resgru))
	echo "<option value=\"".$filgru["codgru"]."\">".$filgru["nomgru"]."</option>\n";
	mysql_free_result($resgru);
?>
                    </select>
                  </span></td>
                <td></td>
              </tr>
            <tr>
              <td height="23"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td></td>
              </tr>
            
            <tr>
              <td height="18"></td>
                <td valign="top" >Login                </td>
                <td valign="top">
                  <input name="txt2logususi" type="text" id="txt2logususi" size="30"maxlength="10" onBlur="xajax_validaloguin(this.value)" />
                  </span></td>
                <td></td>
              </tr>
            <tr>
              <td height="13"></td>
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
              <td height="22"></td>
                <td valign="top" >Contrase&ntilde;a </td>
                <td valign="top">
                  <input name="txt2conno" type="password" id="txt2conno" size="30"maxlength="10" onBlur="xajax_convertircontrasena(this.value)" title="Contraseña"/>
                  </span></td>
                <td></td>
              </tr>
            
            <tr>
              <td height="17"></td>
                <td valign="top" >Repetir Contrase&ntilde;a </td>
                <td valign="top">
                  <input name="txt2con1no" type="password" id="txt2con1no" size="30"maxlength="10"  title="Contraseña1"/>
                  </span>
                  <input name="hid2pasususi" type="hidden" id="hid2pasususi" title="Contraseña">
                  <input name="hid1estususi" type="hidden" id="hid1estususi" title="Contrase&ntilde;a" value="Activo">
                  <input name="hid1feccresi" type="hidden" id="hid1feccresi" title="Contrase&ntilde;a" value="<?php echo date("Y-m-j H:i:s") ; ?>"></td>
                <td></td>
              </tr>
            <tr>
              <td height="20"></td>
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