<?php 
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'mantab.php';
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
          <td height="63" colspan="2" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="8" height="16"></td>
                  <td width="974"></td>
                  <td width="31"></td>
                  <td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="11"></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
            </tr>
            
            <tr>
              <td colspan="2" rowspan="2" valign="top" class="textoerror"><div align="right">
			  <?php
				if (isset($_POST['iniciar'])){
					$qrybd = "SELECT nombd FROM licusu";
					$resbd = mysql_query($qrybd, $enlace);
					$filbd=mysql_fetch_assoc($resbd);
					$dbname = $filbd["nombd"];
					$result = mysql_list_tables($dbname);
			
					while ($row = mysql_fetch_row($result)) {
						$tablas = array ("anos","cc","ciudad","compar","condiciones","condicionesdet","deppro","dia","estcon","forpar","gruprog","gruusu","idi","ip","licusu","mes","mon","motcon","motcondet","pais","pedestdet","progweb","prolispre","provin","propar","pubpar","sesiones","sesionest","terclicar","tercliestciv","tercliestcivdet","terclisex","terclisexdet","terclitipest","terclitraint","terclitranac","tipemp","tippro","tippub", "tipusuter","usuadm","usutercli","idipub","pagsite","pagsiteint", "clipar", "pubpar", "bannerpos", "progwebcli", "proniv", "pedest");
						if (!in_array($row[0],$tablas)) {	
							$qry = "DELETE FROM $row[0] ; ";
							$res = mysql_query($qry,$enlace);
							$qry1 = "ALTER TABLE `$row[0]` PACK_KEYS =0 CHECKSUM =0 DELAY_KEY_WRITE =0 AUTO_INCREMENT =1;";
							$res = mysql_query($qry1, $enlace);
						}
			   		}
					//inserto defecto en tablas
					
					//Consulto los idiomas
					$queryidi= "SELECT codidi FROM idipub";
					$residi = mysql_query($queryidi, $enlace);
					
					while ($filidi = mysql_fetch_assoc($residi)){
						$qrytipter="INSERT INTO tipter VALUES ('".$filidi['codidi']."') ";
						$restipter=mysql_query($qrytipter,$enlace);
	
						$qrytipterdet="INSERT INTO tipterdet VALUES ('".$filidi['codidi']."','".$filidi['codidi']."','idenfinido','".$filidi['codidi']."')";
						$restipterdet=mysql_query($qrytipterdet,$enlace);
					}

					$qryareacon="INSERT INTO areacon VALUES ('1','1','2') ";
					$resareacon=mysql_query($qryareacon,$enlace);
					
					$qryareacondet="INSERT INTO areacondet VALUES ('1','1','Recurso Humano','1') ";
					$resareacondet=mysql_query($qryareacondet,$enlace);
					
					$qrybanner="INSERT INTO banner VALUES ('1', 'defecto', '', '1', '1', '', '2', '', '', '', '', '', '1', 'tipoint') ";
					$resbanner=mysql_query($qrybanner,$enlace);
					
					$qrybannerpag="INSERT INTO bannerpag VALUES ('1', '1', '1', '') ";
					$resbannerpag=mysql_query($qrybannerpag,$enlace);
										
					$qrycla="INSERT INTO cla VALUES ('1', '1', '', '', '', 'tipoint')";
					$rescla=mysql_query($qrycla,$enlace);
					
					$qrycladet="INSERT INTO cladet VALUES ('1', '1', 'defecto', '', '1')";
					$rescladet=mysql_query($qrycladet,$enlace);
					
					$qrycla="INSERT INTO subgru VALUES ('1', '1', '', '', '', 'tipoint')";
					$rescla=mysql_query($qrycla,$enlace);
					
					$qrycladet="INSERT INTO subgrudet VALUES ('1', '1', 'defecto', '1')";
					$rescladet=mysql_query($qrycladet,$enlace);
					
					$qryfab="INSERT INTO fab VALUES ('1', 'defecto', '', '', '', '', '1', 'tipoint')";
					$resfab=mysql_query($qryfab,$enlace);
					
					$qrypagsiteint="INSERT INTO pagsiteint VALUES ('1', '1', '', '', '1', '2', '', '', '1', 'lamado')";
					$respagsiteint=mysql_query($qrypagsiteint,$enlace);

					$qrytippqrs="INSERT INTO tippqrs VALUES ('1', '1', 'tipoint') ";
					$restippqrs=mysql_query($qrytippqrs,$enlace);
					
					$qrytippqrsdet="INSERT INTO tippqrsdet VALUES ('1', '1', 'defecto', '', '1') ";
					$restippqrsdet=mysql_query($qrytippqrsdet,$enlace);
					
					$qrybarrio="INSERT INTO barrio VALUES ('1', 'defecto', '1') ";
					$resbarrio=mysql_query($qrybarrio,$enlace);
					
					$qryent="INSERT INTO ent VALUES ('1', 'defecto', '1') ";
					$resent=mysql_query($qryent,$enlace);

					echo "Proceso finalizado con exito.";
				}
				//boton cancelar cambios
				if (isset($_POST['cancelar'])){
					echo '<script language = JavaScript>
					location = "index1.php";
					</script>';
				}
			?>
              </div></td>
                  <td height="23"></td>
                  <td></td>
            </tr>
            <tr>
              <td height="5"></td>
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
              <td width="1177" height="36" valign="top" class="titulos"><img src="../images/addedit.png" width="48" height="48" align="absmiddle" />Mantenimiento de tablas   </td>
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
              <td width="12" height="19">&nbsp;</td>
              <td width="1127">&nbsp;</td>
              <td width="11">&nbsp;</td>
            </tr>
            <tr>
              <td height="34">&nbsp;</td>
              <td valign="top"><span class="textoerror">ATENCION: </span>Este proceso dejar&aacute; en blanco las tablas del sitio Web y reiniciara los indices de las tablas . Este proceso es irreversible. Si esta seguro de este proceso de click sobre el boton Iniciar mantenimiento </span></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="23">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="29">&nbsp;</td>
              <td valign="top"><span class="Estilo6">
                <input name="iniciar" type="submit" id="iniciar" value="Iniciar Mantenimiento">
              </span></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="105">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
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
