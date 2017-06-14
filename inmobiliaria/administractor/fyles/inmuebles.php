<?php 
session_start();
include("../../administractor/fyles/general/paginador.php") ;
include("../../administractor/fyles/general/conexion.php") ;

//XAJAX

//incluímos la clase ajax 
require ('../../administractor/fyles/xajax/xajax_core/xajax.inc.php');
//instanciamos el objeto de la clase xajax 
$xajax = new xajax();

$xajax->configure('javascript URI', 'xajax/');

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'inmuebles.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

function departamentos($pais){
	global $enlace;
	$respuesta = new xajaxResponse();
	
	$qrylis ="SELECT d.coddep, d.nomdep FROM deppro AS d 
WHERE d.ci= $pais ";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1coddepsi' id='cbo1coddepsi'  class='textonegro' onChange='xajax_ciudades(this.value)' title='departamentos'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["coddep"]."'>".$fillis["nomdep"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("departamentos","innerHTML","Departamentos<br>".$lista); 
	
	return $respuesta;
}
function ciudades($dep){
	global $enlace;
	$respuesta = new xajaxResponse();
	
	$qrylis ="SELECT c.codciu, c.nomciu FROM ciudad AS c
WHERE c.coddep = $dep ";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1codciusi' id='cbo1codciusi'  class='textonegro' onChange='xajax_barrios(this.value)' title='ciudades'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codciu"]."'>".$fillis["nomciu"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("ciudades","innerHTML","Ciudad<br>".$lista); 
	
	return $respuesta;
}

function barrios($ciu){
	global $enlace;
	$respuesta = new xajaxResponse();
	
	$qrylis ="SELECT b.codbar, b.nombar FROM barrio AS b
WHERE b.codciu = $ciu ";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1codbarsi' id='cbo1codbarsi'  class='textonegro'  title='barrios'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codbar"]."'>".$fillis["nombar"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("barrios","innerHTML","Barrio<br>".$lista); 
	
	return $respuesta;
}

$xajax->registerFunction("departamentos");
$xajax->registerFunction("ciudades");
$xajax->registerFunction("barrios");
$xajax->processRequest();



if (isset($_POST['filtrar']))
{
	$query_registros = "SELECT
	inmuebles.codigo
    , inmuebles.codinmueble
    , inmuebles.nominmueble
    , inmuebles.areainmueble
    , inmuebles.numerohab
	, inmuebles.imginmueble
	, inmuebletipo.nomtipinmueble
    , deppro.nomdep
    , ciudad.nomciu
	, pais.ci
    , barrio.nombar
    , zona.nomzona
	,inmuebles.pub
	,inmuebles.pubini
	,inmuebles.valor
	,u.nomusu
	,pa.paraq
FROM
    inmuebles 
    LEFT JOIN barrio
     ON (inmuebles.codbar = barrio.codbar)
    LEFT JOIN ciudad 
        ON (inmuebles.codciu = ciudad.codciu)
    LEFT JOIN deppro 
        ON (ciudad.coddep = deppro.coddep)
	LEFT JOIN pais 
        ON (deppro.ci = pais.ci)	
    LEFT JOIN inmuebletipo 
        ON (inmuebles.codtipinmueble = inmuebletipo.codtipinmueble) 
    LEFT JOIN zona 
        ON (inmuebles.codzona = zona.codzona)
			LEFT JOIN inmuebleparaq AS pa 
	    ON inmuebles.codparaq = pa.codparaq
    LEFT JOIN usuadm AS u ON inmuebles.codusuadm = u.codusuadm
		
     WHERE inmuebles.codinmueble > 0 ";
	 
	
$tipoinmueble = $_POST["cbo1codinmueblesi"];
$pais = $_POST["cbo1cino"];
$departamento = $_POST["cbo1coddepsi"];
$ciudad = $_POST["cbo1codciusi"];
$Zona = $_POST["cbo1zonasi"];
$barrio = $_POST["cbo1codbarsi"];
$numerohabt = $_POST["txt2numerohabsi"];
$valorini = $_POST["txt2valorinisi2"];
$valorfin = $_POST["txt2valorfinsi2"];
$codigo= $_POST["txtcodigosi"];
$nombre = $_POST["txt2nombresi"];


if($codigo<>""){
$query_registros .= " AND inmuebles.codigo= '$codigo' ";
}


if($nombre<>""){
$query_registros .= " AND inmuebles.nominmueble like '%$nombre%'";
}



if($tipoinmueble<>0){
$query_registros .= " AND inmuebles.codtipinmueble= $tipoinmueble ";
}


if ($pais<>0){
$query_registros .= " AND pais.ci= $pais ";
}

if ($departamento<>0){
$query_registros .= " AND deppro.coddep= $departamento ";
}

if($ciudad<>0){
$query_registros .= " AND inmuebles.codciu= $ciudad ";
}

if($Zona<>0){
$query_registros .= " AND inmuebles.codzona= $Zona ";
}

if($barrio<>0){
$query_registros .= " AND inmuebles.codbar= $barrio ";
}


if($numerohabt<>0){
$query_registros .= " AND inmuebles.numerohab= $numerohabt ";
}

if($valorini <> "" && $valorfin <> ""){
 $query_registros .= " AND (inmuebles.valor) BETWEEN '$valorini' AND '$valorfin'";

}
 $query_registros .= "ORDER BY inmuebles.codinmueble DESC ";

	$_SESSION["qryfiltroinmuebles"] = $query_registros;
	
}

