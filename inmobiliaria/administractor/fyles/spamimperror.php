<?php 
session_start();
include("general/paginador.php") ;
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'spamimp.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);
$enlace = enlace();

$currentPage = $_SERVER["PHP_SELF"];

$query_registros = "SELECT * FROM errorimpema  ORDER BY fila ";

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
              <td width="8" height="16"></td>
                  <td width="1079"></td>
                  <td width="35"></td>
                  <td width="74" rowspan="3" align="center" valign="middle"><a href="general/exportar.php"><img src="../images/exportexcel.png" alt="Exportar Resultados a Excel" width="32" height="32" border="0" /><br>
                  Exportar</a></td>
                  <td width="7"></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td>&nbsp;</td>
              <td></td>
            </tr>
            
            
            
            
            
            <tr>
              <td height="28" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php

if (isset($_POST['eliminar']))		
{		
				
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
			if ( entrar ) 
			{
			location = "comliseli.php?codcon=<?php echo $codcon?>"	
			}
			</script>
                <?php
	
	}
	else
	{
	echo "Seleccione los comentarios que desea eliminar";
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
          <td width="1093">&nbsp;</td>
          <td width="6">&nbsp;</td>
        </tr>
        
        
        <tr>
          <td height="45">&nbsp;</td>
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="800" height="52" valign="top" class="titulos"><img src="../images/generic.png" width="48" height="48" align="absmiddle" /> Erroes de importaci&oacute;n (<span class="textoerror">Fecha ultima importacion: 
                
              </span> <span class="textoerror">
              <?php 
			  $qryfec = "SELECT distinct(feccre) FROM errorimpema";
			  $resfec = mysql_query($qryfec, $enlace);
			  $filfec = mysql_fetch_assoc($resfec);
			  echo $filfec["feccre"];
			  ?>) </td>
                <td width="293" align="right" valign="middle" class="textonegro"><a href="spamimp.php">Volver<img src="../images/back.png" width="32" height="32" border="0" align="absmiddle" /></a></td>
            <td width="12">&nbsp;</td>
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
              <td width="22" height="8" valign="top" bgcolor="#000000"></td>
                  <td width="432" valign="top" bgcolor="#000000"></td>
                  <td width="219" valign="top" bgcolor="#000000"></td>
                  <td width="20" valign="top" bgcolor="#000000"></td>
                  <td width="197" valign="top" bgcolor="#000000"></td>
                  <td width="19" valign="top" bgcolor="#000000"></td>
                  <td width="184" valign="top" bgcolor="#000000"></td>
                  <td width="66" valign="top" bgcolor="#000000"></td>
                </tr>
            <tr>
              <td height="30" valign="top" bgcolor="#FFFFFF"><!--DWLayoutEmptyCell-->&nbsp;</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Persona</td>
                  <td align="center" valign="middle" bgcolor="#FFFFFF" >e-mail invalido </td>
                <td valign="top" bgcolor="#FFFFFF"><!--DWLayoutEmptyCell-->&nbsp;</td>
                  <td align="center" valign="middle" bgcolor="#FFFFFF" >e-mail ya existe </td>
                <td valign="top" bgcolor="#FFFFFF"><!--DWLayoutEmptyCell-->&nbsp;</td>
                  <td align="center" valign="middle" bgcolor="#FFFFFF" >Tipo Cliente No Existe </td>
            <td align="center" valign="middle" bgcolor="#FFFFFF" >fila</td>
            </tr>
            
            <tr>
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
		
		   ?>
              <td height="21" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                    <td valign="top" ><?php echo $row_registros['nomspam']; ?></td>
                    <td align="center" valign="top"   <?php if ($row_registros["emainv"]<> ""){?>bgcolor="#CC6699"<?php }?>><?php echo $row_registros['emainv']; ?></td>
                    <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                    <td align="center" valign="top"  <?php if ($row_registros["emaexi"]<> ""){?> bgcolor="#CC6699"<?php }?>><?php echo $row_registros['emaexi']; ?></td>
                    <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                    <td align="center" valign="top"   <?php if ($row_registros["tipclinoexi"]<> ""){?>bgcolor="#CC6699"<?php }?>><?php echo $row_registros['tipclinoexi']; ?></td>
                    <td align="center" valign="top" ><?php echo $row_registros['fila']; ?></td>
                    <?php $numero++; } while ($row_registros = mysql_fetch_assoc($consulta)); }?>
              </tr>
            <tr>
              <td height="18"></td>
                <td></td>
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
                <?php print $pages_navigation_registros[1]; ?><?php print $pages_navigation_registros[2]; ?></td>
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
              <option value="50">50</option>
              <option value="100">100</option>
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