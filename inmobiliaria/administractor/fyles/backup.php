<?php 
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'backup.php';
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
<script type="text/javascript">
function quitar() 
{ 
//alert("No funciona"); 
return false; 
} 
document.oncontextmenu = quitar;
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
	<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<!--DWLayoutTable-->
	<tr>
	<td height="63" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
	<!--DWLayoutTable-->
	<tr>
	<td width="9" height="16"></td>
	<td width="1003"></td>
	<td width="57"></td>
	</tr>
	<tr>
	<td height="19"></td>
	<td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
	<td></td>
	</tr>
	<tr>
	<td height="28"></td>
	<td>&nbsp;</td>
	<td></td>
	</tr>
	</table></td>
	</tr>
	<tr>
	<td width="4" height="25">&nbsp;</td>
	<td width="1092">&nbsp;</td>
	<td width="6">&nbsp;</td>
	</tr>
	<tr>
	<td height="45">&nbsp;</td>
	<td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
	<!--DWLayoutTable-->
	<tr>
	<td width="1177" height="36" valign="top" class="titulos"><img src="../images/data_copy.png" width="48" height="48" />COPIA DE SEGURIDAD  </td>
	</tr>
	<tr>
	<td height="9"></td>
	</tr>
	</table></td>
	</tr>
	<tr>
	<td height="102">&nbsp;</td>
	<td valign="top"><table width="93%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla" style="width: 100%">
	<!--DWLayoutTable-->
	<tr>
	<td width="10" height="8"></td>
	<td width="1097"></td>
	</tr>
	<tr>
	<td height="30" valign="top" bgcolor="#FFFFFF"><!--DWLayoutEmptyCell-->&nbsp;</td>
	<td valign="middle" bgcolor="#FFFFFF" ><!--DWLayoutEmptyCell-->&nbsp;</td>
	</tr>
	<tr>
	<td height="38" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
	<td valign="top"><?php  include("copias/dump_db.php") ; ?></td>
	</tr>
	<tr>
	<td height="28" colspan="2" valign="top" bgcolor="#FFFFFF" class="textonegro"><div align="center"></div></td>
	</tr>
	</table></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td height="24">&nbsp;</td>
	<td valign="top" class="textonegro"><div align="center"></div></td>
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