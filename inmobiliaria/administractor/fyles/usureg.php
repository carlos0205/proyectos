<?php 
session_start();
include("general/paginador.php") ;
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'usureg.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();


$currentPage = $_SERVER["PHP_SELF"];

//verifico clic en boton filtrar
if (isset($_POST['filtrar'])){
	$cliente=$_POST["cbocodter"];
	$hojavida=$_POST["cbocodhoj"];
	$estado = $_POST["cboestado"];
	$tipo=$_POST["hidtipo"];
	
	
	$query_registros = "SELECT * FROM (SELECT tc.nomter, c.nomciu, tu.nomtipusuter, tu.codtipusuter, utc.codusucli, utc.codter, utc.feccre, utc.estusu, utc.logusu, utc.ultvis, '1' tipo 
FROM tercli tc, terclidir tcd, ciudad c, tipusuter tu, usutercli utc 
WHERE tc.coddir = tcd.coddir AND tcd.codciu = c.codciu AND tc.codtipusuter = tu.codtipusuter AND tc.codter = utc.codter 
UNION 
SELECT tc.nomter, c.nomciu, tu.nomtipusuter,tu.codtipusuter,  utc.codusucli, utc.codter, utc.feccre, utc.estusu, utc.logusu, utc.ultvis, '2' tipo FROM terclihojvda tc, ciudad c, tipusuter tu,usutercli utc 
WHERE tc.codciu = c.codciu AND tc.codtipusuter = tu.codtipusuter AND tc.codhojvda = utc.codter 
ORDER BY nomter )AS usuarios
WHERE codusucli > 0 ";


	if($estado <> "0"){
		$query_registros .= " AND estusu = '$estado'";
	}
	
	if($cliente <> 0 || $hojavida <> 0){
		$query_registros .= " AND ( codter = '$cliente' OR codter = '$hojavida') AND codtipusuter = $tipo";
	}
	
	$query_registros .= " ORDER BY nomter";
$_SESSION['qryfiltrousureg']=$query_registros;

}

if (isset($_SESSION['qryfiltrousureg'])){ 
$query_registros = $_SESSION['qryfiltrousureg'];
}
else{
$query_registros = "SELECT tiempo FROM sesionest WHERE tiempo = -1";
}

$query_registros;

include("general/paginadorinferior.php") ;

//consulto parametros del producto
$qrypar= "SELECT * FROM clipar";
$respar = mysql_query($qrypar, $enlace);
$filpar = mysql_fetch_assoc($respar);

?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
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
          <td height="63" colspan="3" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="8" height="16"></td>
                  <td width="1036"></td>
                  <td width="22"></td>
                  <td width="54" rowspan="3" align="center" valign="middle" class="textonegro" ><button class="textonegro" name="nuevo" type="submit" value="nuevo" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/nuevo.png"  /><br>
                  Nuevo</button></td>
                  <td width="68" rowspan="3" align="center" valign="middle" ><button class="textonegro" name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/eliminar.png"  /><br>
                  Eliminar</button></td>
                  <td width="10"></td>
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
				if (isset($_POST['nuevo'])){
					echo '<script language = JavaScript>
					location = "usuregcre.php";
					</script>';
				}
				if (isset($_POST['eliminar'])){		
					if(!empty($_POST['usuario'])) {
						function array_envia($codreg) { 
							$tmp = serialize($codreg); 
							$tmp = urlencode($tmp); 
							return $tmp; 
						} 	
						$codreg=array_values($_POST['usuario']); 
						$codreg=array_envia($codreg); 
						?>
						<script type="text/javascript" language="javascript1.2">
						var entrar = confirm("¿Desea Eliminar los registros seleccionados?")
						if ( entrar ){
							location = "usuregeli.php?codreg=<?php echo $codreg?>"	
						}
						</script>
					 	<?php
					}else{
						echo "Seleccione los registros que desea eliminar";
					}
				}
				?>
              </div></td>
                  <td height="24">&nbsp;</td>
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
          <td width="1171">&nbsp;</td>
          <td width="6">&nbsp;</td>
        </tr>
        
        
        <tr>
          <td height="45">&nbsp;</td>
          <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="textonegro" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="298" rowspan="2" valign="top" class="titulos"><img src="../images/usuarioregistrado.png" width="48" height="48" align="absmiddle" /> Usuarios registrados [ Lista ]  </td>
                <td width="737" height="21" align="right" valign="top" >Filtar por: </td>
            <td width="15" >&nbsp;</td>
            </tr>
            <tr>
              <td height="31" align="right" valign="top">                Cliente
                <select name="cbocodter" id="cbocodter" onChange="document.form1.cbocodhoj.value=0; document.form1.hidtipo.value='2'">
                  <option value="0">Elige</option>
                  <?
	$qryter= "SELECT tc.codter, tc.nomter, tc.codtipusuter FROM tercli tc, usutercli utc WHERE utc.codter = tc.codter
	ORDER BY nomter
	 ";
	$rester = mysql_query($qryter, $enlace);
	while ($filter = mysql_fetch_array($rester))
	echo "<option value=\"".$filter["codter"]."\">".$filter["nomter"]."</option>\n";
	mysql_free_result($rester);
?>
                </select> 
                Usuario Hoja de Vida
                <select name="cbocodhoj" id="cbocodhoj" onChange="document.form1.cbocodter.value=0 ;document.form1.hidtipo.value='4'">
                  <option value="0">Elige</option>
 <?
	$qryter= "SELECT tc.codhojvda, tc.nomter, tc.codtipusuter FROM terclihojvda tc, usutercli utc WHERE utc.codter = tc.codhojvda 
	ORDER BY nomter
	 ";
	$rester = mysql_query($qryter, $enlace);
	while ($filter = mysql_fetch_array($rester))
	echo "<option value=\"".$filter["codter"]."\">".$filter["nomter"]."</option>\n";
	mysql_free_result($rester);
