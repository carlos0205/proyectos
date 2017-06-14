<?php 
session_start();
include("general/paginador.php") ;
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'spam.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);


$enlace = enlace();

if (isset($_POST['filtrar'])){
	$tip=$_POST["seltip"];

	if($tip <> 0){
		$query_registros = "SELECT sp.*,sp.nomspam,sp.emaspam, td.nomtipter FROM spam sp, tipter t, tipterdet td WHERE sp.codtipter = t.codtipter AND sp.codtipter = '$tip' AND t.codtipter = td.codtipter AND td.codidi = 1 ";
	}else{
		$query_registros = "SELECT sp.*, td.nomtipter FROM spam sp, tipter t, tipterdet td WHERE sp.codtipter = t.codtipter AND t.codtipter = td.codtipter AND td.codidi = 1 ";
	}

$_SESSION['qryfiltrospam']=$query_registros;
}
if (isset($_SESSION['qryfiltrospam'])){ 
	$query_registros = $_SESSION['qryfiltrospam'];
}else{
	$query_registros = "SELECT tiempo FROM sesionest WHERE tiempo = -1";
}

$_SESSION["consulta"] = $query_registros;

include("general/paginadorinferior.php") ;

?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>

<script type="text/javascript">
function crearregistro(){
var pasa = validaenvia()

	if(pasa == false ){
		return false;
	}else{
		
			
		var entrar = confirm("¿Desea crear el registro?")
		if ( entrar ) 
		{
			return true;	
		}else{
			return false;
		}
	
	}
}
</script>
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
              <td width="9" height="16"></td>
                  <td width="880"></td>
                  <td width="30"></td>
                  <td width="63" rowspan="3" align="center" valign="middle"><a href="general/exportar.php"><img src="../images/exportexcel.png" alt="Exportar Resultados a Excel" width="32" height="32" border="0" /><br>
                  Exportar</a></td>
                  <td width="54" rowspan="3" align="center" valign="middle" class="textonegro" ><button class="textonegro" name="nuevo" type="submit" value="nuevo" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/nuevo.png"  /><br>
                  Nuevo</button></td>
                  <td width="68" rowspan="3" align="center" valign="middle" ><button class="textonegro" name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/eliminar.png"  /><br>
                  Eliminar</button></td>
                  <td width="17"></td>
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
location = "spamcre.php";
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
			var entrar = confirm("¿Desea eliminar los registros seleccionados?")
			if ( entrar ) 
			{
			location = "spameli.php?codreg=<?php echo $codreg?>"	
			}
			</script>
                <?php
	
	}
	else
	{
	echo "Seleccione el correo que desea eliminar";
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
          <td width="1103">&nbsp;</td>
          <td width="6">&nbsp;</td>
        </tr>  
        <tr>
          <td height="45">&nbsp;</td>
          <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="textonegro" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="606" rowspan="2" valign="top" class="titulos"><img src="../images/pqrs.png" width="48" height="48" align="absmiddle" />Cuentas de correo masivo [ Lista ] </td>
                <td width="435" height="27" align="right" valign="top" >Listar por: Tipo  </td>
                <td width="63" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                <td width="10">&nbsp;</td>
            </tr>
            
            <tr>
              <td height="25" align="right" valign="top">                <select name="seltip" id="seltip">
                  <option value="0">Elige</option>
                  <?
		$qrytip= "SELECT * FROM tipterdet WHERE codidi = 1 ORDER BY nomtipter ";
	$restip = mysql_query($qrytip, $enlace);
	while ($filtip = mysql_fetch_array($restip))
	echo "<option value=\"".$filtip["codtipter"]."\">".$filtip["nomtipter"]."</option>\n";
	mysql_free_result($restip);
?>
                </select>              </td>
              <td valign="top"><div align="right">
                <input name="filtrar" type="submit" id="filtrar" value="Filtrar"/>
              </div></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="9"></td>
              <td></td>
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
                  <td width="352" valign="top" bgcolor="#000000"></td>
                  <td width="285" valign="top" bgcolor="#000000"></td>
                </tr>
            <tr>
              <td width="38" height="30" valign="middle" bgcolor="#FFFFFF" ><div align="center">item</div></td>
                  <td width="441" valign="middle" bgcolor="#FFFFFF" >Nombre</td>
                  <td valign="middle" bgcolor="#FFFFFF" >e-mail</td>
                  <td valign="middle" bgcolor="#FFFFFF" ><div align="center">Perfil</div></td>
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
		$codreg = $row_registros['codspam'];
		   ?><tr onMouseOver="this.style.backgroundColor='#E1EBD8';" class="pointer" onMouseOut="this.style.backgroundColor='#F3F3F3'" title="Editar cuenta">
              <td height="21" valign="top"><div align="center">
                <input type="checkbox" name="registros[]" value="<?php echo $codreg; ?>" />
                </div></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>')"><?php echo $row_registros['nomspam']; ?></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>')"><?php echo $row_registros['emaspam']; ?></td>
                    <td align="center" valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>')"><?php echo $row_registros['nomtipter']; ?></td></tr>
                    <?php $numero++; } while ($row_registros = mysql_fetch_assoc($consulta)); }?>
              
            
            <tr>
              <td height="20">&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
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
                <?php print $pages_navigation_registros[1]; ?><?php print $pages_navigation_registros[2]; ?></td>
                </tr>
            
            
            
            
            
            
          </table></td>
          <td>&nbsp;</td>
          </tr>
        <tr>
          <td height="24"></td>
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