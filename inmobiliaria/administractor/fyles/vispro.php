<?php 
session_start();
header("Cache-control: private");// IE 6 Fix. 
include("general/paginador.php") ;
include("general/conexion.php") ;

$enlace=enlace();


//capturo codigo de evento
$cod = $_GET["codcol"];
$codcli = $_GET["codcli"];

$currentPage = $_SERVER["PHP_SELF"];
$query_registros = "SELECT p.codpro, pd.nompro, v.hits FROM colpro AS cp 
INNER JOIN  prodet pd ON cp.codpro = pd.codpro AND pd.codidi =1 
INNER JOIN colvis v ON cp.codpro = v.codpro
WHERE cd.codcol = $cod  AND v.codter=$codcli";

include("general/paginadorinferior.php") ;
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<script language="JavaScript" type="text/javascript" src="../js/pestanas.js"></script>
<link rel="stylesheet" type="text/css" href="../css/pestanas.css" />
<link href="../css/contenido.css" rel="stylesheet" type="text/css">
<title>Admin-Web</title><body>
<form enctype="multipart/form-data" method="post" name="form1">
<table width="601" border="0" cellpadding="0" cellspacing="0" class="textonegro">
  <!--DWLayoutTable-->
  <tr>
    <td height="56" colspan="2" align="center" valign="middle" >CONSULTA VISITAS A PRODUCTOS DE COLECCI&Oacute;N </td>
    <td width="147" align="right" valign="top"><br>
      <img src="../images/logocli.jpg" width="200"><br>
      <br></td>
  </tr>
  
  <tr>
    <td width="5" height="21">&nbsp;</td>
    <td width="449">&nbsp;</td>
    <td></td>
    </tr>
  <tr>
    <td height="91">&nbsp;</td>
    <td colspan="2" valign="top"><table width="93%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="454" height="8" valign="top" bgcolor="#000000"></td>
                  <td width="140" valign="top" bgcolor="#000000"></td>
            </tr>
            <tr>
              <td height="30" valign="middle" bgcolor="#FFFFFF" >Producto</td>
                  <td align="center" valign="middle" bgcolor="#FFFFFF" >Hits</td>
            </tr>
            <tr>
              <?php if($totalRows_registros > 0){
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
              <td height="21" valign="top" ><?php echo $row_registros['nompro']; ?></td>
                    <td align="center" valign="middle" ><?php echo $row_registros['hits']; ?></td>
                    <?php $numero++; } while ($row_registros = mysql_fetch_assoc($consulta)); }?>
          </tr>
            <tr>
            <td height="2"></td>
            <td></td>
            </tr>
            <tr>
              <td height="28" colspan="2" align="center" valign="top" bgcolor="#FFFFFF" class="textonegro"><?php 
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
    </tr>
  <tr>
    <td height="16"></td>
    <td colspan="2" valign="top" class="textonegro"><div align="center">Ver # 
            <select name="selnumreg" id="selnumreg" >
              <option value="10">10</option>
              <option value="15">15</option>
              <option value="20">20</option>
              <option value="25">25</option>
              <option value="30">30</option>
          </select>
            <input name="ver" type="submit" id="ver" value="ver" />
      Resultados <?php echo $totalRows_registros?></div></td>
    </tr>
  <tr>
    <td height="12"></td>
    <td></td>
    <td></td>
  </tr>
</table>
</form>
</body>
</html>
