<?php
session_start();
include("general/conexion.php") ;

//XAJAX

//incluímos la clase ajax 
require ('xajax/xajax_core/xajax.inc.php');
//instanciamos el objeto de la clase xajax 
$xajax = new xajax();

$xajax->configure('javascript URI', 'xajax/');

include("fckeditor/fckeditor.php") ;

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'procre.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//consulto parametros del producto
$qrypar= "SELECT * FROM propar";
$respar = mysql_query($qrypar, $enlace);
$filpar = mysql_fetch_assoc($respar);

//consulto parametros de publicacion
$qrypub= "SELECT promin, proori FROM pubpar ";
$respub = mysql_query($qrypub, $enlace);
$filpub = mysql_fetch_assoc($respub);

//consulto formato de moneda defecto
$qrymon = "SELECT m.* FROM tblmonedas m WHERE m.mondefecto = '2' ";
$resmon = mysql_query($qrymon, $enlace);
$filmon = mysql_fetch_assoc($resmon);

$qryult = "select max(codpro) as maximo from pro";
$result = mysql_query($qryult, $enlace);
$filult= mysql_fetch_assoc($result);
$ultimo = $filult["maximo"] + 1;

function agregaprecio($form_entrada){
	global $enlace;
	$fecha = date("Y-m-j");
	$qrypre ="INSERT INTO tblmaestratemporal VALUES(".$_SESSION["enlineaadm"].",'$fecha','".$form_entrada["cbo1codlispreno"]."','".$form_entrada["txt1preprono"]."','','','','','','')";
	
	$respre = mysql_query($qrypre, $enlace);
	
	return preciosproducto(); 
}

function eliminaprecio($codlispre){
	global $enlace;
	$qrypre ="DELETE FROM tblmaestratemporal WHERE val1 =  $codlispre AND codusu = ".$_SESSION["enlineaadm"]."";
	$respre =mysql_query($qrypre, $enlace);
	return preciosproducto(); 
}

function preciosproducto(){
	
	global $enlace;
	global $filmon;
	$respuesta = new xajaxResponse();

	$qryval = "SELECT  lp.nomlispre , t.val1, t.val2 FROM tblmaestratemporal t INNER JOIN tbllistasdeprecio lp ON t.val1 = lp.codlispre WHERE codusu = ".$_SESSION["enlineaadm"].""; 
	$resval = mysql_query($qryval, $enlace);
	
	$salida = "<table class='textonegro' bgcolor='999999' width='100%' ><tr><th align='left'>Lista de precio</th><th align='left'>Valor</th><th align='left'>Editar</th><th align='center'>Eliminar</th></tr>";
	while($filval = mysql_fetch_assoc($resval)){
		$salida .= "<tr>";
		$salida .= "<td>".$filval["nomlispre"]."</td>";
		$salida .= "<td>".$filmon["symbolizq"]." ".number_format($filval["val2"],$filmon["lugdec"],$filmon["pundec"],$filmon["punmil"])." ".$filmon["symbolder"]."</td>";
		$salida .= "<td><input type='text' name='txt".$filval["val1"]."' id='txt".$filval["val1"]."' value='".$filval["val2"]."' size='10' maxlength='10'  onBlur='actualizaprecio(".$filval["val1"].",this.value)' class='textonegro' onKeyPress=onlyDigits(event,'decOK') ></td>";
		$salida .= "<td align='center'><img src='../images/eliminarp.png' width='16' height='16' border='0' onclick='eliminaprecio(".$filval["val1"].")' alt'Eliminar valor' class='pointer'></td>";
		$salida .= "</tr>";	
	}
	$salida.="</table>";
	
	$qrylis = "SELECT l.codlispre, l.nomlispre FROM tbllistasdeprecio l WHERE l.codlispre NOT IN (SELECT val1 FROM tblmaestratemporal WHERE codusu = ".$_SESSION["enlineaadm"].") ORDER BY l.nomlispre";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1codlispreno' id='cbo1codlispreno'  class='textonegro'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codlispre"]."'>".$fillis["nomlispre"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("listadeprecio","innerHTML",$lista); 
	$respuesta->assign("preciosproducto","innerHTML",$salida); 
	
	return $respuesta;
}

function actualizaprecio($lista, $valor){
	global $enlace;
	$qryval ="UPDATE tblmaestratemporal SET val2 = '$valor' WHERE val1 = $lista AND codusu = ".$_SESSION["enlineaadm"]."";
	$resval =mysql_query($qryval, $enlace);
	
	return preciosproducto();
}


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

