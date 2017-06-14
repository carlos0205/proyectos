<?php
session_start();
include("../../administractor/fyles/general/conexion.php") ;
include("../../administractor/fyles/general/sesion.php");
include("../../administractor/fyles/general/operaciones.php");
//XAJAX

//incluímos la clase ajax 
require ('../../administractor/fyles/xajax/xajax_core/xajax.inc.php');
//instanciamos el objeto de la clase xajax 
$xajax = new xajax();

$xajax->configure('javascript URI', 'xajax/');

sesion(1);
include("../../administractor/fyles/fckeditor/fckeditor.php") ;


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'inmueblescre.php';
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
	$lista.= "<option value='24'>Elige</option>";
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

//consulto parametros de publicacion
$qrypub= "SELECT proyecmin, proyecori FROM pubpar ";
$respub = mysql_query($qrypub, $enlace);
$filpub = mysql_fetch_assoc($respub);

$qryult = "select max(codinmueble) AS maximo FROM inmuebles";
$result = mysql_query($qryult, $enlace);
$filult= mysql_fetch_assoc($result);
$ultimo = $filult["maximo"] + 1;
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
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" -->
<!-- other languages might be available in the lang directory; please check
your distribution archive. -->
<script type="text/javascript"  src="../../administractor/fyles/general/validaform.js"></script>
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


function nombrefoto()
{	
var filename = document.form1.img1fileno.value ;
filename = filename.substr(filename.lastIndexOf('\\')+1);
var extenciones = new Array("jpg","jpeg","png","gif","JPG","JPEG","PNG","GIF");
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
		document.form1.hid1imginmueblesi.value ="<?php echo $ultimo?>."+tipo;
	}else{
		alert("El tipo dearchivo no es permitido");
		document.form1.img1fileno.value="";
		document.form1.hid1imginmueblesi.value ="";
	}
}<!--


// If this handler returns true then the "date" given as
// parameter will be disabled.  In this example we enable
// only days within a range of 10 days from the current
// date.
// You can use the functions date.getFullYear() -- returns the year
// as 4 digit number, date.getMonth() -- returns the month as 0..11,
// and date.getDate() -- returns the date of the month as 1..31, to
// make heavy calculations here.  However, beware that this function
// should be very fast, as it is called for each day in a month when
// the calendar is (re)constructed.
//-->
</script>
<!-- InstanceEndEditable -->
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
	  <form id="form1" name="form1" method="post" action=""  onSubmit="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="33" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
        <!--DWLayoutTable-->
        <tr>
          <td width="10" height="20"></td>
          <td width="853">&nbsp;</td>
          <td width="30">&nbsp;</td>
          <td width="69" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro(); "><img width="32" src="../../administractor/images/guardar.png"  /><br>
                  Guardar</button></td>
          <td width="63" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../../administractor/images/aplicar.png"  /><br>
                  Aplicar</button></td>
          <td width="64" rowspan="3" align="center" valign="middle"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../../administractor/images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
          <td width="10"></td>
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
		$ruta_miniaturas = "../inmuebles/mini";
		$ruta_original = "../inmuebles";
								
		//El ancho de la miniatura
		$ancho_miniatura = $filpub["proyecmin"];
		$ancho_original = $filpub["proyecori"]; 
		
		//Extensiones permitidas
		$extensiones = array(".gif",".jpg",".png",".jpeg",".GIF",".JPG",".PNG",".JPEG");
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
								ImageJpeg ($redimensionada,"$ruta_miniaturas/$nombre_nuevaimg", $filpub["proyecmin"]);
								ImageDestroy ($redimensionada);
								
							}
							//Subimos la imagen original
							ImageJpeg ($redimensionada1,"$ruta_original/$nombre_nuevaimg", $filpub["proyecori"]);
							
							//move_uploaded_file ($redimensionada1, "$ruta_original/$nombre_nuevaimg");
							ImageDestroy ($redimensionada1);
							ImageDestroy ($nueva_imagen);
							return($continua);			

				} //fin if 
	
				return($continua);
			}// fin if 
	}else{
		$continua = FALSE;
		return($continua);
	}//fin if 3
}
	
		if (isset($_POST['guardarno'])){
		$continua = cargarimagen();
		
		if($continua){
			$siguiente = guardar("inmuebles",1,"codinmueble",2);
			auditoria($_SESSION["enlineaadm"],'inmuebles',$siguiente,'3');
		echo '<script language = JavaScript>
			location = "inmuebles.php";
			</script>';
		}
	}
	if (isset($_POST['aplicarno'])){
		$continua = cargarimagen();
		
		if($continua){
			$siguiente = guardar("inmuebles",2,"codinmueble",2);
			auditoria($_SESSION["enlineaadm"],'inmuebles',$siguiente,'3');
			?>
			<script language="javascript" type="text/javascript">
			location = "inmueblesedi.php?cod=<?php echo $siguiente?>";
			</script>
			<?php
		}			
	}
	//boton cancelar cambios
	if (isset($_POST['cancelarno'])){
		echo '<script language = JavaScript>
		location = "inmuebles.php";
		</script>';
	}
