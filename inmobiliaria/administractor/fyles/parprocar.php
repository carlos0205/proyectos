<?php 
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'parprocar.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//consulto parametros del producto
$qryreg= "SELECT * FROM parprocar ";
$resreg = mysql_query($qryreg, $enlace);
$filreg = mysql_fetch_assoc($resreg);
?>
<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>

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
                  <td width="65"></td>
                  <td width="56" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
                  <td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="13"></td>
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

					
					$qryactpar = "UPDATE parprocar SET hcc='".$_POST["cbo2hccsi"]."'";
					$resactpar = mysql_query($qryactpar, $enlace);
					
					echo '<script language = JavaScript>
					location = "parprocar.php";
					</script>';
				}
				
				//boton cancelar cambios
				if (isset($_POST['cancelar'])){
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
          <td width="1148">&nbsp;</td>
          </tr>
        <tr>
          <td height="61">&nbsp;</td>
          <td valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1177" height="36" valign="top" class="titulos"><img src="../images/carro.png" width="48" height="48" align="absmiddle" /> Parametros de Productos </td>
                </tr>
            <tr>
              <td height="9"></td>
            </tr>
              </table></td>
          </tr>
        <tr>
          <td height="54">&nbsp;</td>
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla">
            <!--DWLayoutTable-->
            <tr>
              <td width="9" height="19">&nbsp;</td>
                  <td width="354">&nbsp;</td>
                  <td width="758">&nbsp;</td>
                  <td width="25">&nbsp;</td>
                </tr>
            <tr>
              <td height="19">&nbsp;</td>
                  <td valign="top" class="textonegro">Habilitar Carrito de Compra </td>
                  <td valign="top" class="textonegro"><p>
                    <label></label>
                    <select name="cbo2hccsi" id="cbo2hccsi" title="habilitar carro">
                      <?php
					  $qrypub="SELECT 'Si' AS publica
						UNION
						SELECT 'No' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['hcc']."\">".$filreg['hcc']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["hcc"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
                                        </select>
                    <br>
                  </p></td>
                  <td>&nbsp;</td>
                </tr>
            <tr>
              <td height="12"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
            </table></td>
          </tr>
        <tr>
          <td height="3"></td>
          <td></td>
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