function validareferencia($ref){
	global $enlace;
	$respuesta = new xajaxResponse();
	$qrypro = "SELECT pd.codpro, pd.nompro FROM prodet AS pd 
	INNER JOIN tblproductosreferencias AS pr ON pd.codpro = pr.codpro AND pd.codidi = 1
	 WHERE pr.refpro = '$ref'";
	$respro = mysql_query($qrypro);

	if(mysql_num_rows($respro) > 0){
		$filpro = mysql_fetch_assoc($respro);
		$respuesta->alert("La referencia ya esta asociada al producto: ".$filpro["nompro"]);
		$respuesta->asign("txt2refprosi","value","");
		return $respuesta;
	}
	
}
$xajax->registerFunction("agregaprecio");
$xajax->registerFunction("eliminaprecio");
$xajax->registerFunction("preciosproducto");
$xajax->registerFunction("actualizaprecio");
$xajax->registerFunction("subgrupo");
$xajax->registerFunction("clase");
$xajax->registerFunction("subclase");
$xajax->registerFunction("validareferencia");

//El objeto xajax tiene que procesar cualquier petición 
$xajax->processRequest(); 

include("general/sesion.php");
include("general/operaciones.php");
sesion(1);
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
<script type="text/javascript" >
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

function nombrefoto()
{	
var filename = document.form1.img1fileno.value ;
filename = filename.substr(filename.lastIndexOf('\\')+1);
var extenciones = new Array("jpg","jpeg","png","gif");
var tipo = filename.substr(filename.lastIndexOf('.')+1);

	for(i=0; i<extenciones.length; i++)
	   {
	   if(extenciones[i] == tipo){
	   	   pasa = true;
		   break;
			
		}else{
			pasa = false;
		}
	} 
	
	if(pasa){
		document.form1.hid1imgprosi.value ="<?php echo $ultimo?>."+tipo;
	}else{
		alert("El tipo dearchivo no es permitido");
		document.form1.img1fileno.value="";
		document.form1.hid1imgprosi.value ="";
	}
}

function agregaprecio()
{

	if(document.form1.cbo1codlispreno.value==0){
	alert("Debe seleccionar la lista de precio del producto")
	document.form1.cbo1codlispreno.focus()
	exit();
	}
	
	if(document.form1.txt1preprono.value==""){
	alert("Debe ingresar el precio del producto")
	document.form1.txt1preprono.focus()
	exit();
	}
	
	xajax_agregaprecio(xajax.getFormValues("form1"));
}

function eliminaprecio(codlispre)
{
	xajax_eliminaprecio(codlispre);
}

function preciosproducto()
{
	xajax_preciosproducto();
}

function actualizaprecio(lista, precio ){
	xajax_actualizaprecio(lista, precio);
}
</script>

<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
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
	  <form id="form1" name="form1" method="post" action=""  onSubmit="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="61" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="6" height="20"></td>
                  <td width="915">&nbsp;</td>
                  <td width="14"></td>
                  <td width="62" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro(); "><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
                  <td width="70" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
                  <td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="13"></td>
            </tr>
            <tr>
              <td height="15"></td>
              <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="26" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php
	
