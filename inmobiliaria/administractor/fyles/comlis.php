<?php
session_start();
include("general/paginador.php") ;
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'comlis.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);
$enlace = enlace();

$fectoday = date("Y-n-j H:i:s ");
//valido si grupo al que pertenece el areaario es al de responde comentarios web
	
	if (isset($_POST['filtrar']))
	{
		
		
		$query_registros = "SELECT cw.codconweb, cw.fecconweb, cw.nomconweb, cw.dirconweb, cw.emaconweb, cw.telconweb, cw.desconweb, p.cn, d.nomdep, c.nomciu, acd.nomarea, u.nomusu, HOUR(TIMEDIFF('$fectoday', cw.fecconweb)) AS tietra 
		FROM conweb AS cw 
		LEFT JOIN ciudad  AS c ON cw.codciu = c.codciu
		LEFT JOIN deppro AS d ON c.coddep = d.coddep
		LEFT JOIN pais AS p ON d.ci = p.ci
		LEFT JOIN areacon AS ac ON cw.codarea = ac.codarea
		LEFT JOIN areacondet AS acd ON ac.codarea = acd.codarea AND acd.codidi = 1
		LEFT JOIN usuadm AS u  ON ac.codusuadm = u.codusuadm
		WHERE  cw.estcon ='Activo' ";
		
		$area = $_POST["selarea"];
		
		if($area <> 0){
			$query_registros .= " AND ac.codarea = $area";
		}
		
		if ($_SESSION["grupo"] == 2){
			$query_registros .= " AND u.codusuadm = ".$_SESSION["enlineaadm"]."";
		}
		
		$query_registros .= " 	ORDER BY cw.fecconweb DESC";
		
		$_SESSION['qryfiltroconact'] = $query_registros;
	}
	else
	{
	$query_registros = "SELECT cw.codconweb, cw.fecconweb, cw.nomconweb, cw.dirconweb, cw.emaconweb, cw.telconweb, cw.desconweb, p.cn, d.nomdep, c.nomciu, acd.nomarea, u.nomusu, HOUR(TIMEDIFF('$fectoday', cw.fecconweb)) AS tietra 
		FROM conweb AS cw 
		LEFT JOIN ciudad  AS c ON cw.codciu = c.codciu
		LEFT JOIN deppro AS d ON c.coddep = d.coddep
		LEFT JOIN pais AS p ON d.ci = p.ci
		LEFT JOIN areacon AS ac ON cw.codarea = ac.codarea
		LEFT JOIN areacondet AS acd ON ac.codarea = acd.codarea AND acd.codidi = 1
		LEFT JOIN usuadm AS u  ON ac.codusuadm = u.codusuadm 
		WHERE  cw.estcon ='Activo'
		";
		
		if ($_SESSION["grupo"] == 2){
			$query_registros .= " AND u.codusuadm = ".$_SESSION["enlineaadm"]."";
		}
		
		$query_registros .= " 	ORDER BY cw.fecconweb DESC";
		$_SESSION['qryfiltroconact'] = $query_registros;
	}


if (isset($_SESSION['qryfiltroconact'])){ 
$query_registros = $_SESSION['qryfiltroconact'];
}
else{
$query_registros = "SELECT tiempo FROM sesionest WHERE tiempo = -1";
}
destruyesesiones("qryfiltroconact");

$_SESSION["consulta"] = $query_registros;


