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

//capturo accion a realizar 1=editar 0=actualizar
$acc = $_GET["acc"];
//capturo codigo de evento
$cod = $_GET["cod"];
if ($acc == 0)
{
//capturo estado del inmueble  a actualiza 1=publica , 2=publica en inicio

$pub = $_GET["pub"];
$pubini = $_GET["pubini"];

	$qrypub = "UPDATE inmuebles SET pub='$pub', pubini='$pubini' WHERE codinmueble='$cod'";
	$respub=mysql_query($qrypub,$enlace);
	
	echo '<script language = JavaScript>
		location = "inmuebles.php";
		</script>';
}
else
{

$qryreg = "SELECT
    inmuebles.*
	, inmuebletipo.nomtipinmueble
    , deppro.nomdep
	, deppro.coddep
	, pais.ci
	, pais.cn
    , ciudad.nomciu
    , barrio.nombar
    , zona.nomzona
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
    LEFT JOIN usuadm AS u 
	ON inmuebles.codusuadm = u.codusuadm
    WHERE inmuebles.codinmueble = $cod";
$resreg = mysql_query($qryreg, $enlace);
$filreg = mysql_fetch_assoc($resreg);
}


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
		
			
		var entrar = confirm("¿Desea Actualizar el registro?")
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
		document.form1.hid1imginmueblesi.value ="<?php echo $cod?>."+tipo;
	}else{
		alert("El tipo de archivo no es permitido");
		document.form1.img1fileno.value="";
		document.form1.hid1imginmueblesi.value ="<?php echo $filreg["imginmueble"]?>";
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
	global $filreg;
	global $cod;
	
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
				
							if(file_exists($ruta_original."/".$filreg["imgpub"])){
									//eliminamos la imagen original
									unlink($ruta_original."/".$filreg["imgpub"]);
									unlink($ruta_miniaturas."/".$filreg["imgpub"]);
								}
								
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
								
								$nombre_nuevaimg = $cod.".".$file_ext; 
				
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
		return($continua);
	}//fin if 3
}
	
	
	if (isset($_POST['guardarno'])){
		$continua = cargarimagen();
		
		if($continua){
				auditoria($_SESSION["enlineaadm"],'inmuebles',$cod,'4');
				actualizar("inmuebles",2,$cod,"codinmueble","inmuebles.php");
		}
	}
	if (isset($_POST['aplicarno'])){
		$continua = cargarimagen();
		
		if($continua){
			auditoria($_SESSION["enlineaadm"],'inmuebles',$cod,'4');	
			actualizar("inmuebles",2,$cod,"codinmueble","inmueblesedi.php?cod=$cod&acc=1");
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
          <td width="1374" height="57" valign="top" class="titulos"><img src="../../administractor/images/inmueble.png" width="48" height="48" align="absmiddle"> Inmuebles [Edita]  <strong>
            <script type="text/javascript" language="JavaScript" src="../../administractor/fyles/general/validaform.js"></script>
          </strong></td>
        </tr>
      </table></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="156">&nbsp;</td>
          <td valign="top"><table width="100" height="407" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
        <!--DWLayoutTable-->
        <tr>
          <td width="2" height="16"></td>
          <td width="167"></td>
          <td width="123"></td>
          <td width="154"></td>
          <td width="127"></td>
          <td width="101"></td>
          <td width="62"></td>
          <td width="49"></td>
          <td width="365"></td>
          <td width="19"></td>
        </tr>
        <tr>
          <td height="63"></td>
          <td valign="middle">Codigo del Inmueble <br>
            <input name="txt2codigosi" type="text" id="txt2codigosi" title="codigo del Inmueble" value="<?php echo $filreg["codigo"] ?>" size="20"maxlength="100" /></td>
          <td colspan="2" valign="middle">Valor del Inmueble <br>
            <input name="txt2valorsi" type="text" id="txt2valorsi" title="Valor del Inmueble" value="<?php echo $filreg["valor"] ?>" size="15"maxlength="100" /></td>
          <td valign="middle">Tipo de Inmueble<br>
            <select  name="cbo1codtipinmueblesi"  class="textonegro" id="cbo1codtipinmueblesi" title="Tipo de Inmueble">
              <?
					$qryinmueble= "SELECT inm.* FROM inmuebletipo AS inm ORDER BY inm.nomtipinmueble ";
					$resinmueble = mysql_query($qryinmueble, $enlace);
					
					if($filreg["codtipinmueble"]==0){
							echo'<option value="0">Elige</option>';
						}else{
							echo'<option value="'.$filreg["codtipinmueble"].'">'.$filreg["nomtipinmueble"].'</option>';
						     }
					
					while ($filinmueble = mysql_fetch_array($resinmueble ))
					echo "<option value=\"".$filinmueble["codtipinmueble"]."\">".$filinmueble["nomtipinmueble"]."</option>\n";
					mysql_free_result($resinmueble );
				?>
            </select></td>
          <td valign="middle">Inmueble para <br>
            <select name="cbo2codparaqsi" id="cbo2codparaqsi" title="Inmueble para " class="textonegro">
              <?
					$qryinmueble= "SELECT * FROM inmuebleparaq ORDER BY codparaq ";
					$resinmueble = mysql_query($qryinmueble, $enlace);
					
					if($filreg["codparaq"]==0){
							echo'<option value="0">Elige</option>';
						}else{
							echo'<option value="'.$filreg["codparaq"].'">'.$filreg["paraq"].'</option>';
						     }
					
					while ($filinmueble = mysql_fetch_array($resinmueble ))
					echo "<option value=\"".$filinmueble["codparaq"]."\">".$filinmueble["paraq"]."</option>\n";
					mysql_free_result($resinmueble );
				?>
            </select></td>
          <td valign="middle">Publicar en Inicio<br>
            <select name="cbo2pubinisi" id="cbo2pubinisi" title="Publica en inicio">
              <?php
					  $qrypub="SELECT 'Si' AS publica
						UNION
						SELECT 'No' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['pubini']."\">".$filreg['pubini']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["pubini"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					?>
            </select></td>
          <td valign="middle">Publicar
            <br>
            <select name="cbo2pubsi" id="cbo2pubsi" title="Publicar">
              <?php
					  $qrypub="SELECT 'Si' AS publica
						UNION
						SELECT 'No' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['pub']."\">".$filreg['pub']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["pubini"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
            </select></td>
          <td align="center" valign="top" bgcolor="#FFFF99">FOTOGRAFIAS DE INMUEBLE <br>
            <a href="../../administractor/fyles/inmueblesfot.php?cod=<?php echo $cod; ?>"><img src="../../administractor/images/125.png" width="48" height="48" border="0"></a></td>
          <td></td>
        </tr>
        
        
        
        
        
        
        <tr>
          <td height="48"></td>
          <td valign="middle"><p>Nombre del Inmueble
            <br>
            <input name="txt1nominmueblesi" type="text" id="txt1nominmueblesi" title="Nombre del Inmueble" value="<?php echo $filreg["nominmueble"] ?>" size="20"maxlength="100" />
          </p></td>
          <td colspan="2" valign="middle" >Direcci&oacute;n del Inmueble <br>
            <input name="txt1dirinmueblesi2" type="text" id="txt1dirinmueblesi2" title="Dirección del Inmueble" value="<?php echo $filreg["dirinmueble"] ?>" size="20"maxlength="100" />
            <br></td>
          <td valign="middle" >Niveles<br>
            <input name="txt1nivelsi" type="text" id="txt1nivelsi" title="Pisos del Inmueble" value="<?php echo $filreg["nivel"] ?>" size="20"maxlength="100" /></td>
          <td >&nbsp;</td>
          <td></td>
          <td></td>
          <td rowspan="3" align="center" valign="middle">FOTOGRAFIA ACTUAL <br>
            <?php
				
				   echo "<img src=\"../inmuebles/mini/".$filreg["imginmueble"]."\" width=\"114\"  />"; 
				   
			 ?></td>
          <td></td>
        </tr>
        
        <tr>
          <td height="5"></td>
          <td></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td></td>
        </tr>
        
        
        <tr>
          <td height="48"></td>
          <td valign="middle">Area del inmueble 
            <br>            
            <input name="txt2areainmueblesi" type="text" id="txt2areainmueblesi" title="Area del Inmueble" value="<?php echo $filreg["areainmueble"] ?>" size="20"maxlength="100" /></td>
          <td colspan="2" valign="middle">Num Habitaciones <br>
            <input name="txt2numerohabsi" type="text" id="txt2numerohabsi" title="Número de Habitaciones" value="<?php echo $filreg["numerohab"] ?>" size="10"maxlength="100" /></td>
          <td valign="middle" >Num Ba&ntilde;os 
            <input name="txt1numerobansi" type="text" id="txt1numerobansi" title="N&uacute;mero de banos" value="<?php echo $filreg["numeroban"] ?>" size="10"maxlength="100" /></td>
          <td></td>
          <td ></td>
          <td >&nbsp;</td>
          <td></td>
        </tr>
       
        
        <tr>
          <td height="43"></td>
          <td><!--DWLayoutEmptyCell-->&nbsp;</td>
          <td><!--DWLayoutEmptyCell-->&nbsp;</td>
          <td><!--DWLayoutEmptyCell-->&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td >&nbsp;</td>
          <td >&nbsp;</td>
          <td align="right" valign="top" >Imagen 
                  Inmueble
                  (Ancho: <?php echo $filpub["proyecori"]; ?> px)
                  
                  <input name="hid1imginmueblesi" type="hidden" id="hid1imginmueblesi" title="Nombre del alb&uacute;m" value="<?php echo $filreg["imginmueble"] ?>" size="10"maxlength="100" />
                  <input type="hidden" name="hid1codusuadmsi" id="hid1codusuadmsi" value="<?php echo $_SESSION['enlineaadm'];?>">
                  <input name="img1fileno" type="file" id="img1fileno" onChange="nombrefoto()"  title="Imagen inmueble"/></td>
          <td></td>
        </tr>
        
        <tr>
          <td height="2"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td></td>
        </tr>
        <tr>
          <td height="48"></td>
          <td colspan="2" valign="middle">Pais<br>
            <select  name="cbo1cino" class="textonegro" id="cbo1cino" title="Pa&iacute;s" onChange="xajax_departamentos(this.value)">
              <?php 
	  $qrypais = "SELECT p.ci, p.cn FROM pais AS p WHERE ci <> ".$filreg["ci"]." ORDER BY p.cn";
	  $respais = mysql_query($qrypais, $enlace);
	 	if($filreg["codciu"]==0){
			echo'<option value="0">Elige</option>';
		}else{
			echo'<option value="'.$filreg["ci"].'">'.$filreg["cn"].'</option>';
		}
		while($filpais=mysql_fetch_assoc($respais))
		{
			echo "<option value='".$filpais["ci"]."'>".$filpais["cn"]."</option>";
		}
	  ?>
            </select></td>
          <td colspan="2" valign="middle" id="departamentos"><span class="textonegro">
            Departamento<br>
            <select  name="cbo1coddepno" class="textonegro" id="cbo1coddepno" title="Departamento" onChange="xajax_ciudades(this.value)" >
              <?php
	if($filreg["codciu"]==0){
		echo'<option value="0">Elige</option>';
	}else{
		$qrydep = "SELECT * FROM deppro AS d WHERE ci = ".$filreg["ci"]." AND coddep <> ".$filreg["coddep"]." ORDER BY nomdep";
		$resdep= mysql_query($qrydep, $enlace);
		echo'<option value="'.$filreg["coddep"].'">'.$filreg["nomdep"].'</option>';
		while($fildep=mysql_fetch_assoc($resdep))
		{
			echo "<option value='".$fildep["coddep"]."'>".$fildep["nomdep"]."</option>";
		}
	}
	 
	?>
            </select>
          </span></td>
          <td valign="middle" id="ciudades" ><span class="textonegro">
            Ciudad<br>
            <select  name="cbo1codciusi" class="textonegro" id="cbo1codciusi" title="Ciudad" onChange="xajax_barrios(this.value)">
              <?php
	if($filreg["codciu"]==0){
		echo'<option value="0">Elige</option>';
	}else{
		$qrydep = "SELECT * FROM ciudad AS c WHERE coddep = ".$filreg["coddep"]." AND codciu <> ".$filreg["codciu"]." ORDER BY nomciu";
		$resdep= mysql_query($qrydep, $enlace);
		echo'<option value="'.$filreg["codciu"].'">'.$filreg["nomciu"].'</option>';
		while($fildep=mysql_fetch_assoc($resdep))
		{
			echo "<option value='".$fildep["codciu"]."'>".$fildep["nomciu"]."</option>";
		}
	}
					?>
            </select>
          </span></td>
          <td colspan="2" valign="middle" >Zona<br>            
            <span class="textoblanco">
            <Select    name="cbo2codzonasi"  class="textonegro" id="cbo2codzonasi" title="Zona de la ciudad del Inmueble">
              <option value="<?php echo $filreg["codzona"]?>" ><?php echo $filreg["nomzona"]?></option>
              <?
					
					$qryzona= "SELECT zn.codzona, zn.nomzona FROM zona AS zn WHERE codzona <> ".$filreg["codzona"]." ORDER BY zn.nomzona ";
					$reszona = mysql_query($qryzona, $enlace);
					while ($filzona = mysql_fetch_array($reszona))
					echo "<option value=\"".$filzona["codzona"]."\">".$filzona["nomzona"]."</option>\n";
					mysql_free_result($reszona);
				?>
            </select>
                  </span></td>
          <td valign="middle" id="barrios" > <span class="textonegro">Barrio</span><span class="textoblanco"><br>  
              <Select    name="cbo1codbarsi"  class="textonegro" id="cbo1codbarsi" title="Barrio del Inmueble">
                <option value="0" >Elige</option>
                    </select>
          </span></td>
          <td></td>
        </tr>
        
        
        
        
        <tr>
          <td height="29"></td>
          <td valign="middle" bgcolor="#CCCCCC"><span class="titulos">Datos de Contacto<br>
cliente </span></td>
          <td valign="middle" style="padding-left:7px">Contacto
            <input name="txt1nominmueblesi2" type="text" id="txt1nominmueblesi2" title="Nombre del Inmueble" value="<?php echo $filreg["responsable"] ?>" size="20"maxlength="100" />
            <br></td>
          <td valign="middle">Email<br>
            <input name="txt1nominmueblesi3" type="text" id="txt1nominmueblesi3" title="Nombre del Inmueble" value="<?php echo $filreg["emailresponsable"] ?>" size="20"maxlength="100" /></td>
          <td valign="middle">Telefono - Celular<br>
            <input name="txt1nominmueblesi4" type="text" id="txt1nominmueblesi4" title="Nombre del Inmueble" value="<?php echo $filreg["telresponsable"] ?>" size="20"maxlength="100" /></td>
          <td></td>
          <td ></td>
          <td ></td>
          <td ></td>
          <td></td>
        </tr>
        
        
        
        
        <tr>
          <td height="32"></td>
          <td colspan="8" valign="top">Descripcion (Obligatorio) </td>
          <td></td>
        </tr>
        <tr>
          <td height="18"></td>
          <td colspan="8" valign="top"><?php
		// Automatically calculates the editor base path based on the _samples directory.
		// This is usefull only for these samples. A real application should use something like this:
		// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
		
		$oFCKeditor = new FCKeditor('txt1desinmueblesi') ;
		$oFCKeditor->BasePath = '../fyles/fckeditor/';
		$oFCKeditor->Value = html_entity_decode($filreg["desinmueble"]);
		$oFCKeditor->Create() ;
		?></td>
          <td></td>
        </tr>
        <tr>
          <td height="27"></td>
          <td>&nbsp;</td>
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