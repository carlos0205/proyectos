<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php");
sesion(1);
include("fckeditor/fckeditor.php") ;


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'notcre.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//consulto parametros de publicacion
$qrypub= "SELECT notmin, notori FROM pubpar ";
$respub = mysql_query($qrypub, $enlace);
$filpub = mysql_fetch_assoc($respub);

$qryult = "select max(codpub) as maximo from pubcon";
$result = mysql_query($qryult, $enlace);
$filult= mysql_fetch_assoc($result);
$ultimo = $filult["maximo"] + 1;
?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" type="text/css" media="all" href="calendario_skin/aqua/theme.css" title="Aqua" />
<!-- import the calendar script -->
<script type="text/javascript" src="calendario/calendar.js"></script>
<!-- import the language module -->
<script type="text/javascript" src="calendario/calendar-sp.js"></script>
<!-- other languages might be available in the lang directory; please check
your distribution archive. -->
<script type="text/javascript"  src="general/validaform.js"></script>
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
		document.form1.hid1imgpubsi.value ="<?php echo $ultimo?>."+tipo;
	}else{
		alert("El tipo dearchivo no es permitido");
		document.form1.img1fileno.value="";
		document.form1.hid1imgpubsi.value ="logocli.jpg";
	}
}
<!--
var oldLink = null;
// code to change the active stylesheet


// This function gets called when the end-user clicks on some date.
function selected(cal, date) {
  cal.sel.value = date; // just update the date in the input field.
  if (cal.dateClicked && (cal.sel.id == "sel1" || cal.sel.id == "sel3"))
    // if we add this call we close the calendar on single-click.
    // just to exemplify both cases, we are using this only for the 1st
    // and the 3rd field, while 2nd and 4th will still require double-click.
    cal.callCloseHandler();
}

// And this gets called when the end-user clicks on the _selected_ date,
// or clicks on the "Close" button.  It just hides the calendar without
// destroying it.
function closeHandler(cal) {
  cal.hide();                        // hide the calendar
//  cal.destroy();
  _dynarch_popupCalendar = null;
}

// This function shows the calendar under the element having the given id.
// It takes care of catching "mousedown" signals on document and hiding the
// calendar if the click was outside.
function showCalendar(id, format, showsTime, showsOtherMonths) {
  var el = document.getElementById(id);
  if (_dynarch_popupCalendar != null) {
    // we already have some calendar created
    _dynarch_popupCalendar.hide();                 // so we hide it first.
  } else {
    // first-time call, create the calendar.
    var cal = new Calendar(1, null, selected, closeHandler);
    // uncomment the following line to hide the week numbers
    // cal.weekNumbers = false;
    if (typeof showsTime == "string") {
      cal.showsTime = true;
      cal.time24 = (showsTime == "24");
    }
    if (showsOtherMonths) {
      cal.showsOtherMonths = true;
    }
    _dynarch_popupCalendar = cal;                  // remember it in the global var
    cal.setRange(1900, 2070);        // min/max year allowed.
    cal.create();
  }
  _dynarch_popupCalendar.setDateFormat(format);    // set the specified date format
  _dynarch_popupCalendar.parseDate(el.value);      // try to parse the text in field
  _dynarch_popupCalendar.sel = el;                 // inform it what input field we use

  // the reference element that we pass to showAtElement is the button that
  // triggers the calendar.  In this example we align the calendar bottom-right
  // to the button.
  _dynarch_popupCalendar.showAtElement(el.nextSibling, "Br");        // show the calendar

  return false;
}

