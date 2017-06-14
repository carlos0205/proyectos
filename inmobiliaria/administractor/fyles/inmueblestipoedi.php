<?php
session_start();
include("../../administractor/fyles/general/conexion.php") ;
include("../../administractor/fyles/general/sesion.php");
include("../../administractor/fyles/general/operaciones.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'inmueblestipoedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();



$cod = $_GET["cod"];

$qryreg = "SELECT codtipinmueble, nomtipinmueble FROM inmuebletipo WHERE codtipinmueble = '$cod'";
$resreg= mysql_query($qryreg, $enlace);
$filreg = mysql_fetch_assoc($resreg);


?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../../administractor/js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<script type="text/javascript"  src="../../administractor/fyles/general/validaform.js"></script>
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
</script>

<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
<link href="../../administractor/css/contenido.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
background-image:url(../../administractor/images/fondomacaw.jpg);
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
    <td width="300" height="49" valign="top" bgcolor="#000000"><img src="../../administractor/images/encabezado.png" width="300" height="49" /></td>
    <td width="100%" valign="bottom" bgcolor="#000000" class="textogris" style="background-image:url(../../administractor/images/fon_adm.png)"><div align="right"><a href="../../administractor/fyles/general/cerrar_sesion.php"><img src="../../administractor/images/cerses.png" alt="Cerrar Ses&oacute;n de Usuario" width="150" height="32" border="0" /></a></div></td>
  </tr>
  <tr>
    <td height="19" colspan="2" valign="top" bgcolor="#F5F5F5"><?php if ($_SESSION["grupo"] == 1){ ?><script type="text/javascript" language="JavaScript1.2" src="../../administractor/js/mnusuperadm.js"></script><?php }else{ ?><script type="text/javascript" language="JavaScript1.2" src="../../administractor/js/mnuadm.js"></script><?php } ?></td>
  </tr>
  <tr>
    <td height="313" colspan="2" valign="top"><!-- InstanceBeginEditable name="contenido" -->
        <form id="form1" name="form1" method="post" action=""  onSubmit="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
          <!--DWLayoutTable-->
          <tr>
            <td height="61" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
                <!--DWLayoutTable-->
                <tr>
                  <td width="1121" height="20">&nbsp;</td>
                  <td width="31">&nbsp;</td>
                   <td width="91" rowspan="3" align="center" valign="middle"><button    name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none; " class="pointer" onClick="return crearregistro()"><img width="32" src="../../administractor/images/guardar.png"  /><br>
                  Guardar</button></td>
	<td width="75" rowspan="3" align="center" valign="middle"><button    name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none; " class="pointer" onClick="return crearregistro()"><img width="32" src="../../administractor/images/aplicar.png"  /><br>
                  Aplicar</button></td>
	<td width="93" rowspan="3" align="center" valign="middle" class="textonegro"><button  name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none; " class="pointer"><img src="../../administractor/images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                </tr>
                <tr>
                  <td height="19" valign="top" class="textonegro" >Usuario: <?php echo $_SESSION["logueado"]?></td>
                <td>&nbsp;</td>
                </tr>
                
                
                
                <tr>
                  <td height="26" valign="top" class="textoerror"><div align="right">
                      <?php

if (isset($_POST['guardarno'])){

		actualizar("inmuebletipo",2,$cod,"codtipinmueble","inmueblestipo.php");
		
	}
	//boton aplicar cambios
	if (isset($_POST['aplicarno'])){

		actualizar("inmuebletipo",2,$cod,"codtipinmueble","inmueblestipoedi.php?cod=$cod");
				
	} 
	
//boton cancelar cambios
if (isset($_POST['cancelarno']))
{
echo '<script language = JavaScript>
location = "inmueblestipo.php";
</script>';
}
 ?>
                  </div></td>
                <td>&nbsp;</td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td width="4" height="19">&nbsp;</td>
            <td width="1122">&nbsp;</td>
            <td width="10">&nbsp;</td>
          </tr>
          <tr>
            <td height="43">&nbsp;</td>
            <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="textonegro" style="width: 100%">
                <!--DWLayoutTable-->
                <tr>
                  <td width="1132" height="41" valign="top" class="titulos"><img src="../../administractor/images/tipinmueble.png" width="32" height="32" align="absmiddle">Tipos de Inmuebles<strong></strong> [Edita] </td>
                </tr>
                <tr>
                  <td height="2"></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="13">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          
          <tr>
            <td height="56">&nbsp;</td>
            <td valign="top"><table width="58%" height="56" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
                <!--DWLayoutTable-->
                <tr>
                  <td width="34" height="13"></td>
                  <td width="170"></td>
                  <td width="383"></td>
                  <td width="493"></td>
                </tr>
                <tr>
                  <td height="22">&nbsp;</td>
                  <td valign="middle" class="textonegro" ><p>Nombre  Tipo de Inmueble: </p></td>
                  <td valign="top" class="textonegro"><input name="txt2nomtipinmueblesi" title="Nombre del tipo de Inmueble" type="text" id="txt2nomtipinmueblesi" value="<?php echo $filreg["nomtipinmueble"];?>" size="60"maxlength="100" /></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td height="19"></td>
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
    <td height="32" colspan="2" valign="top"><div align="center" class="textonegro">  <strong>ADMIN-WEB</strong> , </div></td>
  </tr>
</table>
</form>
</body>
<!-- InstanceEnd --></html>