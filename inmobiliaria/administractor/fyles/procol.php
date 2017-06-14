<?php
session_start();
include("general/paginador.php") ;
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

$enlace = enlace();

require 'general/permisos.php';
$prog = 'proedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

//capturo codigo de sucursal
$cod = $_GET["cod"];

$qrycolpro = "SELECT * FROM carcol WHERE codcarcol IN (SELECT codcarcol FROM procol WHERE codpro = '$cod' )";
$rescolpro = mysql_query($qrycolpro, $enlace);
$numcolpro = mysql_num_rows($rescolpro);

$currentPage = $_SERVER["PHP_SELF"];

$query_registros = "SELECT * FROM carcol WHERE codcarcol NOT IN (SELECT codcarcol FROM procol WHERE codpro = '$cod' )";

include("general/paginadorinferior.php") ;

?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
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
              <td width="7" height="16"></td>
                  <td width="874"></td>
                  <td width="39"></td>
                  <td width="59" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="asignar" type="submit" value="asignar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return validaenvia()"><img width="32" src="../images/aplicar.png"  /><br>
                  Asignar</button></td>
                  <td width="70" rowspan="3" align="center" valign="middle" ><button class="textonegro" name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/eliminar.png"  /><br>
                  Eliminar</button></td>
                  <td width="12"></td>
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
//boton guardar cambios
if (isset($_POST['asignar']))
{
if(!empty($_POST['color'])) {
	
	function array_envia($codcol) { 

    $tmp = serialize($codcol); 
    $tmp = urlencode($tmp); 

    return $tmp; 
	} 
	
	$codcol=array_values($_POST['color']); 
	$codcol=array_envia($codcol); 

?>
                <script type="text/javascript" language="javascript1.2">
			location = "procolasi.php?cod=<?php echo $cod?>&codcol=<?php echo $codcol?>"	
			</script>
                <?php
	
	}
	else
	{
	echo "Seleccione los colores que desea asignar al producto";
	}

}


if (isset($_POST['asignar1']))
{
$col = $_POST["selcol"];

$qryasicol = "INSERT INTO procol values ('$cod', '$col')";
$resasicol = mysql_query($qryasicol, $enlace);
echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	

}

if (isset($_POST['eliminar']))		
{		
				
	if(!empty($_POST['colorasignado'])) {
	
	function array_envia($codcol) { 

    $tmp = serialize($codcol); 
    $tmp = urlencode($tmp); 

    return $tmp; 
	} 
	
	$codcol=array_values($_POST['colorasignado']); 
	$codcol=array_envia($codcol); 

?>
                <script type="text/javascript" language="javascript1.2">
			location = "procoleli.php?cod=<?php echo $cod?>&codcol=<?php echo $codcol?>"	
			</script>
                <?php
	
	}
	else
	{
	echo "Seleccione los colores que desea eliminar";
	}
	
}

