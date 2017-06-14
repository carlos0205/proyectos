<?php 
session_start();
include("general/paginador.php") ;
include("general/conexion.php") ;

//XAJAX

//incluímos la clase ajax 
require ('xajax/xajax_core/xajax.inc.php');
//instanciamos el objeto de la clase xajax 
$xajax = new xajax();

$xajax->configure('javascript URI', 'xajax/');

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'pro.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//consulto parametros del producto
$qrypar= "SELECT * FROM propar";
$respar = mysql_query($qrypar, $enlace);
$filpar = mysql_fetch_assoc($respar);



function subgrupo($lin){
	global $enlace;
	$respuesta = new xajaxResponse();
	
	$qrylis ="SELECT sd.codsubgru, sd.nomsubgru FROM subgru AS s 
INNER JOIN subgrudet AS sd ON s.codsubgru = sd.codsubgru AND sd.codidi =1
WHERE s.codlin = $lin ";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1codsubgrusi' id='cbo1codsubgrusi'  class='textonegro' onChange='xajax_clase(this.value)' title='Subgrupo'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codsubgru"]."'>".$fillis["nomsubgru"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("subgrupo","innerHTML",$lista); 
	
	return $respuesta;
}
function clase($subgru){
	global $enlace;
	$respuesta = new xajaxResponse();
	
	$qrylis ="SELECT cd.codcla, cd.nomcla FROM cla AS c 
INNER JOIN cladet AS cd ON c.codcla = cd.codcla AND cd.codidi =1
WHERE c.codsubgru = $subgru ";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1codclasi' id='cbo1codclasi'  class='textonegro' onChange='xajax_subclase(this.value)' title='Clase'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codcla"]."'>".$fillis["nomcla"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("clase","innerHTML",$lista); 
	
	return $respuesta;
}
function subclase($clase){
	global $enlace;
	$respuesta = new xajaxResponse();
	
	$qrylis ="SELECT sd.codsubcla, sd.nomsubcla FROM subcla AS s 
INNER JOIN subcladet AS sd ON s.codsubcla = sd.codsubcla AND sd.codidi =1
WHERE s.codcla = $clase ";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1codsubclasi' id='cbo1codsubclasi'  class='textonegro' title='Sub-Clase' >/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codsubcla"]."'>".$fillis["nomsubcla"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("subclase","innerHTML",$lista); 
	
	return $respuesta;
}


if (isset($_POST['filtrar'])){

	$linea = $_POST["cbo2codlinsi"];
	$subgrupo = $_POST["cbo1codsubgrusi"];
	$clase =$_POST["cbo1codclasi"];
	$subclase = $_POST["cbo1codsubclasi"];
	$tipo = $_POST["seltip"];
	$codreg = $_POST["selref"];
	$producto = $_POST["cbo1codprono"];
		

	$qrery_registros = "SELECT p.*, pr.refpro, pd.nompro, pd.despro, ld.nomlin, sgd.nomsubgru, cld.nomcla, scd.nomsubcla, tut.nomtipusuter, f.nomfab, tp.nomtippro, u.nomusu
FROM pro p
INNER JOIN prodet pd ON pd.codpro = p.codpro  AND pd.codidi=1 
INNER JOIN linneg l ON p.codlin = l.codlin 
INNER JOIN linnegdet ld ON l.codlin = ld.codlin AND ld.codidi = 1
LEFT JOIN subgru sg ON p.codsubgru = sg.codsubgru 
LEFT JOIN subgrudet sgd ON sg.codsubgru = sgd.codsubgru AND sgd.codidi = 1
LEFT JOIN cla cl ON p.codcla = cl.codcla
LEFT JOIN cladet cld ON cl.codcla = cld.codcla AND cld.codidi = 1
LEFT JOIN subcla sc ON p.codsubcla = sc.codsubcla
LEFT JOIN subcladet scd ON sc.codsubcla = scd.codsubcla AND scd.codidi = 1
INNER JOIN tipusuter tut ON p.codtipusuter = tut.codtipusuter
INNER JOIN tblproductosreferencias pr ON p.codpro = pr.codpro AND pr.tipo = 'Principal'
LEFT JOIN fab f ON p.codfab = f.codfab
LEFT JOIN tippro tp ON p.codtippro = tp.codtippro 
LEFT JOIN usuadm AS u ON p.codusuadm = u.codusuadm
WHERE p.codpro > 0 ";

	if($codreg <> 0){
		$qrery_registros .= "AND p.codpro = '$codreg' ";
	}
	if ($linea <> 0){
		$qrery_registros .= "AND p.codlin = '$linea' ";
	}
	if($subgrupo <> 0){
		$qrery_registros .= "AND p.codsubgru = '$subgrupo' ";
	}
	if ($tipo <> 0){
		$qrery_registros .= " AND p.codtippro = '$tipo' ";
	}
	if($subclase <> 0){
		$qrery_registros .= "AND p.codsubcla = '$subclase' ";
	}
	if($clase <> 0){
		$qrery_registros .= "AND p.codcla = '$clase' ";
	}
	if($producto <> 0){
		$qrery_registros .= "AND p.codpro = '$producto' ";
	}
	$qrery_registros .=  " ORDER BY pd.nompro";
		
$_SESSION['qryfiltropro']=$qrery_registros;

}
if (isset($_SESSION['qryfiltropro'])){ 
$query_registros = $_SESSION['qryfiltropro'];
}
else{
$query_registros = "SELECT tiempo FROM sesionest WHERE tiempo = -1";
}


