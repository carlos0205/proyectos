<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Admin-Web</title>
<link href="../css/contenido.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	margin-left: 10px;
	margin-top: 10px;
	margin-right: 10px;
	margin-bottom: 10px;
}
-->
</style></head>

<body>
<table width="742" border="0" cellpadding="0" cellspacing="0" class="marcotabla">
  <!--DWLayoutTable-->
  <tr>
    <td width="8" height="16"></td>
    <td width="718"></td>
    <td width="14"></td>
  </tr>
  <tr>
    <td height="27"></td>
    <td valign="top">Errores prensentados durante eliminaci&oacute;n en seccion: <?php echo $_GET["seccion"]?></td>
    <td></td>
  </tr>
  <tr>
    <td height="572"></td>
    <td>&nbsp;</td>
    <td></td>
  </tr>
</table>
</body>
</html>
