<?php 
session_start();
include("general/paginador.php") ;
include("general/conexion.php") ;

//XAJAX

//incluímos la ciudades ajax 
require ('xajax/xajax_core/xajax.inc.php');
//instanciamos el objeto de la ciudades xajax 
$xajax = new xajax();

$xajax->configure('javascript URI', 'xajax/');

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'ciu.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();


function departamentos($pais){
	global $enlace;
	$respuesta = new xajaxResponse();
	
	$qrylis ="SELECT d.coddep, d.nomdep FROM deppro AS d 
WHERE d.ci= $pais ";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1coddepno' id='cbo1coddepno'  class='textonegro' onChange='xajax_ciudades(this.value)' title='departamentos'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["coddep"]."'>".$fillis["nomdep"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("departamentos","innerHTML",$lista); 
	
	return $respuesta;
}
function ciudades($dep){
	global $enlace;
	$respuesta = new xajaxResponse();
	
	$qrylis ="SELECT c.codciu, c.nomciu FROM ciudad AS c
WHERE c.coddep = $dep ";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1codciuno' id='cbo1codciuno'  class='textonegro'  title='ciudades'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codciu"]."'>".$fillis["nomciu"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("ciudades","innerHTML",$lista); 
	
	return $respuesta;
}

//verifico clic en boton filtrar
if (isset($_POST['filtrar'])){
	$pais=$_POST["cbo1cino"];
	$dep=$_POST["cbo1coddepno"];
	$ciu=$_POST["cbo1codciuno"];
	
	$query_registros = "SELECT c.*, dp.nomdep, p.cn FROM ciudad c, deppro dp, pais p WHERE c.coddep = dp.coddep AND dp.ci = p.ci ";

	if($pais <> 0 ){
		$query_registros .= "AND p.ci='$pais' ";
	}
	if($dep <> 0 ){
		$query_registros .= "AND dp.coddep='$dep' ";
	}
	if($ciu <> 0){
		$query_registros .= "AND c.codciu='$ciu' ";
	}
	
	$query_registros .= " ORDER BY p.cn, dp.nomdep, c.nomciu ASC";
	
	$_SESSION['qryfiltrociudades'] = $query_registros;
}

if (isset($_SESSION['qryfiltrociudades'])){ 
	$query_registros = $_SESSION['qryfiltrociudades'];
}
else{
	$query_registros = "SELECT tiempo FROM sesionest WHERE tiempo = -1";
}


$_SESSION["consulta"] = $query_registros;

include("general/paginadorinferior.php") ;


$xajax->registerFunction("departamentos");
$xajax->registerFunction("ciudades");

//El objeto xajax tiene que procesar cualquier petición 
$xajax->processRequest(); 

include("general/sesion.php");
sesion(1);
destruyesesiones("qryfiltrociudades");

?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<?php
//En el <head> indicamos al objeto xajax se encargue de generar el javascript necesario 
$xajax->printJavascript(); 
?>
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
              <td width="9" height="16"></td>
                  <td width="819"></td>
                  <td width="17"></td>
                  <td width="55"  rowspan="3" align="center" valign="middle" class="textonegro"><a href="general/exportar.php"><img src="../images/exportexcel.png" alt="Exportar Resultados a Excel" width="32" height="32" border="0" /></a><br>
Exportar</td>
                  <td width="57" rowspan="3" align="center" valign="middle" class="textonegro" ><button class="textonegro" name="nuevo" type="submit" value="nuevo" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/nuevo.png"  /><br>
                  Nuevo</button></td>
                  <td width="73" rowspan="3" align="center" valign="middle" ><button class="textonegro" name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/eliminar.png"  /><br>
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
              <td height="28" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php
				//boton guardar cambios
				if (isset($_POST['nuevo'])){
					echo '<script language = JavaScript>
					location = "ciucre.php";
					</script>';
				}
				if (isset($_POST['eliminar'])){		
					if(!empty($_POST['ciudad'])) {
						function array_envia($codreg) { 
						$tmp = serialize($codreg); 
						$tmp = urlencode($tmp); 
						return $tmp; 
					} 
					$codreg=array_values($_POST['ciudad']); 
					$codreg=array_envia($codreg); 
					?>
					<script type="text/javascript" language="javascript1.2">
					var entrar = confirm("¿Desea eliminar los registros seleccionados?")
					if(entrar ){
						location = "ciueli.php?codreg=<?php echo $codreg?>"	
					}
					</script>
					<?php
				}else{
					echo "Seleccione la ciudad que desea eliminar";
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
              <td width="225" rowspan="2" valign="top"><img src="../images/div.png" width="48" height="48" align="absmiddle" /><span class="titulos">Ciudades [Lista] </span></td>
                <td width="262" height="23" valign="top">Filtrar Por Pais:<br></td>
                <td width="219" valign="top">Departamento</td>
                <td width="270" valign="top">Ciudad</td>
            <td width="13">&nbsp;</td>
            </tr>
            <tr>
              <td height="29" valign="top"><select name="cbo1cino" class="textonegro" id="cbo1cino" title="L&iacute;nea" onChange="xajax_departamentos(this.value)">
                <option value="0">Elige</option>
                <?
					
					$qrypais= "SELECT p.ci, p.cn FROM pais AS p 
					ORDER BY p.cn ";
					$respais = mysql_query($qrypais, $enlace);
					while ($filpais = mysql_fetch_array($respais))
					echo "<option value=\"".$filpais["ci"]."\">".$filpais["cn"]."</option>\n";
					mysql_free_result($respais);
				?>
              </select></td>
              <td valign="top" id="departamentos"><select name="cbo1coddepno" class="textonegro" id="cbo1coddepno" title="ciudades" >
                <option value="0">Elige</option>
              </select></td>
              <td valign="top" id="ciudades"><select name="cbo1codciuno" class="textonegro" id="cbo1codciuno" title="ciudades" >
                <option value="0">Elige</option>
              </select></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="30"></td>
              <td></td>
              <td></td>
              <td align="right" valign="top"><input name="filtrar" type="submit" id="filtrar" value="Filtrar"/></td>
              <td></td>
            </tr>
              </table></td>
          </tr>
        <tr>
          <td height="109">&nbsp;</td>
          <td valign="top"><table width="93%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td height="8" colspan="2" valign="top" bgcolor="#000000"></td>
                  <td width="334" valign="top" bgcolor="#000000"></td>
                  <td width="473" valign="top" bgcolor="#000000"></td>
              </tr>
            <tr>
              <td width="36" height="30" valign="middle" bgcolor="#FFFFFF" ><div align="center">item</div></td>
                  <td width="247" valign="middle" bgcolor="#FFFFFF" >Ciudad</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Departamento</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Pa&iacute;s</td>
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
				$codreg = $row_registros['codciu'];
		   ?>
		   <tr onMouseOver="this.style.backgroundColor='#E1EBD8';" class="pointer" onMouseOut="this.style.backgroundColor='#F3F3F3'" title="Editar Ciudad">
              <td height="21" valign="top"><div align="center">
                <input type="checkbox" name="ciudad[]" value="<?php echo $codreg; ?>" />
                </div></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nomciu']; ?></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nomdep']; ?></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['cn']; ?></td>
                    <?php $numero++; } while ($row_registros = mysql_fetch_assoc($consulta)); }?>
              </tr>
            <tr>
              <td height="21">&nbsp;</td>
                <td>&nbsp;</td>
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
            Resultados <?php echo $totalRows_registros?></div></td>
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