include("general/paginadorinferior.php") ;
//consulto parametros comentario
$qrypar= "SELECT * FROM compar";
$respar = mysql_query($qrypar, $enlace);
$filpar = mysql_fetch_assoc($respar);
?>
<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<script type="text/javascript"  src="general/validaform.js"></script>
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
          <td height="63" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="8" height="16"></td>
                  <td width="952"></td>
                  <td width="34"></td>
                  <td width="62" rowspan="3" align="center" valign="middle"><a href="general/exportar.php"><img src="../images/exportexcel.png" alt="Exportar Resultados a Excel" width="32" height="32" border="0" /><br>
                  Exportar</a></td>
                  <td width="68" rowspan="3" align="center" valign="middle" ><button class="textonegro" name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/eliminar.png"  /><br>
                  Eliminar</button></td>
                  <td width="13"></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top">areaario: <?php echo $_SESSION["usuario"]?></td>
                  <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="28" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php
					if (isset($_POST['eliminar'])){		
						if(!empty($_POST['conweb'])) {
							function array_envia($codcon) { 
								$tmp = serialize($codcon); 
								$tmp = urlencode($tmp); 
								return $tmp; 
							} 
							$codcon=array_values($_POST['conweb']); 
							$codcon=array_envia($codcon); 
							?>
							<script type="text/javascript" language="javascript1.2">
							var entrar = confirm("¿Desea Eliminar los registros seleccionados?")
							if(entrar){
								location = "comliseli.php?codcon=<?php echo $codcon?>"	
							}
							</script>
							<?php
						}else{
							echo "Seleccione los comentarios que desea eliminar";
						}
					}
					if (isset($_POST['ver'])){
						$_SESSION["numreg"]=$_POST["selnumreg"];	
						echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";		
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
          <td width="1093">&nbsp;</td>
          <td width="6">&nbsp;</td>
        </tr>
        
        
        <tr>
          <td height="45">&nbsp;</td>
          <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="textonegro" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="608" height="52" valign="top" class="titulos"><img src="../images/contacto.png" width="48" height="48" align="absmiddle" /> Comentarios Web [Lista]  </td>
                <td width="575" align="right" valign="top"  <?php if ($_SESSION["grupo"] == 2){ ?>style="visibility:hidden" <?php } ?><?php if($filpar["are"] == 1){?> style=" visibility:hidden" <?php }?>>Filtrar Contactos por Area <br>
                  
                  <select name="selarea" id="selarea">
                    <option value="0">Elige</option>
                    <?
						$qryarea = "SELECT ac.codarea, acd.nomarea  FROM areacon ac, areacondet acd  WHERE ac.estado = 'Activa' AND ac.codarea = acd.codarea AND acd.codidi = 1 ";
						$resarea = mysql_query($qryarea, $enlace);
						while ($filarea = mysql_fetch_array($resarea))
							echo "<option value=\"".$filarea["codarea"]."\">".$filarea["nomarea"]."</option>\n";
							mysql_free_result($resarea);
					?>
                   </select>
                  </span>                  <input name="filtrar" type="submit" id="filtrar" value="Filtrar"/>                  </td>
                <td width="11">&nbsp;</td>
            </tr>
            <tr>
              <td height="9"></td>
              <td></td>
              <td></td>
            </tr>
            
            
              </table></td>
          </tr>
        <tr>
          <td height="106">&nbsp;</td>
          <td valign="top"><table width="93%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td height="8" colspan="2" valign="top" bgcolor="#000000"></td>
                  <td width="107" valign="top" bgcolor="#000000"></td>
                  <td width="190" valign="top" bgcolor="#000000"></td>
                  <td width="131" valign="top" bgcolor="#000000"></td>
                  <td width="159" valign="top" bgcolor="#000000"></td>
                  <td width="423" valign="top" bgcolor="#000000"></td>
            </tr>
            <tr>
              <td width="35" height="30" valign="middle" bgcolor="#FFFFFF" ><div align="center">item</div></td>
                  <td width="146" valign="middle" bgcolor="#FFFFFF" >Fecha Contacto </td>
                  <td align="center" valign="middle" bgcolor="#FFFFFF" >No. Horas  </td>
                  <td valign="middle" bgcolor="#FFFFFF" >Remitente</td>
                  <td valign="middle" bgcolor="#FFFFFF" ><div align="center"<?php if($filpar["are"] == 1){?> style=" visibility:hidden" <?php }?>>Area</div></td>
                  <td bgcolor="#FFFFFF" ><div align="center"<?php if($filpar["are"] == 1){?> style=" visibility:hidden" <?php }?>>Responsable</div></td>
                  <td valign="middle" bgcolor="#FFFFFF" ><p align="left">Comentario</p></td>
                </tr>
            
        
              <?php 
			  if($totalRows_registros > 0){
				$num=$startRow_registros;
				$numero = 0 ;
				do{
					if($numero == 1){
						$numero = 0;
						echo"<tr>" ;
						echo"<td></td>";
						echo"</tr>" ;
					}
				$codreg = $row_registros['codconweb'];
		   ?>
		   <tr onMouseOver="this.style.backgroundColor='#E1EBD8';" class="pointer" onMouseOut="this.style.backgroundColor='#F3F3F3'" title="ver contacto" >
              <td height="21" valign="top"><div align="center">
                <input type="checkbox" name="conweb[]" value="<?php echo $codreg; ?>" />
                </div></td>
                    <td valign="top" onClick="location='comres.php?cod=<?php echo  $codreg ; ?>' "><?php echo $row_registros['fecconweb']; ?></td>
                    <td align="center" valign="top" onClick="location='comres.php?cod=<?php echo  $codreg ; ?>' "><?php echo $row_registros['tietra']; ?></td>
                    <td valign="top" onClick="location='comres.php?cod=<?php echo  $codreg ; ?>' "><?php echo $row_registros['nomconweb']; ?></td>
                    <td valign="top" onClick="location='comres.php?cod=<?php echo  $codreg ; ?>' "><div align="center"<?php if($filpar["are"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $row_registros['nomarea']; ?></div></td>
                    <td valign="top" <?php if($filpar["are"] == 1){?> style=" visibility:hidden" <?php }?> onClick="location='comres.php?cod=<?php echo  $codreg ; ?>' "><?php echo $row_registros['nomusu']; ?></td>
                    <td valign="top" onClick="location='comres.php?cod=<?php echo  $codreg ; ?>' "><?php echo html_entity_decode( $row_registros['desconweb'] ); ?></td></tr>
                    <?php $numero++; } while ($row_registros = mysql_fetch_assoc($consulta)); }?>
           
            <tr>
              <td height="18"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="28" colspan="7" align="center" valign="top" bgcolor="#FFFFFF" class="textonegro"><?php 
						# variable declaration
						$prev_registros = "&laquo; Anterior";
						$next_registros = "Siguiente &raquo;";
						$separator = " | ";
						$max_links = 10;
						$pages_navigation_registros = paginador($pageNum_registros,$totalPages_registros,$prev_registros,$next_registros,$separator,$max_links,true); 
						print $pages_navigation_registros[0]; 
						?>
                <?php print $pages_navigation_registros[1]; ?> <?php print $pages_navigation_registros[2]; ?></td>
                </tr>
          </table></td>
          <td>&nbsp;</td>
          </tr>
        <tr>
          <td height="24">&nbsp;</td>
          <td valign="top" class="textonegro"><div align="center">Ver # 
            <select name="selnumreg" id="selnumreg" >
              <option value="10">10</option>
              <option value="15">15</option>
              <option value="20">20</option>
              <option value="25">25</option>
              <option value="30">30</option>
              </select>
            <input name="ver" type="submit" id="ver" value="ver" />
            Resultados <?php echo $totalRows_registros?></div></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="3"></td>
          <td></td>
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
<?php
mysql_free_result($consulta);
?>