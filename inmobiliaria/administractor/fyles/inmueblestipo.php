<?php 
session_start();
include("../../administractor/fyles/general/paginador.php") ;
include("../../administractor/fyles/general/conexion.php") ;
include("../../administractor/fyles/general/sesion.php");
sesion(1);

// fucion validar permisos de acceso a programa

$prog = 'inmueblestipo.php';
$usu = $_SESSION["usuario"];


$enlace = enlace();


$query_registros = "SELECT * FROM inmuebletipo ORDER BY nomtipinmueble";
include("../../administractor/fyles/general/paginadorinferior.php") ;

?>
<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../../administractor/js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<script type="text/javascript" language="JavaScript" src="../../administractor/fyles/general/validaform.js"></script>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
<link href="../../administractor/css/contenido.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
background-image:url(../../administractor/images/fondomacaw.jpg);
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
    <td width="300" height="49" valign="top" bgcolor="#000000"><img src="../../administractor/images/encabezado.png" width="300" height="49" /></td>
    <td width="100%" valign="bottom" bgcolor="#000000" class="textogris" style="background-image:url(../../administractor/images/fon_adm.png)"><div align="right"><a href="../../administractor/fyles/general/cerrar_sesion.php"><img src="../../administractor/images/cerses.png" alt="Cerrar Ses&oacute;n de Usuario" width="150" height="32" border="0" /></a></div></td>
  </tr>
  <tr>
    <td height="19" colspan="2" valign="top" bgcolor="#F5F5F5"><?php if ($_SESSION["grupo"] == 1){ ?><script type="text/javascript" language="JavaScript1.2" src="../../administractor/js/mnusuperadm.js"></script><?php }else{ ?><script type="text/javascript" language="JavaScript1.2" src="../../administractor/js/mnuadm.js"></script><?php } ?></td>
  </tr>
  <tr>
    <td height="313" colspan="2" valign="top"><!-- InstanceBeginEditable name="contenido" -->
	  <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="63" colspan="3" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="5" height="16"></td>
                  <td width="1235"></td>
                  <td width="22"></td>
                  <td width="54" rowspan="3" align="center" valign="middle" class="textonegro" ><button  name="nuevo" type="submit" value="nuevo" style="margin: 0px; background-color: transparent; border: none; " class="pointer"><img width="32" src="../../administractor/images/nuevo.png"  /><br>
                  Nuevo</button></td>
                  <td width="68" rowspan="3" align="center" valign="middle" ><button  name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none; " class="pointer"><img width="32" src="../../administractor/images/eliminar.png"  /><br>
                  Eliminar</button></td>
                <td width="10" ></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
              <td ></td>
            </tr>
            
            <tr>
              <td colspan="2" rowspan="2" valign="top" class="textoerror"><div align="right">
  <?php

//boton guardar cambios
if (isset($_POST['nuevo']))
{
echo '<script language = JavaScript>
location = "inmueblestipocre.php";
</script>';
}

if (isset($_POST['eliminar']))		
{		
				
	if(!empty($_POST['registros'])) {
	
	function array_envia($codreg) { 

    $tmp = serialize($codreg); 
    $tmp = urlencode($tmp); 

    return $tmp; 
	} 
	
	$codreg=array_values($_POST['registros']); 
	$codreg=array_envia($codreg); 

?>
                <script type="text/javascript" language="javascript1.2">
	var entrar = confirm("¿Desea Eliminar los registros seleccionados?")
	if ( entrar ) 
	{
	location = "inmueblestipoeli.php?codreg=<?php echo $codreg?>"	
	}
	</script>
  <?php
	}
	else
	{
	echo "Seleccione el tipo de estudio que desea eliminar";
	}
	
}
if (isset($_POST['ver']))		
{
$_SESSION["numreg"]=$_POST["selnumreg"];	
echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";		
}

?>
              </div></td>
                  <td height="24">&nbsp;</td>
                  <td >&nbsp;</td>
            </tr>
            <tr>
              <td height="6"></td>
              <td></td>
              <td></td>
              <td></td>
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
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="588" height="36" valign="top" class="titulos"><img src="../../administractor/images/tipinmueble.png" width="32" height="32" align="absmiddle">  Tipos de Inmuebles [ Lista ] </td>
                <td width="589" valign="top" class="titulos"><!--DWLayoutEmptyCell-->&nbsp;</td>
            </tr>
            <tr>
              <td height="9" colspan="2"></td>
            </tr>
              </table></td>
          </tr>
        <tr>
          <td height="106">&nbsp;</td>
          <td valign="top"><table width="93%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td height="8" valign="top" bgcolor="#000000"></td>
                  <td width="159" valign="top" bgcolor="#000000"></td>
                  <td width="157" valign="top" bgcolor="#000000"></td>
                  <td width="791" valign="top" bgcolor="#000000"></td>
                </tr>
            <tr>
              <td width="37" height="30" valign="middle" bgcolor="#FFFFFF" ><div align="center">item</div></td>
                  <td colspan="3" valign="middle" bgcolor="#FFFFFF" >Nombre del Tipo Inmueble </td>
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
		$codreg = $row_registros['codtipinmueble'];
		   ?><tr onMouseOver="this.style.backgroundColor='#E1EBD8';" class="pointer" onMouseOut="this.style.backgroundColor='#F3F3F3'" title="Editar Carrera">
              <td height="21" valign="top"><div align="center">
                <input type="checkbox" name="registros[]" value="<?php echo $codreg; ?>" />
                </div></td>
                    <td colspan="3" valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nomtipinmueble']; ?></td></tr>
					<?php $numero++; } while ($row_registros = mysql_fetch_assoc($consulta)); }?>
              
            <tr>
              <td height="18"></td>
                <td colspan="3"></td>
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
    <td height="32" colspan="2" valign="top"><div align="center" class="textonegro">  <strong>ADMIN-WEB</strong> , </div></td>
  </tr>
</table>
</form>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($consulta);
?>