?>
          </div></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        
      </table></td>
        </tr>
        <tr>
          <td width="11" height="25">&nbsp;</td>
          <td width="1183">&nbsp;</td>
          <td width="9">&nbsp;</td>
        </tr>
        <tr>
          <td height="45">&nbsp;</td>
          <td valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="1374" height="57" valign="top" class="titulos"><img src="../../administractor/images/inmueble.png" width="48" height="48" align="absmiddle"> Inmuebles [Crea]  <strong>
            <script type="text/javascript" language="JavaScript" src="../../administractor/fyles/general/validaform.js"></script>
          </strong></td>
        </tr>
      </table></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="156">&nbsp;</td>
          <td valign="top"><select  name="cbo1codtipinmueblesi"  class="textonegro" id="cbo1codtipinmueblesi">
            <option value="0">Elige</option>
            <?
					$qryinmueble= "SELECT inm.* FROM inmuebletipo AS inm where inm.caracteristica = 'Tipo de Inmueble' ORDER BY inm.nomtipinmueble ";
					$resinmueble = mysql_query($qryinmueble, $enlace);
					while ($filinmueble = mysql_fetch_array($resinmueble ))
					echo "<option value=\"".$filinmueble["codtipinmueble"]."\">".$filinmueble["nomtipinmueble"]."</option>\n";
					mysql_free_result($resinmueble );
				?>
          </select>
		  
		  <?php 
		  echo "<div style ='display:none'>";
		  $tipinm = $_POST["cbo1codtipinmueblesi"]; 
		  if ($tipinm = 1){  $valtip = "A";}
		  
		  if ($tipinm = 2){  $valtip = "A";}
		  
		  if ($tipinm = 3){  $valtip = "B";}
		  
		  if ($tipinm = 4){  $valtip = "F";}
		  
		  if ($tipinm = 5){  $valtip = "H";}
		  
		  if ($tipinm = 7){  $valtip = "C";}
		  
		  if ($tipinm = 8){  $valtip = "P";}
		  
		  if ($tipinm = 9){  $valtip = "CH";}
		  
		  if ($tipinm = 10){  $valtip = "E";}
		  
		  if ($tipinm = 11){  $valtip = "O";}	  
		  
		  echo "</div>";
		  
		  ?>
		  
          <table width="100" height="411" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
        <!--DWLayoutTable-->
        <tr>
          <td width="1" height="18"></td>
          <td width="4"></td>
          <td colspan="20" valign="top" class="titulos">Detalle del Inmueble </td>
          <td width="35"></td>
        </tr>
        <tr>
          <td height="48"></td>
          <td></td>
          <td width="115" align="left" valign="middle">Valor del Inmueble <br>
            <input name="txt2valorsi" type="text" id="txt2valorsi" title="Valor del Inmueble" size="15"maxlength="100" /></td>
          <td colspan="3" valign="middle"><p>Tipo de Inmueble
            <select  name="select"  class="textonegro" id="select">
              <option value="0">Elige</option>
              <?
					$qryinmueble= "SELECT inm.* FROM inmuebletipo AS inm where inm.caracteristica = 'Tipo de Inmueble' ORDER BY inm.nomtipinmueble ";
					$resinmueble = mysql_query($qryinmueble, $enlace);
					while ($filinmueble = mysql_fetch_array($resinmueble ))
					echo "<option value=\"".$filinmueble["codtipinmueble"]."\">".$filinmueble["nomtipinmueble"]."</option>\n";
					mysql_free_result($resinmueble );
				?>
            </select>
              <br>
              </p>            </td>
          <td colspan="4" valign="middle">Inmueble para <br>
            <select name="cbo2codparaqsi" id="cbo2codparaqsi" title="Inmueble para " class="textonegro" >
              
              
              <option value="0">Elige</option>
              <?
					$qryinmueble= "SELECT * FROM inmuebleparaq ORDER BY codparaq ";
					$resinmueble = mysql_query($qryinmueble, $enlace);
					while ($filinmueble = mysql_fetch_array($resinmueble ))
					echo "<option value=\"".$filinmueble["codparaq"]."\">".$filinmueble["paraq"]."</option>\n";
					mysql_free_result($resinmueble );
				?>
            </select></td>
			
          <td colspan="2" valign="top">Codigo del Inmueble<br>
		  
		  <?php 
		  $qryconse= "SELECT max(codinmueble) as maxcons  FROM inmuebles";
		  $resconse = mysql_query($qryconse, $enlace);
		  $filconse = mysql_fetch_assoc($resconse);
		  
		  $consecreal = $filconse["maxcons"] + 1;
		  
		  if ($consecreal  < 10){ $compl= "00";
		  
		  }
		  
		  if ($consecreal  > 10 && $consecreal  < 99 ){ $compl= "0";}
		  
		  if ($consecreal  > 99){$compl= "";}
		  
		  ?>
		  
            <input name="txt2codigosi" type="text" id="txt2codigosi" title="Codigo del Inmueble" size="15" maxlength="100" value="<?php echo   $tipinm.$compl.$consecreal; ?>" /></td>
          <td colspan="2" valign="middle">Publicar en incio<br>
            <select name="cbo2pubinisi" id="cbo2pubinisi" title="Publica en inicio">
                      <option value="0">Elige</option>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                  </select></td>
          <td colspan="4" valign="middle" >Publicar
            <br>
            <select name="cbo2pubsi" id="cbo2pubsi" title="Publicar">
                      <option value="0">Elige</option>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                  </select></td>
          <td width="172" >&nbsp;</td>
          <td width="180"></td>
          <td width="6"></td>
          <td width="14"></td>
          <td></td>
        </tr>
        
        
        
        
        
        <tr>
          <td height="45"></td>
          <td>&nbsp;</td>
          <td colspan="2" valign="middle"><p>Nombre del Inmueble
            <br>
            <input name="txt1nominmueblesi" type="text" id="txt1nominmueblesi" title="Nombre del Inmueble" size="15"maxlength="100" />
          </p></td>
          <td colspan="5" valign="middle">Direcci&oacute;n del Inmueble <br>
            <input name="txt1dirinmueblesi" type="text" id="txt1dirinmueblesi" title="Dirección del Inmueble" size="28"maxlength="100" />
            <br></td>
          <td colspan="2" valign="middle">Niveles<br>
            <input name="txt1nivelsi" type="text" id="txt1nivelsi" title="N&uacute;mero de pisos" size="10"maxlength="100" /></td>
          <td width="5" >&nbsp;</td>
          <td width="12" >&nbsp;</td>
          <td width="132" >&nbsp;</td>
          <td width="4" >&nbsp;</td>
          <td width="25" >&nbsp;</td>
          <td colspan="5" align="right" valign="top" >Imagen 
                  Inmueble
                  (Ancho: <?php echo $filpub["proyecori"]; ?> px)
                  
                  <input name="hid1imginmueblesi" type="hidden" id="hid1imginmueblesi" title="Nombre del alb&uacute;m" size="10"maxlength="100" />
                  <input name="hid1clickssi" type="hidden" id="hid1clickssi" title="contador del inmueble" value="0" />
                  <input type="hidden" name="hid1codusuadmsi" id="hid1codusuadmsi" value="<?php echo $_SESSION['enlineaadm'];?>">
                  <input name="img1fileno" type="file" id="img1fileno" onChange="nombrefoto()"  title="Imagen inmueble"/></td>
          <td >&nbsp;</td>
          <td >&nbsp;</td>
        </tr>
        
        
        
        <tr>
          <td height="3"></td>
          <td></td>
          <td colspan="2" rowspan="2" valign="middle">Area del inmueble 
            <br>            <input name="txt2areainmueblesi" type="text" id="txt2areainmueblesi" title="Area del Inmueble" size="20"maxlength="100" /></td>
          <td colspan="5" rowspan="2" valign="middle">Numero de Habitaciones <br>
            <input name="txt2numerohabsi" type="text" id="txt2numerohabsi" title="Número de Habitaciones" size="10"maxlength="100" /></td>
          <td colspan="2" rowspan="2" valign="middle">Numero de Ba&ntilde;os <br>
            <input name="txt1numerobansi" type="text" id="txt1numerobansi" title="N&uacute;mero de banos" size="10"maxlength="100" /></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td ></td>
          <td width="22" ></td>
          <td width="50" ></td>
          <td ></td>
          <td ></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="45"></td>
          <td></td>
          <td></td>
          <td colspan="2" valign="top" bgcolor="#666666"><input name='hid1paqno' type='text' id='hid1paqno' title='paqinmueble'  /></td>
          <td></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        
        
        <tr>
          <td height="13"></td>
          <td ></td>
          <td ></td>
          <td width="10" ></td>
          <td width="31" ></td>
          <td width="62" ></td>
          <td width="55" ></td>
          <td width="24"></td>
          <td width="20"></td>
          <td width="9"></td>
          <td width="95"></td>
          <td></td>
          <td></td>
          <td></td>
          <td ></td>
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
          <td height="40"></td>
          <td>&nbsp;</td>
          <td colspan="2" valign="middle" >Pais<br>
            <select name="cbo1cino" class="textonegro" id="cbo1cino" title="Paises" onChange="xajax_departamentos(this.value)">
              <option value="144">COLOMBIA</option>
              <?
					
					$qrypais= "SELECT p.ci, p.cn FROM pais AS p WHERE ci <> 144
					ORDER BY p.cn ";
					$respais = mysql_query($qrypais, $enlace);
					while ($filpais = mysql_fetch_array($respais))
					echo "<option value=\"".$filpais["ci"]."\">".$filpais["cn"]."</option>\n";
					mysql_free_result($respais);
				?>
            </select></td>
          <td colspan="3" valign="middle" id="departamentos" ><span class="textonegro">
            Departamento<br>
            <select name="cbo1coddepno" class="textonegro" id="cbo1coddepno" title="Departamentos"  onChange="xajax_ciudades(this.value)" >
              <option value="0">Elige</option>
              <?
					
					$qrydep= "SELECT d.* FROM deppro AS d WHERE ci = 144
					ORDER BY d.nomdep ";
					$resdep = mysql_query($qrydep, $enlace);
					while ($fildep = mysql_fetch_array($resdep))
					echo "<option value=\"".$fildep["coddep"]."\">".$fildep["nomdep"]."</option>\n";
					mysql_free_result($resdep);
				?>
            </select>
          </span></td>
          <td colspan="4" valign="middle" id="ciudades" ><span class="textonegro">
            Ciudad<br>
            <select name="cbo1codciusi" class="textonegro" id="cbo1codciusi" title="Ciudades" >
              <option value="0">Elige</option>
            </select>
          </span></td>
          <td colspan="4" valign="middle" >Zona<br>
            <span class="textoblanco">
            <select    name="cbo1codzonasi"  class="textonegro" id="cbo1codzonasi" title="Zonas de Ubicacion ">
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
          <td colspan="4" valign="middle" id="barrios" > <span class="textonegro">Barrio</span><span class="textoblanco"><br>
              <input name="txt1barriosi" type="text" id="txt1barriosi" title="Barrio del Inmueble" size="28" maxlength="100" />
                <br>
          </span></td>
          <td >&nbsp;</td>
          <td >&nbsp;</td>
          <td >&nbsp;</td>
          <td></td>
        </tr>
        
        
        
        
        <tr>
          <td height="44"></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="42"></td>
          <td>&nbsp;</td>
          <td colspan="3" valign="top" bgcolor="#CCCCCC" class="titulos">Datos de Contacto<br>
            cliente            <br></td>
          <td colspan="3" valign="middle" style="padding-left:5px">Contacto
            <br>
            <input name="txt1responsablesi" type="text" id="txt1responsablesi" title="Direcci&oacute;n del Inmueble" size="20"maxlength="100" />            <br></td>
          <td colspan="5" valign="middle">Email<br>
            <input name="txt1emailresponsablesi" type="text" id="txt1emailresponsablesi" title="Direcci&oacute;n del Inmueble" size="20"maxlength="100" /></td>
          <td colspan="4" valign="middle">Telefono - Celular <br>
            <input name="txt1telresponsablesi" type="text" id="txt1telresponsablesi" title="Direcci&oacute;n del Inmueble" size="20"maxlength="100" /></td>
          <td>&nbsp;</td>
          <td ></td>
          <td ></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="38"></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        
        

        
        
        
        <tr>
          <td height="32"></td>
          <td colspan="19" valign="top">Descripcion (Obligatorio) </td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="18"></td>
          <td colspan="19" valign="top"><?php
		// Automatically calculates the editor base path based on the _samples directory.
		// This is usefull only for these samples. A real application should use something like this:
		// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
		
		$oFCKeditor = new FCKeditor('txt1desinmueblesi') ;
		$oFCKeditor->BasePath = '../fyles/fckeditor/';
		if (isset($_POST['txt1desinmueblesi'])){
			$oFCKeditor->Value = $_POST['txt1desinmueblesi'] ;
		}
		else
		{
			$oFCKeditor->Value = "" ;
		}
		$oFCKeditor->Create() ;
		?>	</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="27"></td>
          <td>&nbsp;</td>
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
      </table></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="19">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
		</form>
    <!-- InstanceEndEditable --></td>
  </tr>
  
  <tr>
    <td height="32" colspan="2" valign="top"><div align="center" class="textonegro"> Todos los derechos reservados <strong>ADMINWEB</strong> , </div></td>
  </tr>
</table>
</form>
</body>
<!-- InstanceEnd --></html>