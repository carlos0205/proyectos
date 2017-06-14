<?php 
session_start();
include("general/paginador.php") ;
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'not.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

$currentPage = $_SERVER["PHP_SELF"];

if (isset($_POST['filtrar']))
{
	$query_registros = "SELECT pc.*, tt.nomtipusuter, tp.nomtippub, u.nomusu, i.nomidi FROM pubcon AS pc
	INNER JOIN tipusuter AS tt ON pc.codtipusuter = tt.codtipusuter
	INNER JOIN tippub AS tp ON pc.codtippub = tp.codtippub
	INNER JOIN idipub AS i ON pc.codidi = i.codidi 
	LEFT JOIN usuadm AS u ON pc.codusuadm = u.codusuadm WHERE pc.codpub > 0 ";
	
	if($_POST["seltip"] <> 0){
		$query_registros .= " AND pc.codtippub = ".$_POST["seltip"]." ";
	}
	if($_POST["cbo2codidisi"]<>0){
		$query_registros .= " AND pc.codidi = ".$_POST["cbo2codidisi"]." ";
	}
	$query_registros .= " ORDER BY pc.codpub DESC ";
	
	$_SESSION['qryfiltropub'] =$query_registros;
}

if (isset($_SESSION['qryfiltropub'])){ 
$query_registros= $_SESSION['qryfiltropub'];
}
else{
$query_registros= "SELECT tiempo FROM sesionest WHERE tiempo = -1";
}

destruyesesiones("qryfiltropub");
include("general/paginadorinferior.php") ;

