<?php 
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'vis.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

require_once 'general/libchart/classes/Point.php';
require_once 'general/libchart/classes/Axis.php';
require_once 'general/libchart/classes/Color.php';
require_once 'general/libchart/classes/Primitive.php';
require_once 'general/libchart/classes/Text.php';
require_once 'general/libchart/classes/Chart.php';
require_once 'general/libchart/classes/PieChart.php';
require_once 'general/libchart/classes/BarChart.php';
require_once 'general/libchart/classes/LineChart.php';
require_once 'general/libchart/classes/VerticalChart.php';
require_once 'general/libchart/classes/HorizontalChart.php';

//aleatorio para imagen estadistica
$numero_aleatorio = rand(1,100);
?>
<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<style type="text/css">

#visitas{
	position:relative;
	left: 1px;
	width: 100%;
	height: 170;
	top: 0px;
	overflow: scroll;
	overflow-x:hidden; 
	
}
</style>
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
      <table width="100%" height="544" border="0" cellpadding="0" cellspacing="0" class="textonegro">
        <!--DWLayoutTable-->
        <tr>
          <td height="63" colspan="6" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro" lass="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="9" height="16"></td>
                  <td width="897"></td>
                  <td width="119"></td>
                  <td width="55" rowspan="3" align="center" valign="middle"><a href="general/exportar.php"><img src="../images/exportexcel.png" alt="Exportar Resultados a Excel" width="32" height="32" border="0" /><br>
                  Exportar</a></td>
                  <td width="15"></td>
            </tr>
            <tr>
              <td height="19"></td>
                <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
                  <td></td>
            </tr>
            
            
            
            <tr>
              <td height="28" colspan="2" valign="top" class="textoerror"><div align="right">
                
                </div></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td width="4" height="44">&nbsp;</td>
          <td width="324">&nbsp;</td>
          <td width="54">&nbsp;</td>
          <td width="33">&nbsp;</td>
          <td width="582">&nbsp;</td>
          <td width="106">&nbsp;</td>
        </tr>
        
        <tr>
          <td height="74">&nbsp;</td>
          <td colspan="5" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1177" height="36" valign="top" class="titulos"><img src="../images/vissec.png" width="48" height="48" align="absmiddle" /> Estad&iacute;stica de visitas por secci&oacute;n  </td>
                </tr>
            <tr>
              <td height="9"></td>
            </tr>
            
              </table></td>
          </tr>
        <tr>
          <td height="16"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="2"></td>
          <td colspan="2" rowspan="3" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="231" height="22" valign="top" >Total de visitas </td>
            <td width="171" valign="top"><span class="titmenu"><strong>
              <?
			$consulta="SELECT COUNT(codvis) FROM vis"; 
			$resultado = mysql_query($consulta, $enlace);
			$fila = mysql_fetch_array($resultado);
			echo $fila["COUNT(codvis)"];
		 ?></strong></span></td>
            </tr>
            <tr>
              <td height="25" valign="top" >Total de visitas usuarios diferentes </td>
            <td valign="top">
              <span class="titmenu"><strong>
                <?
			$consulta="SELECT COUNT(DISTINCT ipvis) FROM vis"; 
			$resultado = mysql_query($consulta, $enlace);
			$fila = mysql_fetch_array($resultado);
			echo $fila["COUNT(DISTINCT ipvis)"];
		 ?></strong></span></td>
            </tr>
            <tr>
              <td height="22" valign="top" >Total de visitas hoy </td>
            <td valign="top"><strong>
              <?
			$fecha = date("Y-n-d");
			$consulta="SELECT COUNT(codvis) FROM vis WHERE date(fecvis) = '$fecha'"; 
			$resultado = mysql_query($consulta, $enlace);
			$fila = mysql_fetch_array($resultado);
			
			$qrydif = "SELECT COUNT(DISTINCT ipvis) AS diferentes FROM vis  WHERE date(fecvis) = '$fecha'";
			$resdif = mysql_query($qrydif, $enlace);
			$fildif = mysql_fetch_assoc($resdif);
			
			echo $fila["COUNT(codvis)"] ."-".$fildif["diferentes"];
			
		 ?>
            </strong></td>
            </tr>
            <tr>
              <td height="24" valign="top" >Maximas visitas en un d&iacute;a </td>
            <td valign="top" class="titmenu"><strong>
              <?
			$consulta="SELECT COUNT(codvis) as total, DATE_FORMAT( fecvis, '%d-%m-%Y' ) AS fecha FROM vis GROUP BY fecvis ORDER BY total DESC"; 
			$resultado = mysql_query($consulta, $enlace);
			$fila = mysql_fetch_array($resultado);
			echo $fila["total"]." El d&iacute;a: ".$fila["fecha"];
		 ?>
              </strong></td>
            </tr>
            <tr>
              <td height="21"></td>
                <td></td>
                </tr>
          </table></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        
        <tr>
          <td height="36"></td>
          <td>&nbsp;</td>
          <td valign="top" >Estadistica de visitas por secci&oacute;n </td>
          <td></td>
        </tr>
        
        <tr>
          <td height="76"></td>
          <td></td>
          <td rowspan="3" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
            <!--DWLayoutTable-->
            
            <tr>
              <td width="550" height="250" valign="top"><?php 
			  
			$qryvissec = "SELECT codpag, nompag FROM pagsite ORDER BY codpag ASC";
		    $resvissec = mysql_query($qryvissec, $enlace);
			
			$chart = new LineChart();
			
			 while ($filvissec = mysql_fetch_array($resvissec)){
			  
				$link = $filvissec["codpag"];
				$qryvissec1="SELECT COUNT(codvis) as visitas FROM vis WHERE linkvis = '$link' GROUP BY linkvis"; 
				$resvissec1 = mysql_query($qryvissec1, $enlace);

					$filvissec1 = mysql_fetch_array($resvissec1);
					
					if ($filvissec1['visitas'] == ""){
					$visitas = 0;
					}else{
					$visitas = $filvissec1['visitas'];
					}
					$chart->addPoint(new Point($filvissec["nompag"] , $visitas));
					
			}
		 	$chart->setTitle("Estadisticas detalladas por sección");
			$chart->render("../images/estadisticasseccion.png");
	  
		 	?>
                <br>
                <img src="../images/estadisticasseccion.png?<?php echo $numero_aleatorio;?>" width="550" height="250"></td>
                </tr>
            <tr>
              <td height="6"></td>
                </tr>
          </table></td>
          <td></td>
        </tr>
        <tr>
          <td height="7"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        
        <tr>
          <td height="198"></td>
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="173" height="22" valign="middle" bgcolor="#FFFFFF" ><div align="center">SECCION</div></td>
                  <td width="171" valign="middle" bgcolor="#FFFFFF" ><div align="center">VISITAS</div></td>
                </tr>
            <tr>
              <td height="18" colspan="2" valign="top"><div id="visitas"><?php
			  $qryvis = "SELECT codpag, nompag FROM pagsite ORDER BY codpag";
			  $resvis = mysql_query($qryvis, $enlace);
			  	echo "<table width=100%>\n";
				echo "<tr>\n";
				echo "<th width=173 ></th><th width=171></th>\n";
				echo "</tr>\n";
			  while ($filvis = mysql_fetch_assoc($resvis)){
			  
				$link = $filvis["codpag"];
				$qryvissec="SELECT COUNT(codvis) as visitas FROM vis WHERE linkvis = '$link' GROUP BY linkvis"; 
				$resvissec = mysql_query($qryvissec, $enlace);
				$filvissec = mysql_fetch_array($resvissec);
				echo "<tr>\n";
				echo"<td class=textonegro ><strong>".$filvis["nompag"]."</td>";
				echo "<td class=textonegro align = right>".$filvissec["visitas"]."</td>";
				echo "</tr>";
			  
			  }
			  echo "</table>\n";
			  $qryvissec = "SELECT p.codpag, p.nompag, COUNT(v.codvis) as visitas  FROM pagsite p, vis v WHERE v.linkvis = p.codpag GROUP BY v.linkvis ORDER BY p.codpag";
			  $_SESSION["consulta"] = $qryvissec;
	  		  mysql_free_result($resvis);
			  ?></div></td>
                </tr>
            
          </table></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="28"></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
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