if (isset($_POST['ver']))		
{
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
          <td width="1376">&nbsp;</td>
          <td width="14">&nbsp;</td>
          </tr>
        <tr>
          <td height="61">&nbsp;</td>
          <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="textonegro" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="571" height="52" valign="top" class="titulos"><img src="../images/carcol.png" width="48" height="48" align="absmiddle" /> Carta de colores producto </td>
                <td width="292" valign="bottom" ><div align="right">Seleccionar Color 
                    <select name="selcol" id="selcol">
                      <?

	$qrycol= "SELECT * FROM carcol WHERE codcarcol NOT IN (SELECT codcarcol FROM procol WHERE codpro = '$cod')";
	$rescol = mysql_query($qrycol, $enlace);
	while ($filcol = mysql_fetch_assoc($rescol)){
	echo "<option value=\"".$filcol["codcarcol"]."\">".$filcol["nomcol"]."</option>\n";
	}
	mysql_free_result($rescol);
?>
                    </select>
                    <input name="asignar1" type="submit" id="asignar1" value="Asignar"/>
                </div></td>
            <td width="202" rowspan="2" valign="top"><div align="right">Volver a Producto <a href="proedi.php?cod=<?php echo $cod ?>&acc=1"><img src="../images/back.png" width="32" height="32" border="0" align="absmiddle" /></a></div></td>
            <td width="13">&nbsp;</td>
            </tr>
            <tr>
              <td height="9"></td>
              <td></td>
              <td></td>
            </tr>
            
            
              </table></td>
          </tr>
        <tr>
          <td height="59"></td>
          <td valign="top"><table width="93%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr><td width="33" height="20" valign="top"></td>
		          <td width="152">&nbsp;</td>
		          <td>&nbsp;</td>
		        </tr>
            <tr>
              
              <?php 
			  
			   if($totalRows_registros > 0){
		$num=$startRow_registros;
		$numero = 0 ;
		do{
			if($numero == 7){
				$numero = 0;
				echo"<tr>" ;
				echo"<td height='10'></td>";
				echo"</tr>" ;
			}
		$codreg = $row_registros['codcarcol'];
		   ?>
              <td width="33" height="20" valign="top"><div align="center">
                <input type="checkbox" name="color[]" value="<?php echo $codreg; ?>" />
                </div></td>
                    <td valign="top" class="textonegro"><?php echo $row_registros['nomcol']; ?></td>     
                    <td width="853" valign="top" bgcolor="#<?PHP echo $row_registros['hexacol'];?>" class="marcotabla"><!--DWLayoutEmptyCell-->&nbsp;</td>
                    <?php $numero++; } while ($row_registros = mysql_fetch_assoc($consulta)); }?>
              </tr>
            <tr>
              <td height="16"></td>
                  <td></td>
                  <td></td>
                </tr>
          </table></td>
          <td></td>
          </tr>
        <tr>
          <td height="37"></td>
          <td align="center" valign="top" bgcolor="#FFFFFF" class="textonegro"><?php 
						# variable declaration
						$prev_registros = "&laquo; Anterior";
						$next_registros = "Siguiente &raquo;";
						$separator = " | ";
						$max_links = 10;
						$pages_navigation_registros = paginador($pageNum_registros,$totalPages_registros,$prev_registros,$next_registros,$separator,$max_links,true); 
						print $pages_navigation_registros[0]; 
						?>
            <?php print $pages_navigation_registros[1]; ?><?php print $pages_navigation_registros[2]; ?></td>
          <td></td>
          </tr>
        <tr>
          <td height="24"></td>
          <td valign="top" class="textonegro"><div align="center">Ver #
              <select name="selnumreg" id="selnumreg" >
                <option value="1">1</option>
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
          <td height="46"></td>
          <td>&nbsp;</td>
          <td></td>
        </tr>
        <tr>
          <td height="52"></td>
          <td valign="top" class="titulos"><img src="../images/carcol.png" width="48" height="48" align="absmiddle" /> Colores asignados </td>
          <td></td>
        </tr>
        
        <tr>
          <td height="59"></td>
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla">
            <!--DWLayoutTable-->
			<tr><td width="33" height="20" valign="top"></td>
		          <td width="152">&nbsp;</td>
		          <td>&nbsp;</td>
		        </tr>
            <tr>
              
            <tr>
              <?php if($numcolpro > 0){$numero1 = 0 ;  while ($filcolpro = mysql_fetch_assoc($rescolpro)){
		   if($numero1 == 8 )
				{
				$numero1 = 0;
 				echo"<tr>" ;
				echo"<td height = 10></td>";
				echo"</tr>" ;
				}
		   ?>
              <td width="35" height="20" valign="top"><div align="center">
                <input type="checkbox" name="colorasignado[]" value="<?php echo $filcolpro['codcarcol']; ?>" />
                </div></td>
                    <td valign="top" class="textonegro"><?php echo $filcolpro['nomcol']; ?></td>
                               
                    <td width="1021" valign="top" bgcolor="#<?PHP echo $filcolpro['hexacol'];?>" class="marcotabla"><!--DWLayoutEmptyCell-->&nbsp;</td>
                    <?php $numero1++;  } }?>
              </tr>
            <tr>
              <td height="36"></td>
                  <td></td>
                  <td></td>
                </tr>
          </table></td>
          <td></td>
        </tr>
        <tr>
          <td height="15"></td>
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