//fecha actual para comparar con fecha fin de publicacion
$fecact = date("Y-m-d");
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
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
        <!--DWLayoutTable-->
        <tr>
          <td height="63" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="11" height="16"></td>
                  <td width="1183"></td>
                  <td width="95"></td>
                  <td width="54" rowspan="3" align="center" valign="middle" class="textonegro" ><button class="textonegro" name="nuevo" type="submit" value="nuevo" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/nuevo.png"  /><br>
                  Nuevo</button></td>
	              <td width="68" rowspan="3" align="center" valign="middle" ><button class="textonegro" name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/eliminar.png"  /><br>
                  Eliminar</button></td>
                  <td width="14" ></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top"><span >Usuario: <?php echo $_SESSION["logueado"]?></span></td>
                  <td></td>
                  <td >&nbsp;</td>
            </tr>
            
            <tr>
              <td colspan="2" rowspan="2" valign="top" class="textoerror"><div align="right">
                <?php
				//boton guardar cambios
				if (isset($_POST['nuevo'])){
				echo '<script language = JavaScript>
				location = "notcre.php";
				</script>';
				}
				
				if (isset($_POST['eliminar'])){		
								
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
					if (entrar){
						location = "noteli.php?codreg=<?php echo $codreg?>"	
					}
					</script>
				<?php
					}
					else
					{
					echo "Seleccione el registro que desea eliminar";
					}
				}
				if (isset($_POST['ver'])){
				$_SESSION["numreg"]=$_POST["selnumreg"];	
				echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";		
				}
				?>
              </div></td>
                  <td height="25"></td>
                  <td >&nbsp;</td>
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
          <td width="1216">&nbsp;</td>
          <td width="7">&nbsp;</td>
        </tr>
        <tr>
          <td height="45">&nbsp;</td>
          <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="textonegro" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="379" height="52" valign="top" class="titulos"><img src="../images/noticias.png" width="48" height="48" align="absmiddle" />Publicaciones [ Lista ] </td>
                <td width="503" align="right" valign="middle" class="textonegro">Filtrar por tipo de publicaci&oacute;n <br>
                  <span >
                  <select name="seltip" id="seltip">
                    <option value="0">Elige</option>
                    <?
					$qrytip = "SELECT * FROM tippub ORDER BY nomtippub ";
					$restip = mysql_query($qrytip, $enlace);
					while ($filtip = mysql_fetch_array($restip))
					echo "<option value=\"".$filtip["codtippub"]."\">".$filtip["nomtippub"]."</option>\n";
					mysql_free_result($restip);
				?>
                    </select>
                  </span></td>
            <td width="287" rowspan="2" align="right" valign="middle">Idioma<br>
              <select name="cbo2codidisi" id="cbo2codidisi" title="Idioma">
                <option value="0">Elige</option>
                <?
					if (isset($_POST['selidi'])){
						$idi=$_POST['selidi'];
						$qryidi = "SELECT * FROM idipub WHERE codidi <> '$idi' ORDER BY nomidi ";
						$qryidi1 = "SELECT * FROM idipub WHERE codidi = '$idi' ";
						$residi1 = mysql_query($qryidi1,$enlace);
						$filidi1 = mysql_fetch_array($residi1);
						echo "<option selected value=\"".$filidi1['codidi']."\">".$filidi1['nomidi']."</option>\n";
						mysql_free_result($residi1);
					}
					else
					{
						$qryidi = "SELECT * FROM idipub ORDER BY nomidi ";
					}
					$residi = mysql_query($qryidi, $enlace);
					while ($filidi = mysql_fetch_array($residi))
					echo "<option value=\"".$filidi["codidi"]."\">".$filidi["nomidi"]."</option>\n";
					mysql_free_result($residi);
				?>
              </select></td>
                <td width="66" align="right" valign="middle">                  <input name="filtrar" type="submit" id="filtrar" value="Filtrar" />            </td>
            <td width="14">&nbsp;</td>
            </tr>
            <tr>
              <td height="2"></td>
              <td></td>
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
                  <td width="91" valign="top" bgcolor="#000000"></td>
                  <td width="99" valign="top" bgcolor="#000000"></td>
                  <td width="145" valign="top" bgcolor="#000000"></td>
                  <td width="140" valign="top" bgcolor="#000000"></td>
                  <td width="132" valign="top" bgcolor="#000000"></td>
                  <td width="169" valign="top" bgcolor="#000000"></td>
                  <td width="49" valign="top" bgcolor="#000000"></td>
                  <td width="88" valign="top" bgcolor="#000000"></td>
                  <td width="128" valign="top" bgcolor="#000000"></td>
                </tr>
            <tr>
              <td width="38" height="30" valign="middle" bgcolor="#FFFFFF" ><div align="center">item</div></td>
                  <td width="170" valign="middle" bgcolor="#FFFFFF" >T&iacute;tulo</td>
                  <td valign="middle" bgcolor="#FFFFFF" ><div align="center">Publicado</div></td>
                  <td valign="middle" bgcolor="#FFFFFF" ><div align="center">Publica en Inicio </div></td>
                  <td valign="middle" bgcolor="#FFFFFF" >Acceso</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Inicio</td>
                  <td valign="middle" bgcolor="#FFFFFF" >fin</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Secci&oacute;n</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Idioma</td>
                  <td align="center" valign="middle" bgcolor="#FFFFFF" >Hits</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Creado por </td>
                </tr>
            
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
		$codreg = $row_registros['codpub'];
	?>
	<tr onMouseOver="this.style.backgroundColor='#E1EBD8';" class="pointer" onMouseOut="this.style.backgroundColor='#F3F3F3'" title="Editar publicación">
              <td height="21" valign="top"><div align="center">
                <input type="checkbox" name="registros[]" value="<?php echo $codreg; ?>" />
                </div></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nompub']; ?></td>
                    <td valign="top"><div align="center"><?php if ($row_registros['pub'] == "Si"){ $ico="publish_g.png" ; ?> <a href="notedi.php?cod=<?php echo  $row_registros['codpub']."&amp;pub=No&amp;acc=0&amp;pubini=".$row_registros['pubini']; ?>"><img src="../images/<?php echo $ico; ?>" alt="Cambiar estado" width="16" height="16" border="0" /></a> <?php }else{ $ico ="publish_x.png";?><a href="notedi.php?cod=<?php echo  $row_registros['codpub']."&amp;pub=Si&amp;acc=0&amp;pubini=".$row_registros['pubini']; ?>"><img src="../images/<?php echo $ico; ?>" alt="Cambiar estado" width="16" height="16" border="0" /></a><?php } ?>
                    </div></td>
                    <td valign="top"><div align="center"><?php if ($row_registros['pubini'] == "Si"){ $ico="publish_g.png" ; ?> <a href="notedi.php?cod=<?php echo  $row_registros['codpub']."&amp;pub=".$row_registros['pub']."&amp;acc=0&amp;pubini=No"; ?>"><img src="../images/<?php echo $ico; ?>" alt="Cambiar estado" width="16" height="16" border="0" /></a> <?php }else{ $ico ="publish_x.png";?><a href="notedi.php?cod=<?php echo  $row_registros['codpub']."&amp;pub=".$row_registros['pub']."&amp;acc=0&amp;pubini=Si"; ?>"><img src="../images/<?php echo $ico; ?>" alt="Cambiar estado" width="16" height="16" border="0" /></a><?php } ?>
                    </div></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nomtipusuter']; ?></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['fecinipub']; ?></td>
                    <td valign="top"  <?php if ($row_registros['fecfinpub'] <= $fecact){?>bgcolor="#FF3333" <?php }?> onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['fecfinpub']; ?></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nomtippub']; ?></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nomidi']; ?></td>
                    <td align="center" valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['hits']; ?></td>
                    <td valign="top"onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nomusu']; ?></td></tr>
                    <?php $numero++; } while ($row_registros = mysql_fetch_assoc($consulta)); } ?>
           
            <tr>
              <td height="18"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                </tr>
            <tr>
              <td height="28" colspan="11" align="center" valign="top" bgcolor="#FFFFFF" class="textonegro"><?php 
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
              <option value="1">1</option>
              <option value="10">10</option>
              <option value="15">15</option>
              <option value="20">20</option>
              <option value="25">25</option>
              <option value="30">30</option>
              </select>
            <input name="ver" type="submit" id="ver" value="ver" />
            Resultados <span ><?php echo $totalRows_registros?></span></div></td>
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
<?php
mysql_free_result($consulta);
?>