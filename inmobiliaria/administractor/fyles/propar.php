<?php 
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'propar.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//consulto parametros del producto
$qrypar= "SELECT pp.*, pn.nomniv FROM propar pp, proniv pn WHERE pp.codniv = pn.codniv";
$respar = mysql_query($qrypar, $enlace);
$filpar = mysql_fetch_assoc($respar);

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
	  <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="63" colspan="2" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="7" height="16"></td>
                  <td width="880"></td>
                  <td width="13"></td>
                
                  <td width="56" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" ><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
                  <td width="77" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                <td width="14"></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
                  <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="24" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php
				
				if (isset($_POST['aplicarno'])){
					$pin = $_POST["opspin"];
					$vis= $_POST["opsvis"];
					$man= $_POST["opsman"];
					$col= $_POST["opscar"];
					$niv= $_POST["opsniv"];
					$pre=$_POST["opspre"];
					$exi=$_POST["opsexi"];
					$cla= $_POST["selcla"];
					$idi= $_POST["opsidi"];
					$fab= $_POST["opsfab"];
					$tp= $_POST["opstp"];
					
					$qryactpar = "UPDATE propar SET manpin='$pin', manvis='$vis', proman='$man', carcol='$col',nivacc='$niv', prepro='$pre', exipro = '$exi', codniv='$cla', idi='$idi', fab='$fab', tp='$tp'";
					$resactpar = mysql_query($qryactpar, $enlace);
					
					echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	
				}
				//boton cancelar cambios
				if (isset($_POST['cancelarno'])){
					echo '<script language = JavaScript>
					location = "index1.php";
					</script>';
				}
				?>
              </div></td>
                  <td></td>
                  <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="5"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
         </table></td>
        </tr>
        <tr>
          <td width="4" height="25">&nbsp;</td>
          <td width="1095">&nbsp;</td>
          </tr>
        <tr>
          <td height="61">&nbsp;</td>
          <td valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1177" height="36" valign="top" class="titulos"><img src="../images/carro.png" width="48" height="48" align="absmiddle" /> Parametros de productos    </td>
                </tr>
            <tr>
              <td height="9"></td>
            </tr>
              </table></td>
          </tr>
        <tr>
          <td height="195">&nbsp;</td>
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla">
            <!--DWLayoutTable-->
            <tr>
              <td width="10" height="19">&nbsp;</td>
              <td width="363">&nbsp;</td>
              <td width="779">&nbsp;</td>
              <td width="24">&nbsp;</td>
            </tr>
            <tr>
              <td height="19">&nbsp;</td>
              <td valign="top" class="textonegro">Permite Pintas </td>
              <td valign="top" class="textonegro"><p>
                <label>
                  <input name="opspin" type="radio" value="2" <?php if($filpar["manpin"]==2){?>checked <?php } ?>>
                  Si</label>
                <label>
                  <input type="radio" name="opspin" value="1"  <?php if($filpar["manpin"]==1){?>checked <?php } ?>>
                  No</label>
                <br>
              </p></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="21">&nbsp;</td>
              <td valign="top" class="textonegro">Permite Vistas </td>
              <td valign="top" class="textonegro"><label>
                <input type="radio" name="opsvis" value="2"  <?php if($filpar["manvis"]==2){?>checked <?php } ?>>
				Si</label>
                <label>
                <input type="radio" name="opsvis" value="1"  <?php if($filpar["manvis"]==1){?>checked <?php } ?>>
				No</label></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="20">&nbsp;</td>
              <td valign="top" class="textonegro">Permite Carga de Manuales </td>
              <td valign="top" class="textonegro"><label>
                <input type="radio" name="opsman" value="2" <?php if($filpar["proman"]==2){?>checked <?php } ?>>
				Si</label>
                <label>
                <input type="radio" name="opsman" value="1"  <?php if($filpar["proman"]==1){?>checked <?php } ?>>
				No</label></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="18"></td>
              <td valign="top" class="textonegro">Permite Carta de colores </td>
              <td valign="top" class="textonegro"><label>
                <input type="radio" name="opscar" value="2" <?php if($filpar["carcol"]==2){?>checked <?php } ?>>
				Si</label>
                <label>
                <input type="radio" name="opscar" value="1"  <?php if($filpar["carcol"]==1){?>checked <?php } ?>>
				No</label></td>
              <td></td>
            </tr>
            <tr>
              <td height="18"></td>
              <td valign="top" class="textonegro">Permite Establecer nivel de acceso </td>
              <td valign="top" class="textonegro"><label>
                <input type="radio" name="opsniv" value="2"  <?php if($filpar["nivacc"]==2){?>checked <?php } ?>>
				Si</label>
                <label>
                <input type="radio" name="opsniv" value="1"  <?php if($filpar["nivacc"]==1){?>checked <?php } ?>>
				No</label></td>
              <td></td>
            </tr>
            <tr>
              <td height="21"></td>
              <td valign="top" class="textonegro">Permite Precio de Productos </td>
              <td valign="top"><span class="textonegro">
                <label>
                <input type="radio" name="opspre" value="2"  <?php if($filpar["prepro"]==2){?>checked <?php } ?>>
				Si</label>
                <label>
                <input type="radio" name="opspre" value="1"  <?php if($filpar["prepro"]==1){?>checked <?php } ?>>
				No</label>
              </span></td>
              <td></td>
            </tr>
            <tr>
              <td height="21"></td>
              <td valign="top" class="textonegro">Permite control de existencias </td>
              <td valign="top"><span class="textonegro">
                <label>
                <input type="radio" name="opsexi" value="2"  <?php if($filpar["exipro"]==2){?>checked <?php } ?>>
				Si</label>
                <label>
                <input type="radio" name="opsexi" value="1"  <?php if($filpar["exipro"]==1){?>checked <?php } ?>>
				No</label>
              </span></td>
              <td></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top"><span class="textonegro">Idioma</span></td>
              <td valign="top"><span class="textonegro">
                <label>
                <input type="radio" name="opsidi" value="2"  <?php if($filpar["idi"]==2){?>checked <?php } ?>>
				Si</label>
                <label>
                <input type="radio" name="opsidi" value="1"  <?php if($filpar["idi"]==1){?>checked <?php } ?>>
				No</label>
              </span></td>
              <td></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top"><span class="textonegro">Fabricante</span></td>
              <td valign="top"><span class="textonegro">
                <label>
                <input type="radio" name="opsfab" value="2"  <?php if($filpar["fab"]==2){?>checked <?php } ?>>
				Si</label>
                <label>
                <input type="radio" name="opsfab" value="1"  <?php if($filpar["fab"]==1){?>checked <?php } ?>>
				No</label>
              </span></td>
              <td></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top" class="textonegro">Tipo de producto </td>
              <td valign="top"><span class="textonegro">
                <label>
                <input type="radio" name="opstp" value="2"  <?php if($filpar["tp"]==2){?>checked <?php } ?>>
				Si</label>
                <label>
                <input type="radio" name="opstp" value="1"  <?php if($filpar["tp"]==1){?>checked <?php } ?>>
				No</label>
              </span></td>
              <td></td>
            </tr>
            <tr>
              <td height="16"></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="22"></td>
              <td valign="top" class="textonegro">Maxima Clasificacion permitida del producto </td>
              <td valign="top" class="textonegro">
               <select name="selcla" id="selcla">
                <?
					$niv =  $filpar["codniv"];
					$qryniv = "SELECT * FROM proniv WHERE codniv <> '$niv' ORDER BY codniv ";
					$resniv = mysql_query($qryniv, $enlace);
					echo "<option selected value=\"".$filpar["codniv"]."\">".$filpar["nomniv"]."</option>\n";
					while ($filniv = mysql_fetch_array($resniv))
					echo "<option value=\"".$filniv["codniv"]."\">".$filniv["nomniv"]."</option>\n";
					mysql_free_result($resniv);
				?>
                 </select>
    	         </span></td>
              <td></td>
            </tr>
            <tr>
              <td height="15"></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </table>
          </td>
          </tr>
        <tr>
          <td height="27">&nbsp;</td>
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