var MINUTE = 60 * 1000;
var HOUR = 60 * MINUTE;
var DAY = 24 * HOUR;
var WEEK = 7 * DAY;

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
          <td height="33" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
        <!--DWLayoutTable-->
        <tr>
          <td width="10" height="20"></td>
          <td width="853">&nbsp;</td>
          <td width="30">&nbsp;</td>
          <td width="69" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro(); "><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
          <td width="63" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
          <td width="64" rowspan="3" align="center" valign="middle"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
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
		$ruta_miniaturas = "../publicaciones/mini";
		$ruta_original = "../publicaciones";
								
		//El ancho de la miniatura
		$ancho_miniatura = $filpub["notmin"];
		$ancho_original = $filpub["notori"]; 
		
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
								ImageJpeg ($redimensionada,"$ruta_miniaturas/$nombre_nuevaimg", $filpub["notmin"]);
								ImageDestroy ($redimensionada);
								
							}
							//Subimos la imagen original
							ImageJpeg ($redimensionada1,"$ruta_original/$nombre_nuevaimg", $filpub["notori"]);
							
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
			$siguiente = guardar("pubcon",1,"codpub",2);
			auditoria($_SESSION["enlineaadm"],'Publicaciones',$siguiente,'3');
			echo '<script language = JavaScript>
			location = "not.php";
			</script>';
		}
	}
	if (isset($_POST['aplicarno'])){
		$continua = cargarimagen();
		
		if($continua){
			$siguiente = guardar("pubcon",2,"codpub",2);
			auditoria($_SESSION["enlineaadm"],'Publicaciones',$siguiente,'3');
			?>
			<script language="javascript" type="text/javascript">
			location = "notedi.php?cod=<?php echo $siguiente?>";
			</script>
			<?php
		}			
	}
	//boton cancelar cambios
	if (isset($_POST['cancelarno'])){
		echo '<script language = JavaScript>
		location = "not.php";
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
          <td width="1374" height="23" valign="top" class="titulos"><img src="../images/noticias.png" width="48" height="48" align="absmiddle" /> Publicaci&oacute;n [Crea]  <strong>
            <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
          </strong></td>
        </tr>
      </table></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="156">&nbsp;</td>
          <td valign="top"><table width="100" height="454" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
        <!--DWLayoutTable-->
        <tr>
          <td width="4" height="33"></td>
          <td width="377">&nbsp;</td>
          <td width="412">&nbsp;</td>
          <td width="10">&nbsp;</td>
          <td width="272" rowspan="8" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="151" height="148">&nbsp;</td>
                    <td width="118"></td>
                    <td width="3"></td>
                    </tr>
            <tr>
              <td height="42" colspan="2" valign="top">
                <div align="left"> Imagen 
                  Evento
                  (Ancho: <?php echo $filpub["notori"]; ?> px) 
                  <input name="img1fileno" type="file" id="img1fileno" onChange="nombrefoto()"  title="Imagen publicación"/>
                  </div></td>
                  <td></td>
              </tr>
            <tr>
              <td height="20" colspan="2" valign="top"><input type="hidden" name="hid1codusuadmsi" id="hid1codusuadmsi" value="<?php echo $_SESSION['enlineaadm'];?>">
                <input name="hid1hitssi" type="hidden" id="hid1hitssi" title="Nombre del alb&uacute;m" value="0" size="10"maxlength="100" />
                <input name="hid1imgpubsi" type="hidden" id="hid1imgpubsi" title="Nombre del alb&uacute;m" value="logocli.jpg" size="10"maxlength="100" />
                <input name="hid1feccrepubsi" type="hidden" id="hid1feccrepubsi" title="Nombre del alb&uacute;m" value="<?php echo date("Y-n-j H:i:s")?>" size="10"maxlength="100" /></td>
                <td></td>
              </tr>
            <tr>
              <td height="23">&nbsp;</td>
                <td></td>
                <td></td>
              </tr>
            <!--DWLayoutTable-->
            <!--DWLayoutTable-->
            <tr>
              <td height="24" valign="top">Idioma</td>
                  <td colspan="2" valign="top"><select name="cbo2codidisi" id="cbo2codidisi" title="Idioma">
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
                  </tr>
            <tr>
              <td height="6"></td>
                <td></td>
                <td></td>
              </tr>
            
            <tr>
              <td height="30" valign="top">Publicar en p&aacute;gina de inicio </td>
                    <td valign="top"><select name="cbo2pubinisi" id="cbo2pubinisi" title="Publica en inicio">
                      <option value="0">Elige</option>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                      
                      </select></td>
                    <td></td>
                    </tr>
            <tr>
              <td height="10"></td>
                <td></td>
                <td></td>
              </tr>
            
            <tr>
              <td height="20" valign="top">Publicar</td>
                    <td valign="top"><select name="cbo2pubsi" id="cbo2pubsi" title="Publicar">
                      <option value="0">Elige</option>
                      <option value="Si">Si</option>
                      <option value="No">No</option>
                      
                      </select></td>
                    <td></td>
              </tr>
            <tr>
              <td height="8"></td>
                <td></td>
                <td></td>
              </tr>
            <tr>
              <td height="22" valign="top">Nivel de Acceso </td>
                    <td valign="top"><select name="cbo2codtipusutersi" id="cbo2codtipusutersi" title="Nivel de acceso">
                      <option value="0">Elige</option>
                      <?
				if (isset($_POST['selter'])){
					$ter=$_POST['selter'];
					$qryter = "SELECT * FROM tipusuter WHERE codtipusuter <> '$ter' AND codtipusuter < 3 ORDER BY nomtipusuter ";
					$qryter1 = "SELECT * FROM tipusuter WHERE codtipusuter= '$ter'";
					$rester1 = mysql_query($qryter1,$enlace);
					$filter1 = mysql_fetch_array($rester1);
					echo "<option selected value=\"".$filter1['codtipusuter']."\">".$filter1['nomtipusuter']."</option>\n";
					mysql_free_result($rester1);
				}
				else
				{
					$qryter = "SELECT * FROM tipusuter WHERE codtipusuter < 3 ORDER BY nomtipusuter ";
				}
				$rester = mysql_query($qryter, $enlace);
				while ($filter = mysql_fetch_array($rester))
				echo "<option value=\"".$filter["codtipusuter"]."\">".$filter["nomtipusuter"]."</option>\n";
				mysql_free_result($rester);
			?>
                      </select>				</td>
                    <td></td>
              </tr>
            <!--DWLayoutTable-->
            <tr>
              <td height="25">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td></td>
                    </tr>
            <tr>
              <td height="27" valign="top">Iniciar publicaci&oacute;n </td>
                    <td valign="top"><strong>
                      <input name="txt2fecinipubsi" type="text"  id="txt2fecinipubsi" size="10"  value ="<?php if (isset($_POST['txt2fecinipubsi'])) echo $_POST['txt2fecinipubsi']; ?>"readonly="" title="Fecha de Inicio"
			  ><input type="reset" value=" ... " 
			  onclick="return showCalendar('txt2fecinipubsi', '%Y-%m-%d');" >
                      </strong></td>
                    <td></td>
              </tr>
            <tr>
              <td height="24" valign="top">Finalizar publicaci&oacute;n </td>
                    <td valign="top"><strong>
                      <input name="txt2fecfinpubsi" type="text"  id="txt2fecfinpubsi" size="10" value ="<?php if (isset($_POST['txt2fecfinpubsi'])) echo $_POST['txt2fecfinpubsi']; ?>" readonly="" title="Fecha de finalización"
			  ><input type="reset" value=" ... " 
			  onclick="return showCalendar('txt2fecfinpubsi', '%Y-%m-%d');" >
                      </strong></td>
                    <td></td>
              </tr>
            
            <tr>
              <td height="8"></td>
                <td></td>
                <td></td>
              </tr>
            
            
          </table></td>
          <td width="6">&nbsp;</td>
        </tr>
        <tr>
          <td height="33"></td>
          <td valign="top"><p>T&iacute;tulo
            <input name="txt2nompubsi" type="text" id="txt2nompubsi" size="40" value = "<?php if (isset($_POST['txt2nompubsi'])) echo $_POST['txt2nompubsi']; ?>"maxlength="100" title="Título publicación" />
          </p></td>
          <td valign="top">Secci&oacute;n publicaci&oacute;n 
            <select name="cbo2codtippubsi" id="cbo2codtippubsi" title="Sección publicación">
              <option value="0">Elige</option>
              <?
					if (isset($_POST['seltip'])){
						$tip=$_POST['seltip'];
						$qrytip = "SELECT * FROM tippub WHERE codtippub <> '$tip' ORDER BY nomtippub ";
						$qrytip1 = "SELECT * FROM tippub WHERE codtippub = '$tip' ";
						$restip1 = mysql_query($qrytip1,$enlace);
						$filtip1 = mysql_fetch_array($restip1);
						echo "<option selected value=\"".$filtip1['codtippub']."\">".$filtip1['nomtippub']."</option>\n";
						mysql_free_result($restip1);
					}
					else
					{
						$qrytip = "SELECT * FROM tippub ORDER BY nomtippub ";
					}
					$restip = mysql_query($qrytip, $enlace);
					while ($filtip = mysql_fetch_array($restip))
					echo "<option value=\"".$filtip["codtippub"]."\">".$filtip["nomtippub"]."</option>\n";
					mysql_free_result($restip);
				?>
                  </select></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="17"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        
        
        
        
        
        
        <tr>
          <td height="32"></td>
          <td colspan="2" valign="top">Texto Corto  (Obligatorio) </td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="112"></td>
          <td colspan="2" valign="top"><?php
			// Automatically calculates the editor base path based on the _samples directory.
			// This is usefull only for these samples. A real application should use something like this:
			// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
			
			$oFCKeditor = new FCKeditor('txt1texcorpubsi') ;
			$oFCKeditor->BasePath = '../fyles/fckeditor/';
			
			if (isset($_POST['txt1texcorpubsi'])){
				$oFCKeditor->Value = $_POST['txt1texcorpubsi'] ;
			}
			else
			{
				$oFCKeditor->Value = "" ;
			}
			$oFCKeditor->Create() ;
			?></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="16"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="29">&nbsp;</td>
          <td colspan="2" valign="top" >Texto Extendido  (Opcional) </td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        
        <tr>
          <td height="167">&nbsp;</td>
          <td colspan="2" valign="top"> <?php
		// Automatically calculates the editor base path based on the _samples directory.
		// This is usefull only for these samples. A real application should use something like this:
		// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
		
		$oFCKeditor = new FCKeditor('txt1texextpubsi') ;
		$oFCKeditor->BasePath = '../fyles/fckeditor/';
		if (isset($_POST['txt1texextpubsi'])){
			$oFCKeditor->Value = $_POST['txt1texextpubsi'] ;
		}
		else
		{
			$oFCKeditor->Value = "" ;
		}
		$oFCKeditor->Create() ;
		?></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="13">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
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
    <td height="32" colspan="2" valign="top"><div align="center" class="textonegro"><img src="../images/guacamayo.png" width="40" height="81" align="middle">  <strong>ADMIN-WEB</strong> , </div></td>
  </tr>
</table>
</form>
</body>
<!-- InstanceEnd --></html>