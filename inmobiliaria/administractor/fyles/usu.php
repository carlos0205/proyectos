<?php 
session_start();
include("general/paginador.php") ;
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'usu.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

$currentPage = $_SERVER["PHP_SELF"];

if (isset($_POST['filtrar'])){

	
	$grupo = $_POST["cbo2codgrusi"];
	$estado = $_POST["cboestado"];
		
	$qrery_registros = "SELECT u.*, g.nomgru FROM usuadm AS u 
	INNER JOIN gruusu AS g ON u.codgru = g.codgru 
	WHERE codusuadm > 0  ";

	if($grupo <> 0){
		$qrery_registros .= " AND u.codgru = '$grupo' ";
	}
	if ($estado <> "0"){
		$qrery_registros .= " AND u.estusu = '$estado' ";
	}
	
	
	if ($_SESSION["grupo"] <> 1){
		$qrery_registros .= " AND u.codgru <> 1 ";
	}
	
	$qrery_registros .=  " ORDER BY g.nomgru, u.nomusu";
		
$_SESSION['qryfiltrousuarios']=$qrery_registros;

}
if (isset($_SESSION['qryfiltrousuarios'])){ 
$query_registros = $_SESSION['qryfiltrousuarios'];
}
else{
$query_registros = "SELECT tiempo FROM sesionest WHERE tiempo = -1";
}
destruyesesiones("qryfiltrousuarios");

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
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
        <!--DWLayoutTable-->
        <tr>
          <td height="63" colspan="3" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="9" height="16"></td>
                  <td width="894"></td>
                  <td width="35"></td>
                  <td width="55" rowspan="3" align="center" valign="middle" class="textonegro" ><button class="textonegro" name="nuevo" type="submit" value="nuevo" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/nuevo.png"  /><br>
                  Nuevo</button></td>
                  <td width="68" rowspan="3" align="center" valign="middle" ><button class="textonegro" name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/eliminar.png"  /><br>
                  Eliminar</button></td>
                  <td width="7"></td>
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
if (isset($_POST['nuevo']))
{
echo '<script language = JavaScript>
location = "usucre.php";
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
			location = "usueli.php?codreg=<?php echo $codreg?>"	
			}
			</script>
                <?php
	
	}
	else
	{
	echo "Seleccione el usuario que desea eliminar";
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
          <td width="1042">&nbsp;</td>
          <td width="6">&nbsp;</td>
        </tr>
        
        
        <tr>
          <td height="45">&nbsp;</td>
          <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="textonegro" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="313" rowspan="2" valign="top" class="titulos"><img src="../images/usuarios.png" width="48" height="48" align="absmiddle" />Usuarios del sitema [ Lista ]  </td>
                <td width="354" height="22" valign="top">Grupo 
                  <select name="cbo2codgrusi" id="cbo2codgrusi" title="Grupo de usuario">
                      <option value="0">Elige</option>
                      <?
	if (isset($_POST['selgru'])){
		$gru=$_POST['selgru'];
		$qrygru = "SELECT * FROM gruusu WHERE codgru <> 1 AND codgru <> '$gru'  ORDER BY nomgru";
		$qrygru1 = "SELECT * FROM gruusu WHERE codgru = '$gru' ";
		$resgru1 = mysql_query($qrygru1,$enlace);
		$filgru1 = mysql_fetch_array($resgru1);
		echo "<option selected value=\"".$filgru1['codgru']."\">".$filgru1['nomgru']."</option>\n";
		mysql_free_result($resgru1);
	}
	else
	{
		$qrygru = "SELECT * FROM gruusu WHERE codgru <> 1 ORDER BY nomgru ";
	}
	$resgru = mysql_query($qrygru, $enlace);
	while ($filgru = mysql_fetch_array($resgru))
	echo "<option value=\"".$filgru["codgru"]."\">".$filgru["nomgru"]."</option>\n";
	mysql_free_result($resgru);
?>
                    </select></td>
                <td width="380" valign="top">Estado 
                  <label>
                  <select name="cboestado" id="cboestado">
                    <option value="0">Elige</option>
                    <option value="Activo">Activo</option>
                    <option value="Bloqueado">Bloqueado</option>
                  </select>
                  </label></td>
                <td width="62" rowspan="2" align="right" valign="top"><input name="filtrar" type="submit" id="filtrar" value="Filtrar"/></td>
                <td width="13">&nbsp;</td>
            </tr>
            <tr>
              <td height="29">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
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
          <td height="107">&nbsp;</td>
          <td valign="top"><table width="93%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td height="8" colspan="2" valign="top" bgcolor="#000000"></td>
                  <td width="95" valign="top" bgcolor="#000000"></td>
                  <td width="252" valign="top" bgcolor="#000000"></td>
                  <td width="96" valign="top" bgcolor="#000000"></td>
                  <td width="133" valign="top" bgcolor="#000000"></td>
                  <td width="125" valign="top" bgcolor="#000000"></td>
                  <td width="141" valign="top" bgcolor="#000000"></td>
                </tr>
            <tr>
              <td width="36" height="30" valign="middle" bgcolor="#FFFFFF" ><div align="center">item</div></td>
                  <td width="162" valign="middle" bgcolor="#FFFFFF" >Nombre Usuario</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Usuario</td>
                  <td valign="middle" bgcolor="#FFFFFF" >e-mail</td>
                  <td align="center" valign="middle" bgcolor="#FFFFFF" >Estado</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Grupo</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Creado</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Ultima Visita</td>
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
		$codreg = $row_registros['codusuadm'];
		   ?><tr onMouseOver="this.style.backgroundColor='#E1EBD8';" class="pointer" onMouseOut="this.style.backgroundColor='#F3F3F3'" title="Editar Usuario">
              <td height="21" valign="top"><div align="center">
                <input type="checkbox" name="registros[]" value="<?php echo $codreg; ?>" />
                </div></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)" ><?php echo $row_registros['nomusu']; ?></td>
                  <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['logusu']; ?></td>
                    <td valign="top"onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)" ><?php echo $row_registros['emausu']; ?></td>
                    <td align="center" valign="top"><?php if ($row_registros['estusu'] == 'Activo'){ $ico="publish_g.png" ; ?> <a href="usuedi.php?cod=<?php echo  $row_registros['codusuadm']."&amp;hab=Bloqueado&amp;acc=0"; ?>"><img src="../images/<?php echo $ico; ?>" alt="Cambiar estado" width="16" height="16" border="0" /></a> <?php }else{ $ico ="publish_x.png";?><a href="usuedi.php?cod=<?php echo  $row_registros['codusuadm']."&amp;hab=Activo&amp;acc=0"; ?>"><img src="../images/<?php echo $ico; ?>" alt="Cambiar estado" width="16" height="16" border="0" /></a><?php } ?>                    </td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nomgru']; ?></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['feccre']; ?></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['ultvis']; ?></td></tr>
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