<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'compar.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//consulto parametros del producto
$qryreg= "SELECT cp.* FROM compar cp ";
$resreg = mysql_query($qryreg, $enlace);
$filreg = mysql_fetch_assoc($resreg);
?>
<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>

<script language="javascript" type="text/javascript">


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
<style type="text/css">
<!--
.Estilo6 {	color: #333333;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
.Estilo9 {font-size: 16px}
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
	  <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="63" colspan="2" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="8" height="16"></td>
                  <td width="930"></td>
                  <td width="80"></td>
                  <td width="57" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"  onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
	<td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="18"></td>
                </tr>
            <tr>
              <td height="19"></td>
              <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
                  <td></td>
                </tr>
            <tr>
              <td colspan="2" rowspan="2" valign="top" class="textoerror"><div align="right">
                <?php

				if (isset($_POST['aplicarno'])){
					$are = $_POST["opsare"];
					$dir= $_POST["opsdir"];
					$tel= $_POST["opstel"];
					$mov= $_POST["opsmov"];
					$ciu=$_POST["opsciu"];
					$percon=$_POST["opspercon"];
					$pai=$_POST["opspai"];
					$estpro=$_POST["opsestpro"];
					$tipcli=$_POST["opstipcli"];
					$ced=$_POST["opsced"];
					$emp=$_POST["opsemp"];
					$car=$_POST["opscar"];
					
					$qryactpar = "UPDATE compar SET are='$are', dir='$dir', tel='$tel', mov='$mov', ciu='$ciu', percon='$percon', pai='$pai', estpro='$estpro', tipcli='$tipcli', ced='$ced', emp='$emp', car='$car'";
					$resactpar = mysql_query($qryactpar, $enlace);
					
					echo '<script language = JavaScript>
					location = "compar.php";
					</script>';
				}
				//boton cancelar cambios
				if (isset($_POST['cancelarno'])){
					echo '<script language = JavaScript>
					location = "index1.php";
					</script>';
				}
				?>
              </div></td>
                  <td height="24"></td>
                  <td></td>
                </tr>
            <tr>
              <td height="5"></td>
              <td></td>
              <td></td>
              <td></td>
              </tr>
         </table></td>
        </tr>
        <tr>
          <td width="4" height="25">&nbsp;</td>
          <td width="1182">&nbsp;</td>
          </tr>
        <tr>
          <td height="61">&nbsp;</td>
          <td valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1177" height="36" valign="top" class="titulos"><img src="../images/contacto.png" width="48" height="48" align="absmiddle" /> Parametros de contacto Web  </td>
                </tr>
            <tr>
              <td height="9"></td>
            </tr>
              </table></td>
          </tr>
        <tr>
          <td height="263">&nbsp;</td>
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla">
            <!--DWLayoutTable-->
            <tr>
              <td width="9" height="19">&nbsp;</td>
                <td width="353">&nbsp;</td>
                <td width="756">&nbsp;</td>
                <td width="26">&nbsp;</td>
              </tr>
            <tr>
              <td height="19">&nbsp;</td>
                <td valign="top" class="textonegro">Area </td>
                <td valign="top" class="textonegro"><p>
                  <label>
                    <input name="opsare" type="radio" value="2" <?php if($filreg["are"]==2){?>checked <?php } ?>>
                    Si</label>
                  <label>
                    <input type="radio" name="opsare" value="1"  <?php if($filreg["are"]==1){?>checked <?php } ?>>
                    No</label>
                  <br>
                  </p></td>
                <td>&nbsp;</td>
              </tr>
            <tr>
              <td height="21">&nbsp;</td>
                <td valign="top" class="textonegro">Direcci&oacute;n</td>
                <td valign="top" class="textonegro"><label>
                  <input type="radio" name="opsdir" value="2" <?php if($filreg["dir"]==2){?>checked <?php } ?>>
                  Si</label>
                  <label>
                    <input type="radio" name="opsdir" value="1"  <?php if($filreg["dir"]==1){?>checked <?php } ?>>
                  No</label></td>
                <td>&nbsp;</td>
              </tr>
            <tr>
              <td height="21">&nbsp;</td>
                <td valign="top" class="textonegro">T&eacute;lefono</td>
                <td valign="top" class="textonegro"><label>
                  <input type="radio" name="opstel" value="2" <?php if($filreg["tel"]==2){?>checked <?php } ?>>
                  Si</label>
                  <label>
                    <input type="radio" name="opstel" value="1"  <?php if($filreg["tel"]==1){?>checked <?php } ?>>
                  No</label></td>
                <td>&nbsp;</td>
              </tr>
            <tr>
              <td height="21"></td>
                <td valign="top" class="textonegro">Movil</td>
                <td valign="top" class="textonegro"><label>
                  <input type="radio" name="opsmov" value="2"  <?php if($filreg["mov"]==2){?>checked <?php } ?>>
                  Si</label>
                  <label>
                    <input type="radio" name="opsmov" value="1"  <?php if($filreg["mov"]==1){?>checked <?php } ?>>
                  No</label></td>
                <td></td>
              </tr>
            <tr>
              <td height="21"></td>
                <td valign="top" class="textonegro">Ciudad</td>
                <td valign="top"><span class="textonegro">
                  <label>
                    <input type="radio" name="opsciu" value="2"  <?php if($filreg["ciu"]==2){?>checked <?php } ?>>
                    Si</label>
                  <label>
                    <input type="radio" name="opsciu" value="1"  <?php if($filreg["ciu"]==1){?>checked <?php } ?>>
                    No</label>
                  </span></td>
                <td></td>
              </tr>
              <tr>
              <td height="21"></td>
                <td valign="top" class="textonegro">Persona De Contacto </td>
                <td valign="top"><span class="textonegro">
                  <label>
                    <input type="radio" name="opspercon" value="2"  <?php if($filreg["percon"]==2){?>checked <?php } ?>>
                    Si</label>
                  <label>
                    <input type="radio" name="opspercon" value="1"  <?php if($filreg["percon"]==1){?>checked <?php } ?>>
                    No</label>
                  </span></td>
                <td></td>
              </tr>
              <tr>
              <td height="21"></td>
                <td valign="top"><span class="textonegro">Pais </span></td>
                <td valign="top"><span class="textonegro">
                  <label>
                    <input type="radio" name="opspai" value="2"  <?php if($filreg["pai"]==2){?>checked <?php } ?>>
                    Si</label>
                  <label>
                    <input type="radio" name="opspai" value="1"  <?php if($filreg["pai"]==1){?>checked <?php } ?>>
                    No</label>
                  </span></td>
                <td></td>
              </tr>
              <tr>
              <td height="21"></td>
                <td valign="top"><span class="textonegro">Estado / Provincia </span></td>
                <td valign="top"><span class="textonegro">
                  <label>
                    <input type="radio" name="opsestpro" value="2"  <?php if($filreg["estpro"]==2){?>checked <?php } ?>>
                    Si</label>
                  <label>
                    <input type="radio" name="opsestpro" value="1"  <?php if($filreg["estpro"]==1){?>checked <?php } ?>>
                    No</label>
                  </span></td>
                <td></td>
              </tr>
            <tr>
              <td height="19"></td>
                <td valign="top"><span class="textonegro">Permite Tipo de Cliente </span></td>
                <td valign="top"><span class="textonegro">
                  <label>
                  <input type="radio" name="opstipcli" value="2"  <?php if($filreg["tipcli"]==2){?>checked <?php } ?>>
Si</label>
                  <label>
                  <input type="radio" name="opstipcli" value="1"  <?php if($filreg["tipcli"]==1){?>checked <?php } ?>>
No</label>
                </span></td>
                <td></td>
              </tr>
            <tr>
              <td height="21"></td>
              <td valign="top"><span class="textonegro">Cedula</span></td>
                <td valign="top"><span class="textonegro">
                  <label>
                  <input type="radio" name="opsced" value="2"  <?php if($filreg["ced"]==2){?>checked <?php } ?>>
Si</label>
                  <label>
                  <input type="radio" name="opsced" value="1"  <?php if($filreg["ced"]==1){?>checked <?php } ?>>
No</label>
                </span></td>
                <td></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top"><span class="textonegro">Empresa</span></td>
              <td valign="top"><span class="textonegro">
                <label>
                <input type="radio" name="opsemp" value="2"  <?php if($filreg["emp"]==2){?>checked <?php } ?>>
Si</label>
                <label>
                <input type="radio" name="opsemp" value="1"  <?php if($filreg["emp"]==1){?>checked <?php } ?>>
No</label>
              </span></td>
              <td></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top"><span class="textonegro">Cargo</span></td>
              <td valign="top"><span class="textonegro">
                <label>
                <input type="radio" name="opscar" value="2"  <?php if($filreg["car"]==2){?>checked <?php } ?>>
Si</label>
                <label>
                <input type="radio" name="opscar" value="1"  <?php if($filreg["car"]==1){?>checked <?php } ?>>
No</label>
              </span></td>
              <td></td>
            </tr>
            <tr>
              <td height="37"></td>
              <td>&nbsp;</td>
              <td></td>
              <td></td>
            </tr>
	       </table></td>
          </tr>
        <tr>
          <td height="19">&nbsp;</td>
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