include("general/paginadorinferior.php") ;

$xajax->registerFunction("subgrupo");
$xajax->registerFunction("clase");
$xajax->registerFunction("subclase");

//El objeto xajax tiene que procesar cualquier petición 
$xajax->processRequest(); 

include("general/sesion.php");
sesion(1);
destruyesesiones("qryfiltropro");
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
                  <td width="1117"></td>
                  <td width="38"></td>
                  <td width="58" rowspan="3" align="center" valign="middle"><button class="textonegro" name="nuevo" type="submit" value="nuevo" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/nuevo.png"  /><br>
                  Nuevo</button></td>
                  <td width="58" rowspan="4" align="center" valign="middle"><button class="textonegro" name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/eliminar.png"  /><br>
                  Eliminar</button></td>
                  <td width="15"></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
                  <td></td>
            </tr>
            
            <tr>
              <td colspan="2" rowspan="3" valign="top" class="textoerror"><div align="right">
                <?php
				//boton guardar cambios
				if (isset($_POST['nuevo'])){
					echo '<script language = JavaScript>
					location = "procre.php";
					</script>';
				}
				if (isset($_POST['eliminar'])){		
					if(!empty($_POST['registro'])) {
						function array_envia($codreg) { 
						$tmp = serialize($codreg); 
						$tmp = urlencode($tmp); 
						return $tmp; 
						} 
						$codreg=array_values($_POST['registro']); 
						$codreg=array_envia($codreg); 
						?>
							<script type="text/javascript" language="javascript1.2">
							var entrar = confirm("¿Desea Eliminar los registros seleccionados?")
							if(entrar){
								location = "proeli.php?codreg=<?php echo $codreg?>"	
							}
							</script>
						<?php
					}else{
						echo "Seleccione los registros que desea eliminar";
					}
				}
				
				if (isset($_POST['ver']))		
				{
				$_SESSION["numreg"]=$_POST["selnumreg"];	
				echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";		
				}
				?>
              </div></td>
                  <td height="23"></td>
                  <td></td>
            </tr>
            <tr>
              <td height="1"></td>
              <td></td>
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
          <td width="1363">&nbsp;</td>
          <td width="6">&nbsp;</td>
        </tr>
        <tr>
          <td height="45">&nbsp;</td>
          <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="textonegro" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="210" rowspan="4" valign="top" class="titulos"><img src="../images/carrito.png" width="48" height="48" align="absmiddle" />Productos (Lista)  </td>
                <td width="275" height="13" valign="top" >Filtrar por L&iacute;nea<br></td>
                <td width="332" valign="top">Clase</td>
                <td width="171" rowspan="2" valign="top">Referencia Espec&iacute;fica <br>
                  <select name="selref" class="textonegro" id="selref">
                    <option value="0">Elige</option>
                    <?
						$qryref= "SELECT codpro, refpro FROM tblproductosreferencias WHERE tipo = 'Principal' ORDER BY refpro ";
						$resref = mysql_query($qryref, $enlace);
						while ($filref = mysql_fetch_array($resref))
						echo "<option value=\"".$filref["codpro"]."\">".$filref["refpro"]."</option>\n";
						mysql_free_result($resref);
					?>
                  </select></td>
                <td width="169" rowspan="2" valign="top"  <?php if($filpar["tp"] == 1){?>style=" visibility:hidden" <?php }?>>Tipo Producto<strong><br>
                      <select name="seltip" class="textonegro" id="seltip">
                        <option value="0">Elige</option>
                        <?
						$qrytip= "SELECT * FROM tippro ORDER BY nomtippro ";
						$restip = mysql_query($qrytip, $enlace);
						while ($filtip = mysql_fetch_array($restip))
						echo "<option value=\"".$filtip["codtippro"]."\">".$filtip["nomtippro"]."</option>\n";
						mysql_free_result($restip);
					?>
                    </select>               </td>
                <td width="121" rowspan="4" align="right" valign="bottom"><input name="filtrar" type="submit" id="filtrar" value="Filtrar"/></td>
                <td width="21"></td>
            </tr>
            <tr>
              <td height="24" valign="top"><select name="cbo2codlinsi" class="textonegro" id="cbo2codlinsi" title="L&iacute;nea" onChange="xajax_subgrupo(this.value)">
                <option value="0">Elige</option>
                <?
					
					$qrylin= "SELECT l.codlin, ld.nomlin FROM linneg AS l 
					INNER JOIN linnegdet AS ld ON l.codlin = ld.codlin AND ld.codidi = 1 ";
					$reslin = mysql_query($qrylin, $enlace);
					while ($fillin = mysql_fetch_array($reslin))
					echo "<option value=\"".$fillin["codlin"]."\">".$fillin["nomlin"]."</option>\n";
					mysql_free_result($restlin);
				?>
              </select></td>
              <td valign="top" id="clase"><select name="cbo1codclasi" class="textonegro" id="cbo1codclasi" title="Clase" >
                <option value="0">Elige</option>
              </select></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="13" valign="top">Subgrupo</td>
              <td valign="top">Sub-Clase</td>
              <td colspan="2" valign="top">Producto</td>
                <td></td>
            </tr>
            <tr>
              <td height="30" valign="top" id="subgrupo"><select name="cbo1codsubgrusi" class="textonegro" id="cbo1codsubgrusi" title="Subgrupo" >
                <option value="0">Elige</option>
              </select></td>
              <td valign="top" id="subclase"><select name="cbo1codsubclasi" class="textonegro" id="cbo1codsubclasi" title="Sub-Clase" >
                <option value="0">Elige</option>
              </select></td>
              <td colspan="2" valign="top"><select name="cbo1codprono" class="textonegro" id="cbo1codprono">
                <option value="0">Elige</option>
                <?
						$qryref= "SELECT  codpro, nompro FROM prodet WHERE codidi =1 ORDER BY prodet.nompro";
						$resref = mysql_query($qryref, $enlace);
						while ($filref = mysql_fetch_array($resref))
						echo "<option value=\"".$filref["codpro"]."\">".$filref["nompro"]."</option>\n";
						mysql_free_result($resref);
					?>
              </select></td>
              <td></td>
            </tr>
            <tr>
              <td height="9"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
              </table></td>
          </tr>
        <tr>
          <td height="88">&nbsp;</td>
          <td valign="top"><table width="93%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td height="8" colspan="2" valign="top" bgcolor="#000000"></td>
                  <td width="145" valign="top" bgcolor="#000000"></td>
                  <td width="159" valign="top" bgcolor="#000000"></td>
                  <td width="139" valign="top" bgcolor="#000000"></td>
                  <td width="105" valign="top" bgcolor="#000000"></td>
                  <td width="88" valign="top" bgcolor="#000000"></td>
                  <td width="148" valign="top" bgcolor="#000000"></td>
                  <td width="129" valign="top" bgcolor="#000000"></td>
                  <td width="170" valign="top" bgcolor="#000000"></td>
                </tr>
            <tr>
              <td width="42" height="30" valign="middle" bgcolor="#FFFFFF"><div align="center">item</div></td>
                  <td width="192" valign="middle" bgcolor="#FFFFFF">Nombre</td>
                  <td valign="middle" bgcolor="#FFFFFF"><div align="center">Referencia</div></td>
                  <td valign="middle" bgcolor="#FFFFFF"><div align="center"  <?php if($filpar["codniv"] == 5){?> style=" visibility:hidden" <?php }?>>L&iacute;nea </div></td>
                  <td valign="middle" bgcolor="#FFFFFF"><div align="center" <?php if($filpar["codniv"] == 5 || $filpar["codniv"] < 2){?> style=" visibility:hidden" <?php }?>>Subgrupo</div></td>
                  
                  <td align="center" valign="middle" bgcolor="#FFFFFF">Publicar</td>
                  <td valign="middle" bgcolor="#FFFFFF"><div align="center">Acceso</div></td>
                  <td valign="middle" bgcolor="#FFFFFF"><div align="center" <?php if($filpar["tp"] == 1){?> style=" visibility:hidden" <?php }?>>Tipo Producto   </div></td>
                  <td valign="middle" bgcolor="#FFFFFF"><div align="center">Hits</div></td>
                  <td valign="middle" bgcolor="#FFFFFF"><p align="center">Creado por </p></td>
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
				$codreg = $row_registros['codpro'];
		   ?>
		   <tr onMouseOver="this.style.backgroundColor='#E1EBD8';" class="pointer" onMouseOut="this.style.backgroundColor='#F3F3F3'" title="Editar producto">
              <td height="21" valign="top"><div align="center">
                <input type="checkbox" name="registro[]" value="<?php echo $row_registros['codpro']; ?>" />
                </div></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"> <?php echo $row_registros['nompro']; ?> </td>
                  <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><div align="center"><?php echo $row_registros['refpro']; ?></div></td>
                  <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><div align="center" <?php if($filpar["codniv"] == 5){?> style=" visibility:hidden" <?php }else{ ?>><?PHP echo $row_registros['nomlin'];} ?></div></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><div align="center"<?php if($filpar["codniv"] == 5 || $filpar["codniv"] < 2){?> style=" visibility:hidden" <?php }else { ?>><?php echo $row_registros['nomsubgru']; }?></div></td>
                   
                    <td align="center" valign="top" ><?php if ($row_registros['pub'] == "Si"){ $ico="publish_g.png" ; ?>
                      <a href="proedi.php?cod=<?php echo  $row_registros['codpro']."&pub=No&acc=0"; ?>"><img src="../images/<?php echo $ico; ?>" alt="Cambiar estado" width="16" height="16" border="0" /></a>
                      <?php }else{ $ico ="publish_x.png";?>
                      <a href="proedi.php?cod=<?php echo  $row_registros['codpro']."&pub=Si&acc=0"; ?>"><img src="../images/<?php echo $ico; ?>" alt="Cambiar estado" width="16" height="16" border="0" /></a>
                    <?php } ?></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><div align="center"><?php echo $row_registros['nomtipusuter']; ?></div></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><div align="center" <?php if($filpar["tp"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $row_registros['nomtippro']; ?></div></td>
                    <td valign="top"onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)" ><div align="center"><?php echo $row_registros['hits']; ?></div></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><div align="center"><?php echo $row_registros['nomusu']; ?></div></td>
			    </tr>
                    <?php $numero++; } while ($row_registros = mysql_fetch_assoc($consulta)); }?>
              
            <tr>
              <td height="28" colspan="10" align="center" valign="top" bgcolor="#FFFFFF"><span class="textonegro">
                <?php 
						# variable declaration
						$prev_registros = "&laquo; Anterior";
						$next_registros = "Siguiente &raquo;";
						$separator = " | ";
						$max_links = 10;
						$pages_navigation_registros = paginador($pageNum_registros,$totalPages_registros,$prev_registros,$next_registros,$separator,$max_links,true); 
						print $pages_navigation_registros[0]; 
						?>
                <?php print $pages_navigation_registros[1]; ?> <?php print $pages_navigation_registros[2]; ?></span></td>
            </tr>
            
            
          </table></td>
          <td>&nbsp;</td>
          </tr>
        <tr>
          <td height="24">&nbsp;</td>
          <td valign="top" class="textonegro"><div align="center">Ver # 
            <select name="selnumreg" id="selnumreg" >
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="30">30</option>
              <option value="40">40</option>
              <option value="50">50</option>
              <option value="60">60</option>
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