?>
                </select>
                <br>
                <br>
                Estado 
                  <label>
                  <select name="cboestado" id="cboestado">
                    <option value="0">Elige</option>
                    <option value="Activo">Activo</option>
                    <option value="Bloqueado">Bloqueado</option>
                  </select>
                  <input name="hidtipo" type="hidden" id="hidtipo">
                  <br>
                  <br>
                  </label>
                  <input name="filtrar" type="submit" id="filtrar" value="Filtrar"/>              </td>
            <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="9"></td>
              <td></td>
              <td></td>
            </tr>
            
            
              </table></td>
          </tr>
        <tr>
          <td height="108">&nbsp;</td>
          <td valign="top"><table width="93%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td height="8" colspan="2" valign="top" bgcolor="#000000"></td>
                  <td width="101" valign="top" bgcolor="#000000"></td>
                  <td width="291" valign="top" bgcolor="#000000"></td>
                  <td width="111" valign="top" bgcolor="#000000"></td>
                  <td width="153" valign="top" bgcolor="#000000"></td>
                  <td width="144" valign="top" bgcolor="#000000"></td>
                  <td width="161" valign="top" bgcolor="#000000"></td>
                </tr>
            <tr>
              <td width="17" height="30" valign="middle" bgcolor="#FFFFFF" ><div align="center">Item</div></td>
                  <td width="212" valign="middle" bgcolor="#FFFFFF" >Nombre Usuario</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Usuario</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Ciudad</td>
                  <td align="center" valign="middle" bgcolor="#FFFFFF" >Estado</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Tipo Usuario</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Creado</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Ultima Visita</td>
                </tr>
     
              <?php  if($totalRows_registros > 0){
		$num=$startRow_registros;
		$numero = 0 ;
		do{
			if($numero == 1){
				$numero = 0;
				echo"<tr>" ;
				echo"<td></td>";
				echo"</tr>" ;
			}
		$codreg = $row_registros['codter'];
		   ?><tr onMouseOver="this.style.backgroundColor='#E1EBD8';" class="pointer" onMouseOut="this.style.backgroundColor='#F3F3F3'" title="Editar Usuario">                <td height="21" valign="top"><div align="center">
                  <input name="usuario[]" type="checkbox" id="usuario[]" value="<?php echo $row_registros['codusucli']; ?>" />
                </div></td>
                    <td valign="top"  onClick="window.location='usuregedi.php?cod=<?php echo  $codreg ; ?>&acc=1&tipo=<?php echo $row_registros['tipo'] ?>' "><?php echo $row_registros['nomter']; ?></td>
                  <td valign="top"  onClick="window.location='usuregedi.php?cod=<?php echo  $codreg ; ?>&acc=1&tipo=<?php echo $row_registros['tipo'] ?>' "><?php echo $row_registros['logusu']; ?></td>
                    <td valign="top"  onClick="window.location='usuregedi.php?cod=<?php echo  $codreg ; ?>&acc=1&tipo=<?php echo $row_registros['tipo'] ?>' "><?php echo $row_registros['nomciu']; ?></td>
                    <td align="center" valign="top"  onClick="window.location='usuregedi.php?cod=<?php echo  $codreg ; ?>&acc=1&tipo=<?php echo $row_registros['tipo'] ?>' "><?php if ($row_registros['estusu'] == "Activo"){ $ico="publish_g.png" ; ?> <a href="usuregedi.php?cod=<?php echo  $row_registros['codusucli']."&hab=Bloqueado&acc=0"; ?>"><img src="../images/<?php echo $ico; ?>" alt="Cambiar estado" width="16" height="16" border="0" /></a> <?php }else{ $ico ="publish_x.png";?><a href="usuregedi.php?cod=<?php echo  $row_registros['codusucli']."&hab=Activo&acc=0"; ?>"><img src="../images/<?php echo $ico; ?>" alt="Cambiar estado" width="16" height="16" border="0" /></a><?php } ?>                    </td>
                    <td valign="top"  onClick="window.location='usuregedi.php?cod=<?php echo  $codreg ; ?>&acc=1&tipo=<?php echo $row_registros['tipo'] ?>' "><?php echo $row_registros['nomtipusuter']; ?></td>
                    <td valign="top" onClick="window.location='usuregedi.php?cod=<?php echo  $codreg ; ?>&acc=1&tipo=<?php echo $row_registros['tipo'] ?>' " ><?php echo $row_registros['feccre']; ?></td>
                    <td valign="top"  onClick="window.location='usuregedi.php?cod=<?php echo  $codreg ; ?>&acc=1&tipo=<?php echo $row_registros['tipo'] ?>' "><?php echo $row_registros['ultvis']; ?></td></tr>
                    <?php $numero++; } while ($row_registros = mysql_fetch_assoc($consulta)); }?>
   
            <tr>
              <td height="19">&nbsp;</td>
              <td>&nbsp;</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              </tr>
            <tr>
              <td height="28" colspan="8" align="center" valign="top" bgcolor="#FFFFFF" class="textonegro"><?php 
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
          <td height="6"></td>
          <td></td>
          <td></td>
          </tr>
        <tr>
          <td height="23"></td>
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
          <td></td>
        </tr>
        <tr>
          <td height="12"></td>
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