function cargarimagen(){
	global $enlace;
	global $filpub;
	global $ultimo;
	
	$continua = TRUE;

	//Verifico si se inserta imagen de la publicación
	$file_name = $_FILES['img1fileno']['name'];
	if( $file_name <> ""){ //if 3
		
		$continua = TRUE; 

		//Ruta donde guardamos las imágenes
		$ruta_miniaturas = "../productos/mini";
		$ruta_original = "../productos";
								
		//El ancho de la miniatura
		$ancho_miniatura = $filpub["promin"];
		$ancho_original = $filpub["proori"]; 
		
		//Extensiones permitidas
		$extensiones = array(".gif",".jpg",".png",".jpeg");
		$datosarch = $_FILES["img1fileno"];
		$file_type = $_FILES['img1fileno']['type'];
		$file_size = $_FILES['img1fileno']['size'];
		$file_tmp = $_FILES['img1fileno']['tmp_name'];
		
		//validar la extension
		$ext = strrchr($file_name,'.');
		$ext = strtolower($ext);
		if (!in_array($ext,$extensiones)) {	 //if 5	   
			echo "¡El tipo de archivo no es permitido!";
			$continua = FALSE;			  
		} // fin if 5
		if($continua){  //if
			// validar tamaño de archivo	   
			if  ($file_size > 8368308) //bytes = 8368308 = 8392kb = 8Mb
			/*Copia el archivo en una directorio específico del servidor*/
			{ //if 7
				echo "¡El archivo debe ser inferior a 8MB!";						
				$continua = FALSE;				
			} //fin if 7
			if ($continua){ //if 
				//Tomamos la extension
					$getExt = explode ('.', $file_name);
					$file_ext = $getExt[count($getExt)-1];  
					$ThumbWidth = $ancho_miniatura;
					$ThumbWidth1 = $ancho_original;
					   
					//buscamos la funcion segun la imagen
					if($file_size){
						if($file_type == "image/pjpeg" || $file_type == "image/jpeg"){
							$nueva_imagen = imagecreatefromjpeg($file_tmp);
						}elseif($file_type == "image/x-png" || $file_type == "image/png"){
						   $nueva_imagen = imagecreatefrompng($file_tmp);
						}elseif($file_type == "image/gif"){
						   $nueva_imagen = imagecreatefromgif($file_tmp);
						}
						//Chequeamos el ancho y el alto para mantener la relacion de aspecto
						list($width, $height) = getimagesize($file_tmp);
						$imgratio=$width/$height;
							   
						if ($imgratio>1){
							$nuevo_ancho = $ThumbWidth;
							$nuevo_alto = $ThumbWidth/$imgratio;
							$nuevo_ancho1 = $ThumbWidth1;
							$nuevo_alto1 = $ThumbWidth1/$imgratio;
						}else{
							$nuevo_alto = $ThumbWidth;
							$nuevo_ancho = $ThumbWidth*$imgratio;
							$nuevo_alto1 = $ThumbWidth1;
							$nuevo_ancho1 = $ThumbWidth1*$imgratio;
						}
						$redimensionada = imagecreatetruecolor($nuevo_ancho,$nuevo_alto);
						$redimensionada1 = imagecreatetruecolor($nuevo_ancho1,$nuevo_alto1);
						
						imagecopyresized($redimensionada, $nueva_imagen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $width, $height);
						imagecopyresized($redimensionada1, $nueva_imagen, 0, 0, 0, 0, $nuevo_ancho1, $nuevo_alto1, $width, $height);
						
						$nombre_nuevaimg = $ultimo.".".$file_ext; 
		
						//guardamos la imagen
						ImageJpeg ($redimensionada,"$ruta_miniaturas/$nombre_nuevaimg", $filpub["promin"]);
						ImageDestroy ($redimensionada);
						
					}
					//Subimos la imagen original
					ImageJpeg ($redimensionada1,"$ruta_original/$nombre_nuevaimg", $filpub["proori"]);
					
					//move_uploaded_file ($redimensionada1, "$ruta_original/$nombre_nuevaimg");
					ImageDestroy ($redimensionada1);
					ImageDestroy ($nueva_imagen);
					return($continua);			

				} //fin if 
	
				return($continua);
			}// fin if 
	}else{
		return($continua);
	}//fin if 3
}			
				
