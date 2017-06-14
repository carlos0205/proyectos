<?php 
session_start();
include("general/paginador.php") ;
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'intpag.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();


if (isset($_POST['filtrar'])){
	
	
	$query_registros = "SELECT pim.*,d.nomdiasemana, p.nompag, i.nomidi FROM pagsiteimgdiaria AS pim
	INNER JOIN pagsite AS p ON pim.codpag = p.codpag 
	INNER JOIN idipub AS i ON pim.codidi = i.codidi 
	INNER JOIN diasemana AS d ON pim.coddiasemana = d.coddiasemana WHERE pim.codpagimg > 0 ";
	
	$pagina=$_POST["selpag"];
	$dia = $_POST["seldia"];
	
	if($pagina <> 0){
		$query_registros .= " AND pim.codpag = '$pagina'";
	}
	
	if($dia <> 0){
		$query_registros .= " AND pim.coddiasemana = '$dia'";
	}
	
	if($_SESSION["grupo"]> 2){
		$query_registros .= " AND p.codempresa = ".$_SESSION["empresa"]."";
	}
	
$_SESSION['qryfiltroimgdiaria']=$query_registros;
}

if (isset($_SESSION['qryfiltroimgdiaria'])){ 
$query_registros = $_SESSION['qryfiltroimgdiaria'];
}
else{
$query_registros = "SELECT tiempo FROM sesionest WHERE tiempo = -1";
}

include("general/paginadorinferior.php") ;

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
                  <td width="833"></td>
                  <td width="99"></td>
                  <td width="71" rowspan="3" align="center" valign="middle"><button class="textonegro" name="nuevo" type="submit" value="nuevo" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/nuevo.png"  /><br>
                  Nuevo</button></td>
                  <td width="67" rowspan="3" align="center" valign="middle"><button class="textonegro" name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/eliminar.png"  /><br>
                  Eliminar</button></td>
                  <td width="9"></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
                  <td></td>
            </tr>
            
            
            
            

            <tr>
              <td height="28" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php
			if (isset($_POST['nuevo'])){
				echo '<script language = JavaScript>
				location = "pagsiteimgdiariacre.php";
				</script>';
				}
				if (isset($_POST['eliminar'])){		
					if(!empty($_POST['registros'])){
						function array_envia($codreg) { 
							$tmp = serialize($codreg); 
							$tmp = urlencode($tmp); 
							return $tmp; 
						} 
						$codreg=array_values($_POST['registros']); 
						$codreg=array_envia($codreg); 
						?>
						<script type="text/javascript" language="javascript1.2">
						var entrar = confirm("¿Desea Eliminar las listas de precio seleccionadas?")
						if (entrar){
							location = "pagsiteimgdiariaeli.php?codreg=<?php echo $codreg?>"	
						}
						</script>
						<?php
					}else{
						echo "Seleccione la lista de precio que desea eliminar";
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
          <td width="1092">&nbsp;</td>
          <td width="6">&nbsp;</td>
        </tr>
        
        
        <tr>
          <td height="45">&nbsp;</td>
          <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="textonegro" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="342" height="52" valign="top" class="titulos"><img src="../images/imgsecdiaria.png" width="48" height="48" align="absmiddle" />Imagen diaria de secci&oacute;n [ Lista ]  </td>
                <td width="301" valign="middle" >Filtrar por secci&oacute;n<br>
                   <select name="selpag" id="selpag">
                     <option value="0">Elige</option>
                     <?
						$qrypag= "SELECT p.* FROM pagsite p ORDER BY p.nompag ";
						$respag = mysql_query($qrypag, $enlace);
						while ($filpag = mysql_fetch_array($respag))
						echo "<option value=\"".$filpag["codpag"]."\">".$filpag["nompag"]."</option>\n";
						mysql_free_result($respag);
					?>
                  </select></td>
                <td width="232" valign="middle" >D&iacute;a de la semana <br>
                  <label>
                  <select name="seldia" id="seldia">
                    <option value="0">Elige</option>
                    <option value="1">Lunes</option>
                    <option value="2">Martes</option>
                    <option value="3">Miercoles</option>
                    <option value="4">Jueves</option>
                    <option value="5">Viernes</option>
                    <option value="6">S&aacute;bado</option>
                    <option value="7">Domingo</option>
                  </select>
                  </label></td>
                <td width="111" align="right" valign="bottom" ><input name="filtrar" type="submit" id="filtrar" value="Filtrar"/></td>
                <td width="12" >&nbsp;</td>
            </tr>
            <tr>
              <td height="9"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
              </table></td>
          </tr>
        <tr>
          <td height="102">&nbsp;</td>
          <td valign="top"><table width="93%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="41" height="8"></td>
                  <td width="524"></td>
                  <td width="390"></td>
                  <td width="115"></td>
            </tr>
            <tr>
              <td height="30" align="center" valign="middle" bgcolor="#FFFFFF">Item</td>
                <td valign="middle" bgcolor="#FFFFFF">Secci&oacute;n</td>
                <td valign="middle" bgcolor="#FFFFFF">D&iacute;a de la semana </td>
            <td valign="middle" bgcolor="#FFFFFF">Idioma</td>
            </tr>
           
              <?php if($totalRows_registros > 0){$num=$startRow_registros; $numero = 0 ;  do {
		   if($numero == 1 )
				{
				$numero = 0;
 				echo"<tr>" ;
				echo"<td></td>";
				echo"</tr>" ;
				}
				
				$codreg = $row_registros['codpagimg'];
				
		   ?>
		    <tr onMouseOver="this.style.backgroundColor='#E1EBD8';" class="pointer" onMouseOut="this.style.backgroundColor='#F3F3F3'" title="Editar imagen de sección">
              <td height="21" align="center" valign="top"><input type="checkbox" name="registros[]" value="<?php echo $codreg; ?>" /></td>
                  <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nompag']; ?></td>
                  <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nomdiasemana']; ?></td>
                  <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nomidi']; ?></td>
			    </tr>
                  <?php $numero++; } while ($row_registros = mysql_fetch_assoc($consulta)); }?>
             
            <tr>
              <td height="14"></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="28" colspan="4" align="center" valign="top" bgcolor="#FFFFFF" class="textonegro"><?php 
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
            Resultados <span class="texnegronegrita"><?php echo $totalRows_registros?></span></div></td>
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
<?php
mysql_free_result($consulta);
?>