if(isset($_SESSION["qryfiltroinmuebles"])){
	$query_registros=$_SESSION["qryfiltroinmuebles"];
}else{
$query_registros= "SELECT tiempo FROM sesionest WHERE tiempo = -1"; 
}

include("../../administractor/fyles/general/paginadorinferior.php") ;


include("../../administractor/fyles/general/sesion.php");
sesion(1);
destruyesesiones("qryfiltroinmuebles");
?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../../administractor/js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<?php
//En el <head> indicamos al objeto xajax se encargue de generar el javascript necesario 
$xajax->printJavascript(); 
?>
<script type="text/javascript"  src="../../administractor/fyles/general/validaform.js"></script>
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
    <td width="100%" valign="bottom" bgcolor="#000000" class="textogris" style="background-image:url(../../administractor/images/fon_adm.png)"><div align="right"><a href="../../system/files/general/cerrar_sesion.php"><img src="../../administractor/images/cerses.png" alt="Cerrar Ses&oacute;n de Usuario" width="150" height="32" border="0" /></a></div></td>
  </tr>
  <tr>
    <td height="19" colspan="2" valign="top" bgcolor="#F5F5F5"><?php if ($_SESSION["grupo"] == 1){ ?><script type="text/javascript" language="JavaScript1.2" src="../../administractor/js/mnusuperadm.js"></script><?php }else{ ?><script type="text/javascript" language="JavaScript1.2" src="../../administractor/js/mnuadm.js"></script><?php } ?></td>
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
                  <td width="54" rowspan="3" align="center" valign="middle" class="textonegro" ><button class="textonegro" name="nuevo" type="submit" value="nuevo" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../../administractor/images/nuevo.png"  /><br>
                  Nuevo</button></td>
	              <td width="68" rowspan="3" align="center" valign="middle" ><button class="textonegro" name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../../administractor/images/eliminar.png"  /><br>
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
				location = "inmueblescre.php";
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
						location = "inmuebleseli.php?codreg=<?php echo $codreg?>"	
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
          <td height="100">&nbsp;</td>
          <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="textonegro" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="244" height="48" valign="top" class="titulos"> <img src="../../administractor/images/inmueble.png" width="44" height="44" align="absmiddle"> Inmuebles [ Lista ] </td>
                <td width="196" valign="top">Codigo<br>
                  <span class="textoblanco">
                  <input name="txtcodigosi" type="text"  style="background-color:#FFFFFF; border:2px; height:20px" class="textonegro" id="txtcodigosi" value="" size="20" maxlength="20" onFocus="style.backgroundColor='#9BCDFF'" onBlur="style.backgroundColor='#FFFFFF'"  />
                  </span></td>
                <td width="266" valign="middle">Filtrar por Tipo de Inmueble <br>
                  <select  name="cbo1codinmueblesi"  class="textonegro" id="cbo1codinmueblesi">
                    <option value="0">Elige</option>
                    <?
					$qryinmueble= "SELECT inm.* FROM inmuebletipo AS inm ORDER BY inm.nomtipinmueble ";
					$resinmueble = mysql_query($qryinmueble, $enlace);
					while ($filinmueble = mysql_fetch_array($resinmueble ))
					echo "<option value=\"".$filinmueble["codtipinmueble"]."\">".$filinmueble["nomtipinmueble"]."</option>\n";
					mysql_free_result($resinmueble );
				?>
                  </select></td>
            <td colspan="2" valign="middle" class="textonegro">Filtrar por Rango de Valores  <br>
                  <span class="textonegro">Entre $<span class="textoblanco">
                  <input name="txt2valorinisi2" type="text"  style="background-color:#FFFFFF; border:2px; height:20px" class="textonegro" id="txt2valorinisi2" value="" size="10" maxlength="20" onFocus="style.backgroundColor='#9BCDFF'" onBlur="style.backgroundColor='#FFFFFF'"  />
                  </span>y <span class="textoblanco">
                  <input name="txt2valorfinsi2" type="text"  style="background-color:#FFFFFF; border:2px; height:20px" class="textonegro" id="txt2valorfinsi2" tabindex="0" value="" size="10" maxlength="20" onFocus="style.backgroundColor='#9BCDFF'" onBlur="style.backgroundColor='#FFFFFF'"  />
                  </span></span></td>
            <td colspan="3" valign="middle">N&ordm; Habitaciones <br>
                  <input name="txt2numerohabsi" type="text" id="txt2numerohabsi" size="10"maxlength="100" title="N&uacute;mero de Habitaciones" /></td>
            <td width="120" rowspan="3" align="right" valign="middle">                  <input name="filtrar" type="submit" id="filtrar" value="Filtrar" />            </td>
            </tr>
            
            
            <tr>
              <td height="37"></td>
              <td rowspan="2" valign="top">Nombre<br>
                <span class="textoblanco">
                <input name="txt2nombresi" type="text"  style="background-color:#FFFFFF; border:2px; height:20px" class="textonegro" id="txt2nombresi" value="" size="20" maxlength="20" onFocus="style.backgroundColor='#9BCDFF'" onBlur="style.backgroundColor='#FFFFFF'"  />
                </span></td>
              <td valign="middle" id="paises">Pais<br>
                  <select name="cbo1cino" class="textonegro" id="cbo1cino" title="Paises" onChange="xajax_departamentos(this.value)">
                      <option value="144">Colombia</option>
                      <?
					
					$qrypais= "SELECT p.ci, p.cn FROM pais AS p WHERE ci <> 144
					ORDER BY p.cn ";
					$respais = mysql_query($qrypais, $enlace);
					while ($filpais = mysql_fetch_array($respais))
					echo "<option value=\"".$filpais["ci"]."\">".$filpais["cn"]."</option>\n";
					mysql_free_result($respais);
				?>
                  </select></td>
              <td width="218" valign="middle" id="departamentos">Departamento<br>
                <select  name="cbo1coddepsi" class="textonegro" id="cbo1coddepsi" title="departamentos"  onChange="xajax_ciudades(this.value)" >
                      <option value="0">Elige</option>
                      <?
					
					$qrydep= "SELECT d.* FROM deppro AS d WHERE ci = 144
					ORDER BY d.nomdep ";
					$resdep = mysql_query($qrydep, $enlace);
					while ($fildep = mysql_fetch_array($resdep))
					echo "<option value=\"".$fildep["coddep"]."\">".$fildep["nomdep"]."</option>\n";
					mysql_free_result($resdep);
				?>
                  </select></td>
              <td colspan="2" valign="middle" id="ciudades">Ciudad<br>
                <select name="cbo1codciusi" class="textonegro" id="cbo1codciusi" title="ciudades" >
                  <option value="0">Elige</option>
                </select></td>
            <td width="145" valign="middle">Filtrar por Zona <br>
                <span class="textoblanco">
              <Select    name="cbo1zonasi"  class="textonegro" id="cbo1zonasi" title= "Zona de la Ciudad">
                <option value="0" >Elige</option>
                <?
					
					$qryzona= "SELECT zn.codzona, zn.nomzona FROM zona AS zn ORDER BY zn.nomzona ";
					$reszona = mysql_query($qryzona, $enlace);
					while ($filzona = mysql_fetch_array($reszona))
					echo "<option value=\"".$filzona["codzona"]."\">".$filzona["nomzona"]."</option>\n";
					mysql_free_result($reszona);
				?>
              </select>
                  </span></td>
            <td width="208" valign="middle" id="barrios">Barrio<span class="textoblanco"><br>
                <Select    name="cbo1codbarsi"  class="textonegro" id="cbo1codbarsi" title="Barrio del Inmueble">
                  <option value="0" >Elige</option>
                </select>
                  </span></td>
              </tr>
            
            
            <tr>
              <td height="2"></td>
              <td></td>
              <td></td>
              <td width="172"></td>
              <td width="94"></td>
              <td></td>
              <td></td>
              </tr>
            <tr>
              <td height="13"></td>
              <td></td>
              <td></td>
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
          <td height="106">&nbsp;</td>
          <td valign="top"><table width="93%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td height="8" colspan="2" valign="top" bgcolor="#000000"></td>
                  <td width="173" valign="top" bgcolor="#000000"></td>
                  <td width="112" valign="top" bgcolor="#000000"></td>
                  <td width="159" valign="top" bgcolor="#000000"></td>
                  <td width="113" valign="top" bgcolor="#000000"></td>
                  <td width="99" valign="top" bgcolor="#000000"></td>
                  <td width="109" valign="top" bgcolor="#000000"></td>
                  <td width="120" valign="top" bgcolor="#000000"></td>
                  <td width="146" valign="top" bgcolor="#000000"></td>
                  <td width="201" valign="top" bgcolor="#000000"></td>
				   <td width="139" valign="top" bgcolor="#000000"></td>
				    <td width="121" valign="top" bgcolor="#000000"></td>
                </tr>
            <tr>
              <td width="32" height="30" valign="middle" bgcolor="#FFFFFF" ><div align="center">item</div></td>
			    <td width="130" valign="middle" bgcolor="#FFFFFF" >codigo del Inmueble </td>
				  <td valign="middle" bgcolor="#FFFFFF" >nombre del Inmueble </td>
                  <td valign="middle" >Inmueble para </td>
                  <td width="159" valign="middle" bgcolor="#FFFFFF" >Tipo del Inmueble </td>
                  <td valign="middle" bgcolor="#FFFFFF" ><div align="center">Publicado</div></td>
                  <td valign="middle" bgcolor="#FFFFFF" ><div align="center">Publica en Inicio </div></td>
                  <td valign="middle" bgcolor="#FFFFFF" >Ciudad</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Zona</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Area</td>
                  <td valign="middle" bgcolor="#FFFFFF" >Valor</td>
                  <td width="139" valign="middle" bgcolor="#FFFFFF" >N&ordm; Habitaciones </td>
                  <td width="121" valign="middle" bgcolor="#FFFFFF" >Creado por Por </td>
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
		$codreg = $row_registros['codinmueble'];
	?>
	<tr onMouseOver="this.style.backgroundColor='#E1EBD8';" class="pointer" onMouseOut="this.style.backgroundColor='#F3F3F3'" title="Editar publicación">
              <td height="21" valign="top"><div align="center">
                <input type="checkbox" name="registros[]" value="<?php echo $codreg; ?>" />
                </div></td>
				 <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['codigo']; ?></td>
				  <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nominmueble']; ?></td>
                     <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['paraq']; ?></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nomtipinmueble']; ?></td>
                    <td valign="top"><div align="center"><?php if ($row_registros['pub'] == "Si"){ $ico="publish_g.png" ; ?> <a href="../../administractor/fyles/inmueblesedi.php?cod=<?php echo  $row_registros['codinmueble']."&amp;pub=No&amp;acc=0&amp;pubini=".$row_registros['pubini']; ?>"><img src="../images/<?php echo $ico; ?>" alt="Cambiar estado" width="16" height="16" border="0" /></a> <?php }else{ $ico ="publish_x.png";?><a href="../../administractor/fyles/inmueblesedi.php?cod=<?php echo  $row_registros['codinmueble']."&amp;pub=Si&amp;acc=0&amp;pubini=".$row_registros['pubini']; ?>"><img src="../images/<?php echo $ico; ?>" alt="Cambiar estado" width="16" height="16" border="0" /></a><?php } ?>
                    </div></td>
                    <td valign="top"><div align="center"><?php if ($row_registros['pubini'] == "Si"){ $ico="publish_g.png" ; ?> <a href="../../administractor/fyles/inmueblesedi.php?cod=<?php echo  $row_registros['codinmueble']."&amp;pub=".$row_registros['pub']."&amp;acc=0&amp;pubini=No"; ?>"><img src="../images/<?php echo $ico; ?>" alt="Cambiar estado" width="16" height="16" border="0" /></a> <?php }else{ $ico ="publish_x.png";?><a href="../../administractor/fyles/inmueblesedi.php?cod=<?php echo  $row_registros['codinmueble']."&amp;pub=".$row_registros['pub']."&amp;acc=0&amp;pubini=Si"; ?>"><img src="../images/<?php echo $ico; ?>" alt="Cambiar estado" width="16" height="16" border="0" /></a><?php } ?>
                    </div></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nomciu']; ?></td>
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nomzona']; ?></td>
					
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['areainmueble']; ?></td>
					
                  <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['valor']; ?></td>
					
                    <td valign="top" onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['numerohab']; ?></td>
                    <td valign="top"onClick="edita('<?php echo substr(basename($_SERVER['PHP_SELF']),0,-4)?>','<?php echo $codreg?>',1)"><?php echo $row_registros['nomusu']; ?></td>
                </tr>
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
                <td>&nbsp;</td>
                <td>&nbsp;</td>
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
    <td height="32" colspan="2" valign="top"><div align="center" class="textonegro">  <strong>ADMIN-WEB</strong> <a href="http://www.rentayamiami.com" target="_blank">www.rentayamiami.com</a> </div></td>
  </tr>
</table>
</form>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($consulta);
?>