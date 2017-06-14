<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

include("fckeditor/fckeditor.php") ;

$enlace = enlace();

//consulto parametros del producto
$qryinfmul= "SELECT infmul FROM licusu";
$resinfmul = mysql_query($qryinfmul, $enlace);
$filinfmul = mysql_fetch_assoc($resinfmul);
?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
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
                  <td width="6" height="20">&nbsp;</td>
                  <td width="947">&nbsp;</td>
                  <td width="33">&nbsp;</td>
                </tr>
                <tr>
                  <td height="15"></td>
                  <td valign="top" >Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
                </tr>
                <tr>
                  <td height="26"></td>
                  <td>&nbsp;</td>
                  <td></td>
                </tr>
                
            </table></td>
          </tr>
          <tr>
            <td width="4" height="25">&nbsp;</td>
            <td width="1188">&nbsp;</td>
            <td width="11">&nbsp;</td>
          </tr>
          <tr>
            <td height="52">&nbsp;</td>
            <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="1390" height="52" valign="top" class="titulos"><img src="../images/multimedia.png" width="48" height="48" align="middle" />C&oacute;digo de ayuda Mp3, Video, PDF</td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="20">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="56">&nbsp;</td>
            <td valign="top"><table width="58%" height="116" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
                <!--DWLayoutTable-->
                <tr>
                  <td width="10" height="17"></td>
                  <td width="1148"></td>
                  <td width="12"></td>
                </tr>
                <tr>
                  <td height="18"></td>
                  <td valign="top" class="textonegro"><?PHP echo html_entity_decode($filinfmul["infmul"])?></td>
                  <td></td>
                </tr>
                <tr>
                  <td height="79"></td>
                  <td>&nbsp;</td>
                  <td></td>
                </tr>
                        </table></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td height="45">&nbsp;</td>
            <td>&nbsp;</td>
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