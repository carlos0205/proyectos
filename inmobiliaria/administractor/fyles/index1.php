<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

$enlace = enlace();
//variable paginacion de resultados
$_SESSION["numreg"]=10;

?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<link href="../css/pestas.css" rel="stylesheet" type="text/css" />
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
    <td width="100%" valign="bottom" bgcolor="#000000" class="textogris" style="background-image:url(../images/fon_adm.png)"><div align="right"><a href="../../WebHelp_Pro/manualmacaw.htm" target="_blank" title="Manual Admin-Web"><img src="../images/imagenmanual.png" width=150 height="32" border="0"><a href="general/cerrar_sesion.php"><img src="../images/cerses.png" alt="Cerrar Ses&oacute;n de Usuario" width="150" height="32" border="0" /></a></div></td>
  </tr>
  <tr>
    <td height="19" colspan="2" valign="top" bgcolor="#F5F5F5"><?php if ($_SESSION["grupo"] == 1){ ?><script type="text/javascript" language="JavaScript1.2" src="../js/mnusuperadm.js"></script><?php 
	
	}elseif($_SESSION["grupo"] == 5){
	
	?>
	<script type="text/javascript" language="JavaScript1.2" src="../js/mnuminisitio.js"></script>
	<?php
	}else{ ?><script type="text/javascript" language="JavaScript1.2" src="../js/mnuadm.js"></script><?php } ?></td>
  </tr>
  <tr>
    <td height="313" colspan="2" valign="top"><!-- InstanceBeginEditable name="contenido" -->
	  <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="33" colspan="3" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
              <!--DWLayoutTable-->
              <tr>
                <td width="10" height="15"></td>
                <td width="586"></td>
                <td width="585"></td>
              </tr>
              <tr>
                <td height="18"></td>
                <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                <td></td>
              </tr>
          </table></td>
        </tr>
        <tr>
          <td width="7" height="25">&nbsp;</td>
          <td width="1099">&nbsp;</td>
          <td width="9">&nbsp;</td>
        </tr>

        <tr>
          <td height="279">&nbsp;</td>
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="marcotabla">
            <!--DWLayoutTable-->
            <tr>
              <td width="28" height="18"></td>
                  <td width="1041"></td>
                  <td width="28"></td>
              </tr>
            <tr>
              <td height="245"></td>
                <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" >
                  <!--DWLayoutTable-->
                  <tr>
                    <td width="100%" height="245" align="center" valign="middle" class="textogris">CLIENTE AUTORIZADO<br>
                        <br>
                        <?php 
						$datos = GetImageSize('../images/logocli.jpg'); 
						$x = $datos[0]; 
						$y = $datos[1];
						?>                        <img src="../images/logocli.jpg" width="<?php echo $x;?>" height="<?php echo $y;?>"></td>
                      </tr>
                  
                  
                  
                  
                  
                  </table></td>
                  <td></td>
              </tr>
            <tr>
              <td height="14"></td>
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
  
</table>
</form>
</body>
<!-- InstanceEnd --></html>