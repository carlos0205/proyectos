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

#DIAS{
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
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
	  <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
        <!--DWLayoutTable-->
        <tr>
          <td height="63" colspan="6" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="8" height="16"></td>
                  <td width="939"></td>
                  <td width="19"></td>
                  <td width="69" rowspan="3" align="center" valign="middle"><a href="general/exportar.php"><img src="../images/exportexcel.png" alt="Exportar Resultados a Excel" width="32" height="32" border="0" /><br>
                  Exportar</a></td>
                  <td width="17"></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
              <td></td>
            </tr>
            
            
            <tr>
              <td height="28" colspan="2" valign="top" class="textoerror"><div align="right"><?php
			  if (isset($_POST['ver']))
			{
			$mes = $_POST["selmes"]."-".$_POST["selano"];
			}else{
			$mes = date("m-Y"); 
			}
			  ?>
                
              </div></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td width="4" height="25">&nbsp;</td>
          <td width="344">&nbsp;</td>
          <td width="44">&nbsp;</td>
          <td width="53">&nbsp;</td>
          <td width="551">&nbsp;</td>
          <td width="108">&nbsp;</td>
        </tr>
        <tr>
          <td height="61">&nbsp;</td>
          <td colspan="5" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1177" height="36" valign="top" class="titulos"><img src="../images/visdia.png" width="48" height="48" align="absmiddle" /> Estad&iacute;stica visitas por d&iacute;a  </td>
                </tr>
            <tr>
              <td height="9"></td>
            </tr>
              </table></td>
          </tr>
        <tr>
          <td height="14"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="20"></td>
          <td colspan="2" rowspan="4" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="231" height="22" valign="top" >Total de visitas </td>
            <td width="159" valign="top"><span class="titmenu"><strong>
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
            <td valign="top">
              <span class="titmenu"><strong>
                <?
			$fecha = date("Y-n-d"); 

			$consulta="SELECT COUNT(codvis) FROM vis WHERE date(fecvis) = '$fecha'"; 
			$resultado = mysql_query($consulta, $enlace);
			$fila = mysql_fetch_array($resultado);
			
			$qrydif = "SELECT COUNT(DISTINCT ipvis) AS diferentes FROM vis  WHERE date(fecvis) = '$fecha'";
			$resdif = mysql_query($qrydif, $enlace);
			$fildif = mysql_fetch_assoc($resdif);
			
			echo $fila["COUNT(codvis)"] ."-".$fildif["diferentes"];
			
		 ?></strong></span></td>
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
              <td height="22"></td>
                <td></td>
                </tr>
          </table></td>
          <td>&nbsp;</td>
          <td valign="top" >Estad&iacute;stica visitas por d&iacute;a del mes: <?php  echo $mes; ?>
          </td>
          <td></td>
        </tr>
        <tr>
          <td height="26"></td>
          <td></td>
          <td valign="top" >Mes  
            <select name="selmes" id="selmes">
              <option value="01">Enero</option>
              <option value="02">Febrero</option>
              <option value="03">Marzo</option>
              <option value="04">Abril</option>
              <option value="05">Mayo</option>
              <option value="06">Junio</option>
              <option value="07">Julio</option>
              <option value="08">Agosto</option>
              <option value="09">Septiembre</option>
              <option value="10">Octubre</option>
              <option value="11">Noviembre</option>
              <option value="12">Diciembre</option>
            </select>
            A&ntilde;o
            <select name="selano" id="selano">
              <option value="2008">2008</option>
              <option value="2009">2009</option>
              <option value="2010">2010</option>
              <option value="2011">2011</option>
              <option value="2012">2012</option>
              <option value="2013">2013</option>
              <option value="2014">2014</option>
              <option value="2015">2015</option>
              <option value="2016">2016</option>
              <option value="2017">2017</option>
              <option value="2018">2018</option>
              <option value="2019">2019</option>
              <option value="2020">2020</option>
              </select>             <input name="ver" type="submit" id="ver" value="Ver"/>              </td>
          <td></td>
        </tr>
        <tr>
          <td height="2"></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="67"></td>
          <td></td>
          <td rowspan="3" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="551" height="13"></td>
                </tr>
            <tr>
              <td height="268" valign="top">
                
                <div align="left">
                  <?PHP 			
			  
			  $qryvis="SELECT DATE_FORMAT(fecvis, '%d-%m-%Y') AS fecha, COUNT(codvis) FROM vis WHERE DATE_FORMAT(fecvis, '%m-%Y') = '$mes' GROUP BY fecha ORDER BY fecvis ASC"; 
			$resvis = mysql_query($qryvis, $enlace);
						
			$chart = new LineChart();
			  
			while ($filvis = mysql_fetch_array($resvis))
			{
					$chart->addPoint(new Point($filvis["fecha"] , $filvis["COUNT(codvis)"]));
						
			}
		 	$chart->setTitle("Estadísticas detalladas por día");
			$chart->render("../images/estadisticasdia.png");
		 	?>
                  <br>                
                  <img src="../images/estadisticasdia.png?<?php echo $numero_aleatorio;?>" width="550" height="250"></div></td>
                </tr>
          </table></td>
          <td></td>
        </tr>
        
        <tr>
          <td height="20"></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="194"></td>
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="173" height="24" valign="middle" bgcolor="#FFFFFF" ><div align="center">DIA</div></td>
                <td width="171" valign="middle" bgcolor="#FFFFFF" ><div align="center">VISITAS</div></td>
              </tr>
            <tr>
              <td height="157" colspan="2" valign="top"><div align="center" id="DIAS">
                <?php 
	  			$qryvisdia="SELECT DATE_FORMAT(fecvis, '%d-%m-%Y') AS fecha, COUNT(codvis) AS visitas FROM vis WHERE DATE_FORMAT(fecvis, '%m-%Y') = '$mes' GROUP BY fecha ORDER BY fecha ASC"; 
			$resvisdia = mysql_query($qryvisdia, $enlace);
			echo "<table width=100%>\n";
			echo "<tr>\n";
			echo "<th width=173 ></th><th width=171></th>\n";
			echo "</tr>\n";
			while ($filvisdia = mysql_fetch_array($resvisdia))
			{
					echo "<tr>\n";
					echo"<td class=textonegro><strong>".$filvisdia["fecha"]."</strong></td>";
					echo "<td class=textonegro align = right >".$filvisdia["visitas"]."</td>";
					echo "</tr>";						
			}
			echo "</table>\n";
			
			$_SESSION["consulta"] = $qryvisdia;
			
mysql_free_result($resvisdia);
		 	?>
                </div></td>
                </tr>
            
          </table></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="25"></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
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