if (isset($_POST['guardarno'])){
	$continua = cargarimagen();
	
	if($continua){
	
		$siguiente=guardar("pro",1,"codpro",2);
		auditoria($_SESSION["enlineaadm"],'Productos',$siguiente,'3');
		//inserto detalle producto
		$qryproinsdet="INSERT INTO prodet VALUES ('0', '$siguiente', '".$_POST["txt2nomprono"]."', '".$_POST["txt1desprono"]."', '1' )";							
		$resproinsdet=mysql_query($qryproinsdet,$enlace);
		
		//inserto precios
		$qrypre = "INSERT INTO tblproductosprecios (SELECT val1, '$siguiente' codpro, val2 FROM tblmaestratemporal WHERE codusu=".$_SESSION["enlineaadm"].")";
		$respre = mysql_query($qrypre, $enlace);
		
		//inserto referencia principal
		$qryref = "INSERT INTO tblproductosreferencias VALUES('0','$siguiente','".$_POST["txt2refprono"]."','0','+','0','Principal')";
		$resref = mysql_query($qryref, $enlace);
		
		echo '<script language = "JavaScript">
			location = "pro.php";
			</script>';
	}
}
if (isset($_POST['aplicarno'])){
	$continua = cargarimagen();
	
	if($continua){
		$siguiente = guardar("pro",2,"codpro",2);
		
		auditoria($_SESSION["enlineaadm"],'Productos',$siguiente,'3');
		//inserto detalle producto
		$qryproinsdet="INSERT INTO prodet VALUES ('0', '$siguiente', '".$_POST["txt2nomprono"]."', '".$_POST["txt1desprono"]."', '1' )";							
		$resproinsdet=mysql_query($qryproinsdet,$enlace);
		
		//inserto precios
		$qrypre = "INSERT INTO tblproductosprecios (SELECT val1, '$siguiente' codpro, val2 FROM tblmaestratemporal WHERE codusu=".$_SESSION["enlineaadm"].")";
		$respre = mysql_query($qrypre, $enlace);
		
		//inserto referencia principal
		$qryref = "INSERT INTO tblproductosreferencias VALUES('0','$siguiente','".$_POST["txt2refprono"]."','0','+','0','Principal')";
		$resref = mysql_query($qryref, $enlace);
		
		?>
		<script language = JavaScript>
		location = "proedi.php?cod=<?php echo $siguiente?>&acc=1";
		</script>
		<?php
	}			
}

							 
//boton cancelar cambios
if (isset($_POST['cancelarno'])){
	echo '<script language = JavaScript>
	location = "pro.php";
	</script>';
}
	
	
$qrytmp ="DELETE FROM tblmaestratemporal WHERE codusu = ".$_SESSION["enlineaadm"]."";
$restmp = mysql_query($qrytmp, $enlace);
				 ?>
              </div></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
            </tr>
            
              </table></td>
        </tr>
        <tr>
          <td width="4" height="25">&nbsp;</td>
          <td width="1379">&nbsp;</td>
          <td width="11">&nbsp;</td>
        </tr>
        
        <tr>
          <td height="52">&nbsp;</td>
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/carrito.png" width="48" height="48" align="absmiddle" />Productos [Crea] <strong></strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="344">&nbsp;</td>
          <td valign="top"><table width="58%" height="486" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="10" height="13"></td>
                  <td width="171"></td>
                  <td width="62"></td>
                  <td width="47"></td>
                  <td width="153"></td>
                  <td width="41"></td>
                  <td width="53"></td>
                  <td width="99"></td>
                  <td width="155"></td>
                  <td width="34"></td>
                  <td width="235"></td>
                  <td width="24"></td>
            </tr>
            <tr>
              <td height="35"></td>
              <td rowspan="2" valign="top"><p>Referencia <br>
                <input name="txt2refprono" type="text" class="textonegro" id="txt2refprono" value = "<?php if (isset($_POST['txtref'])) echo $_POST['txtref']; ?>" size="20"maxlength="20" title="Referencia" onBlur="xajax_validareferencia(this.value)" />
              </p></td>
                  <td colspan="6" rowspan="2" valign="top"> Nombre<br>
                    <input name="txt2nomprono" type="text" class="textonegro" id="txt2nomprono" value="<?php if (isset($_POST['txtnom'])) echo $_POST['txtnom']; ?>" size="50" maxlength="100" title="Nombre producto"/></td>
              <td colspan="2" rowspan="2" valign="top" >Nivel de Acceso<br>
                    <select name="cbo2codtipusutersi" id="cbo2codtipusutersi" title="Nivel de Acceso">
                      <option value="0">Elige</option>
                      <?
						if (isset($_POST['selter'])){
							$ter=$_POST['selter'];
							$qryter = "SELECT * FROM tipusuter WHERE codtipusuter <> '$ter' AND codtipusuter < 3 ORDER BY codtipusuter DESC ";
							$qryter1 = "SELECT * FROM tipusuter WHERE codtipusuter= '$ter'";
							$rester1 = mysql_query($qryter1,$enlace);
							$filter1 = mysql_fetch_array($rester1);
							echo "<option selected value=\"".$filter1['codtipusuter']."\">".$filter1['nomtipusuter']."</option>\n";
							mysql_free_result($rester1);
						}
						else
						{
							$qryter = "SELECT * FROM tipusuter WHERE  codtipusuter < 3 ORDER BY codtipusuter DESC";
						}
						$rester = mysql_query($qryter, $enlace);
						while ($filter = mysql_fetch_array($rester))
						echo "<option value=\"".$filter["codtipusuter"]."\">".$filter["nomtipusuter"]."</option>\n";
						mysql_free_result($rester);
					?>
                          </select></td>
              <td valign="top" >Publicar                  
                <br>
                <select name="cbo2pubsi" id="cbo2pubsi" title="Publicar">
                      <option value="0" selected>Elige</option>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                                                                </select></td>
              <td></td>
            </tr>
            <tr>
              <td height="1"></td>
              <td ></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="15"></td>
              <td valign="top">Lista de precio </td>
              <td colspan="2" valign="top">Precio</td>
              <td valign="top">Moneda defecto </td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="21"></td>
              <td valign="top" id="listadeprecio"><select name="cbo1codlispreno" class="textonegro" id="cbo1codlispreno" title="Opci&oacute;n de producto"  >
                <option value="0">Elige</option>
                <?
				$qrylis= "SELECT l.codlispre, l.nomlispre FROM tbllistasdeprecio l WHERE estlispre = 'Si' ORDER BY l.nomlispre ";
				$reslis = mysql_query($qrylis, $enlace);
				while ($fillis = mysql_fetch_array($reslis))
				echo "<option value=\"".$fillis["codlispre"]."\">".$fillis["nomlispre"]."</option>\n";
				mysql_free_result($reslis);
				?>
              </select></td>
              <td colspan="2" valign="top"><input name="txt1preprono" type="text" class="textonegro" id="txt1preprono" size="10"maxlength="10" onKeyPress="onlyDigits(event,'decOK')"/></td>
              <td valign="top"><?php echo $filmon["nommon"] ?></td>
              <td colspan="3" align="right" valign="top"><input name="agregarpreciono" type="button" class="textonegro" id="agregarpreciono" onClick="agregaprecio()" value="Agregar"></td>
              <td></td>
              <td></td>
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
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="13"></td>
              <td colspan="7" valign="top" class="texnegronegrita">PRCIOS DE PRODUCTO </td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="18"></td>
              <td colspan="7" valign="top" bgcolor="#FFFF99" id="preciosproducto"><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td></td>
              <td></td>
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
              <td></td>
              <td></td>
              <td></td>
            </tr>
            
            
            

            
            
            
            <tr>
              <td height="35"></td>
              <td colspan="2" valign="top" <?php if($filpar["tp"] == 1){?>style=" visibility:hidden" <?php }?>> Tipo de Producto<br>                
                <select name="cbo1codtipprosi" class="textonegro" id="cbo1codtipprosi" title="Tipo de producto">
                  <option value="0">Elige</option>
                      <?
					if (isset($_POST['seltip'])){
						$tip=$_POST['seltip'];
						$qrytip = "SELECT * FROM tippro WHERE codtippro <> '$tip' AND codtippro < 3 ORDER BY nomtippro ";
						$qrytip1 = "SELECT * FROM tippro WHERE codtippro= '$tip'";
						$restip1 = mysql_query($qrytip1,$enlace);
						$filtip1 = mysql_fetch_array($restip1);
						echo "<option selected value=\"".$filtip1['codtippro']."\">".$filtip1['nomtippro']."</option>\n";
						mysql_free_result($restip1);
					}
					else
					{
						$qrytip= "SELECT * FROM tippro WHERE codtippro < 3  ORDER BY nomtippro ";
					}
					$restip = mysql_query($qrytip, $enlace);
					while ($filtip = mysql_fetch_array($restip))
					echo "<option value=\"".$filtip["codtippro"]."\">".$filtip["nomtippro"]."</option>\n";
					mysql_free_result($restip);
				?>
                  </select></td>
              <td colspan="3" valign="top"  <?php if($filpar["fab"] == 1){?> style=" visibility:hidden"<?php }?>>Fabricante<span class="textonegro">
                  <br>
                  <select name="cbo1codfabsi" class="textonegro" id="cbo1codfabsi" title="Fabricante">
                    <option value="0">Elige</option>
                    <?
					
					$qryfab= "SELECT codfab, nomfab FROM fab WHERE estfab ='Activo' ORDER BY nomfab ";

					$resfab = mysql_query($qryfab, $enlace);
					while ($filfab = mysql_fetch_array($resfab))
					echo "<option value=\"".$filfab["codfab"]."\">".$filfab["nomfab"]."</option>\n";
					mysql_free_result($resfab);
				?>
                  </select>
              </span></td>
              
              <td colspan="2" valign="top" <?php if($filpar["manpin"] == 1){?>style=" visibility:hidden"  <?php }?>>Maneja Pintas<br>
                <select name="cbo1mospinsi" class="textonegro" id="cbo1mospinsi" title="Maneja pintas">
                      <option value="0">Elige</option>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                                                </select></td>
              <td valign="top"   <?php if($filpar["carcol"] == 1){?>style=" visibility:hidden"<?php }?>>Maneja Colores<br>
                <select name="cbo1moscolsi" class="textonegro" id="cbo1moscolsi" title="Maneja Colores">
                      <option value="0">Elige</option>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                                                </select></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td></td>
            </tr>
            
            <tr>
              <td height="20"></td>
              <td>&nbsp;</td>
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
              <td height="13"></td>
              <td colspan="3" valign="top">L&iacute;nea</td>
              <td colspan="3" valign="top">Subgrupo</td>
              <td colspan="2" valign="top">Clase</td>
              <td colspan="2" valign="top">Sub-Clase</td>
              <td></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td colspan="3" valign="top"><select name="cbo2codlinsi" class="textonegro" id="cbo2codlinsi" title="Línea" onChange="xajax_subgrupo(this.value)">
                <option value="0">Elige</option>
                <?
					
					$qrylin= "SELECT l.codlin, ld.nomlin FROM linneg AS l 
					INNER JOIN linnegdet AS ld ON l.codlin = ld.codlin AND ld.codidi = 1 ";
					$reslin = mysql_query($qrylin, $enlace);
					while ($fillin = mysql_fetch_array($reslin))
					echo "<option value=\"".$fillin["codlin"]."\">".$fillin["nomlin"]."</option>\n";
					mysql_free_result($reslin);
				?>
              </select></td>
              <td colspan="3" valign="top" id="subgrupo"><select name="cbo1codsubgrusi" class="textonegro" id="cbo1codsubgrusi" title="Subgrupo" >
                <option value="0">Elige</option>
              
              </select></td>
              <td colspan="2" valign="top" id="clase"><select name="cbo1codclasi" class="textonegro" id="cbo1codclasi" title="Clase" >
                <option value="0">Elige</option>
              </select></td>
              <td colspan="2" valign="top" id="subclase"><select name="cbo1codsubclasi" class="textonegro" id="cbo1codsubclasi" title="Sub-Clase" >
                <option value="0">Elige</option>
              </select></td>
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
              <td></td>
              <td></td>
              <td></td>
            </tr>
            

            <tr>
              <td height="24"></td>
              <td colspan="10" valign="top" class="textonegro">Imagen Producto (Ancho: <?php echo $filpub["proori"]; ?> px)
                  <input name="img1fileno" type="file" id="img1fileno" onChange="nombrefoto()"  title="Imagen de producto"/>
                  <input type="hidden" name="hid1codusuadmsi" id="hid1codusuadmsi" value="<?php echo $_SESSION['enlineaadm'];?>">
                  <input name="hid1hitssi" type="hidden" id="hid1hitssi" title="Nombre del alb&uacute;m" value="0" size="10"maxlength="100" />
                  <input name="hid1imgprosi" type="hidden" id="hid1imgprosi" title="Nombre del alb&uacute;m" value="defecto.jpg" size="10"maxlength="100" />
                  <input name="hid1feccresi" type="hidden" id="hid1feccresi" title="Nombre del alb&uacute;m" value="<?php echo date("Y-m-j H:i:s")
 ?>" size="10"maxlength="100" /></td>
                  <td></td>
            </tr>
            <tr>
              <td height="6"></td>
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
              <td height="13"></td>
              <td colspan="10" valign="top" class="textonegro">Descripci&oacute;n </td>
                  <td></td>
            </tr>
            
            <tr>
              <td height="181"></td>
              <td colspan="10" valign="top"><?php
				// Automatically calculates the editor base path based on the _samples directory.
				// This is usefull only for these samples. A real application should use something like this:
				// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
				
				$oFCKeditor = new FCKeditor('txt1desprono') ;
				$oFCKeditor->BasePath = '../fyles/fckeditor/';
				
				if (isset($_POST['txt1desprono'])){
					$oFCKeditor->Value = $_POST['txt1desprono'] ;
				}
				else
				{
					$oFCKeditor->Value = "" ;
				}
				$oFCKeditor->Create() ;
				?></td>
                  <td></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
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
          </table></td>
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