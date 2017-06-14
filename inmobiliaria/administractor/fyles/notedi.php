<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php");
sesion(1);


//XAJAX

//incluímos la clase ajax 
require ('xajax/xajax_core/xajax.inc.php');
//instanciamos el objeto de la clase xajax 
$xajax = new xajax();

$xajax->configure('javascript URI', 'xajax/');


include("fckeditor/fckeditor.php") ;


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'notedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();


//capturo accion a realizar 1=editar 0=actualizar
$acc = $_GET["acc"];
//capturo codigo de evento
$cod = $_GET["cod"];
if ($acc == 0)
{
//capturo estado de publicacion a actualiza 1=publica , 2=publica en inicio

$pub = $_GET["pub"];
$pubini = $_GET["pubini"];

	$qrypub = "UPDATE pubcon SET pub='$pub', pubini='$pubini' WHERE codpub='$cod'";
	$respub=mysql_query($qrypub,$enlace);
	
	echo '<script language = JavaScript>
		location = "not.php";
		</script>';
}
else
{
$qryreg = "SELECT pc.*, tt.nomtipusuter, tp.nomtippub, u.nomusu, i.nomidi, f.nomformato FROM pubcon AS pc
	INNER JOIN tipusuter AS tt ON pc.codtipusuter = tt.codtipusuter
	INNER JOIN tippub AS tp ON pc.codtippub = tp.codtippub
	LEFT JOIN usuadm AS u ON pc.codusuadm = u.codusuadm 
	LEFT JOIN tblformatoinscripcioneve AS f ON pc.codformato = f.codformato
	INNER JOIN idipub AS i ON pc.codidi = i.codidi WHERE pc.codpub = $cod";
$resreg = mysql_query($qryreg, $enlace);
$filreg = mysql_fetch_assoc($resreg);
}


function eliminarinscrito($codinscrito){
	
	global $enlace;
	global $cod;
	
	$qryeli = "DELETE FROM tblformatoinscripcioneveres WHERE codinscrito = $codinscrito ";
	$reseli = mysql_query($qryeli, $enlace);
	return inscritos();
	
}

function inscritos(){
	
	global $enlace;
	global $cod;
	$respuesta = new xajaxResponse();
	
	$salida='<a href="general/crear excel/crearexcelinscritos.php?cod=$cod" target="_blank">Exportar Inscritos <img src="../images/exportexcel.png" alt="Exportar Resultados a Excel" width="32" height="32" border="0" align="absmiddle" /></a>';

			
			$qrypub ="SELECT
					tblformatoinscripcionevepre.*
					FROM
					tblformatoinscripcionevepre 
					INNER JOIN pubcon 
					ON tblformatoinscripcionevepre.codformato = pubcon.codformato
					WHERE pubcon.codpub = $cod";
			$respub = mysql_query($qrypub, $enlace);
			$salida.="<table border=0 cellpadding=0 cellspacing=0 width=100% class='textonegro'>";
			$salida.="<tr align='left' bgcolor='#99CC33'>";
			while($filpub=mysql_fetch_assoc($respub)){
			$salida.="<th>".$filpub["nombrepregunta"]."</th>";
			}
			$salida.="<th>Eliminar</th>";
			$salida.="</tr>";
			
			//consulto inscritos
			$qryins = " SELECT codinscrito FROM tblformatoinscripcioneveres WHERE codpub = $cod
			GROUP BY codinscrito";
			$resins= mysql_query($qryins, $enlace);
			
			$contador = 1;
			
			while($filins = mysql_fetch_assoc($resins)){

			//consulto las respuestas del inscrito
			$qryres="SELECT  i.codinscrito, i.texteva FROM tblformatoinscripcioneveres AS i
					INNER JOIN  tblformatoinscripcionevepre AS p 
					ON i.codpregunta = p.codpregunta 
					WHERE i.codinscrito = ".$filins["codinscrito"]." AND i.codpub = $cod ";
			$resres = mysql_query($qryres, $enlace);
			
			$salida.="<tr onMouseOver=this.style.backgroundColor='#E1EBD8'; class='pointer' onMouseOut=this.style.backgroundColor='#F5F5F5' bgcolor='#F5F5F5'>";
			$contadorres = 1;
			$numres = mysql_num_rows($resres);
			while($filres=mysql_fetch_assoc($resres)){
				
				if($contadorres < $numres){
					$salida.="<td>".$filres["texteva"]."</td>";
				}else{
					$salida.="<td>".$filres["texteva"]."</td>";
					$salida.="<td align='center'><img src='../images/eliminarp2.gif' width='16' height='16' border='0' onclick='eliminarinscrito(".$filres["codinscrito"].")'  </td>";
				}
				$contadorres++;
			}
			
			$salida.="</tr>";
			$contador++;
			
			}
			$salida.="</table>";
	
	$respuesta->assign("inscritos","innerHTML",$salida);
	
	return $respuesta;
	
}

$xajax->registerFunction("eliminarinscrito");
$xajax->registerFunction("inscritos");

//El objeto xajax tiene que procesar cualquier petición 
$xajax->processRequest(); 


//consulto parametros de publicacion
$qrypub= "SELECT notmin, notori FROM pubpar ";
$respub = mysql_query($qrypub, $enlace);
$filpub = mysql_fetch_assoc($respub);

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
function eliminarinscrito(codinscrito){
var entrar = confirm("¿Desea eliminar el registro ?")
	if ( entrar ) 
	{

	xajax_eliminarinscrito(codinscrito);
	}else{
	return false;
	}
}

function mostrar(nombreCapa){ 
	if(document.getElementById(nombreCapa).style.visibility=="visible"){
		document.getElementById(nombreCapa).style.display="none"; 
		document.getElementById(nombreCapa).style.visibility="hidden"; 
		
		document.getElementById("textos").style.display="block"; 
		document.getElementById("textos").style.visibility="visible";

	
	}else{
		document.getElementById(nombreCapa).style.display="block"; 
		document.getElementById(nombreCapa).style.visibility="visible"; 
		
		
		
		document.getElementById("textos").style.display="none"; 
		document.getElementById("textos").style.visibility="hidden";

		
	}
} 

function ocultar(nombreCapa){ 
document.getElementById(nombreCapa).style.display="none"; 
document.getElementById(nombreCapa).style.visibility="hidden"; 
} 


function crearregistro(){
var pasa = validaenvia()

	if(pasa == false ){
		return false;
	}else{
		if(document.form1.cbo2permiteinssi.value=="Si" && document.form1.cbo1codformatosi.value==0){
			alert("Seleccine El formato a utilziar para la inscripción");
			document.form1.cbo1codformatosi.focus();
			return false;
			exit();
		}
		
		var entrar = confirm("¿Desea actualizar el registro?")
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
		document.form1.hid1imgpubsi.value ="<?php echo $cod?>."+tipo;
	}else{
		alert("El tipo de archivo no es permitido");
		document.form1.img1fileno.value="";
		document.form1.hid1imgpubsi.value ="<?php echo $filreg["imgpub"]?>";
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
<style type="text/css">
#inscritos{
	position:relative;
	left: 1px;
	width: 100%;
	height: 400;
	top: 0px;
	overflow: scroll;
}
</style>
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
	global $filreg;
	global $cod;
	
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
		return($continua);
	}//fin if 3
}
	
	if (isset($_POST['guardarno'])){
		$continua = cargarimagen();
		
		if($continua){
				auditoria($_SESSION["enlineaadm"],'Publicaciones',$cod,'4');
				
				//averiguo si cambio el formato de inscripcion, si es asi borro todos los registros que hayn con el formato anterior
				if($filreg["codformato"] <> $_POST["cbo1codformatosi"]){
					$qryeli="DELETE FROM tblformatoinscripcioneveres WHERE codpub = $cod";
					$reseli = mysql_query($qryeli, $enlace);
				}
				
				actualizar("pubcon",2,$cod,"codpub","not.php");
		}
	}
	if (isset($_POST['aplicarno'])){
		$continua = cargarimagen();
		
		if($continua){
			auditoria($_SESSION["enlineaadm"],'Publicaciones',$cod,'4');
			
			//averiguo si cambio el formato de inscripcion, si es asi borro todos los registros que hayn con el formato anterior
				if($filreg["codformato"] <> $_POST["cbo1codformatosi"]){
					$qryeli="DELETE FROM tblformatoinscripcioneveres WHERE codpub = $cod";
					$reseli = mysql_query($qryeli, $enlace);
				}
				
			actualizar("pubcon",2,$cod,"codpub","notedi.php?cod=$cod&acc=1");
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
          <td width="1374" height="23" valign="top" class="titulos"><img src="../images/noticias.png" width="48" height="48" align="absmiddle" /> Publicaci&oacute;n [Edita]  <strong>
            <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
          </strong></td>
        </tr>
      </table></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="156">&nbsp;</td>
          <td valign="top"><table width="100" height="486" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
        <!--DWLayoutTable-->
        <tr>
          <td width="3" height="14"></td>
          <td width="244"></td>
          <td width="110"></td>
          <td width="221"></td>
          <td width="143"></td>
          <td width="5"></td>
          <td width="275" rowspan="8" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="151" height="11"></td>
                    <td width="118"></td>
                    <td width="3"></td>
            </tr>
            <tr>
              <td height="61" colspan="3" align="center" valign="middle" bgcolor="#FFFF99">FOTOGRAFIAS DE PUBLICACI&Oacute;N <br>
                <a href="notfot.php?cod=<?php echo $cod; ?>"><img src="../images/125.png" width="48" height="48" border="0"></a></td>
                    </tr>
            <tr>
              <td height="17"></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="40" colspan="3" align="center" valign="top">FOTOGRAFIA ACTUAL <br>
                <?php
				  if($filreg["imgpub"]<>"logocli.jpg"){
				   echo "<img src=\"../publicaciones/".$filreg["imgpub"]."\" width=\"114\"  />"; 
				   }else{
				   echo "<img src=\"../images/".$filreg["imgpub"]."\" width=\"114\"  />";
				   }
				   ?></td>
            </tr>
            <tr>
              <td height="19">&nbsp;</td>
              <td></td>
              <td></td>
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
              <td height="20" colspan="2" valign="top">
                <input name="hid1imgpubsi" type="hidden" id="hid1imgpubsi" title="Nombre del alb&uacute;m" value="<?php echo $filreg["imgpub"] ?>" size="10"maxlength="100" /></td>
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
                    <option value="<?php echo $filreg["codidi"]?>"><?php echo $filreg["nomidi"]?></option>
                    <?

					$qryidi = "SELECT * FROM idipub WHERE codidi <> ".$filreg["codidi"]." ORDER BY nomidi ";
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
                      <option value="<?php echo $filreg["codtipusuter"]?>"><?php echo $filreg["nomtipusuter"]?></option>
                      <?
				
				$qryter = "SELECT * FROM tipusuter WHERE codtipusuter <> ".$filreg["codtipusuter"]." AND codtipusuter < 3 ORDER BY nomtipusuter ";
				
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
                      <input name="txt2fecinipubsi" type="text"  id="txt2fecinipubsi" size="10"  value ="<?php echo $filreg["fecinipub"] ?>"readonly="" title="Fecha de Inicio"
			  ><input type="reset" value=" ... " 
			  onclick="return showCalendar('txt2fecinipubsi', '%Y-%m-%d');" >
                      </strong></td>
                    <td></td>
              </tr>
            <tr>
              <td height="24" valign="top">Finalizar publicaci&oacute;n </td>
                    <td valign="top"><strong>
                      <input name="txt2fecfinpubsi" type="text"  id="txt2fecfinpubsi" size="10" value ="<?php echo $filreg["fecfinpub"] ?>" readonly="" title="Fecha de finalización"
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
          <td width="22"></td>
        </tr>
        <tr>
          <td height="33"></td>
          <td colspan="2" valign="top"><p>T&iacute;tulo
            <input name="txt2nompubsi" type="text" id="txt2nompubsi" size="40" value = "<?php echo $filreg["nompub"] ?>"maxlength="100" title="Título publicación" />
          </p></td>
          <td colspan="2" valign="top">Secci&oacute;n publicaci&oacute;n
            <select name="cbo2codtippubsi" id="cbo2codtippubsi" title="Sección publicación">
              <option value="<?php echo $filreg["codtippub"]?>"><?php echo $filreg["nomtippub"]?></option>
              <?
					$qrytip = "SELECT * FROM tippub WHERE codtippub <> ".$filreg["codtippub"]." ORDER BY nomtippub ";
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
          <td height="35"></td>
          <td valign="top">Permite Inscripci&oacute;n a publicaci&oacute;n? <br>
            <select name="cbo2permiteinssi" id="cbo2permiteinssi" title="Permite Inscripci&oacute;n">
              <?php
					  $qrypub="SELECT 'Si' AS publica
						UNION
						SELECT 'No' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['permiteins']."\">".$filreg['permiteins']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["permiteins"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					?>
            </select></td>
          <td colspan="2" valign="top">Formato de Inscripci&oacute;n a utilizar<br>
            <select name="cbo1codformatosi" id="cbo1codformatosi" class="textonegro" style="width:300" title="Formato de Inscripci&oacute;n">
              <?php if($filreg["codformato"]<>"" && $filreg["codformato"]<> 0){  
              echo'<option value="'.$filreg["codformato"].'">'.$filreg["nomformato"].'</option>';
			  }else{
			   echo'<option value="0">Elige</option>';
			  }
          
			  	$qryeva = "SELECT f.* FROM tblformatoinscripcioneve AS f 
				WHERE f.codformato <> '".$filreg["codformato"]."'
				ORDER BY f.nomformato";
				$reseva = mysql_query($qryeva, $enlace);
				while($fileva = mysql_fetch_assoc($reseva)){
					echo "<option value = '".$fileva['codformato']."'>".$fileva['nomformato']."</option>";
				}
			  ?>
            </select></td>
          <td align="center" valign="top"><a href="general/crear excel/crearexcelinscritos.php?cod=<?php echo $cod ?>" target="_blank"></a></td>
          <td></td>
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
        </tr>
        <tr>
          <td height="20"></td>
          <td colspan="4" valign="middle" bgcolor="#FFFF99"><a href="#" onClick="mostrar('inscritos')"><img src="../images/clientepotencial.png" width="32" height="32" border="0" align="absmiddle">ver personas inscritas</a> </td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="15"></td>
          <td colspan="4" valign="top"  >
            <div id="inscritos" style="position:relative;width:100%;visibility:hidden; display:none; background-color:#66CCCC">
			<a href="general/crear excel/crearexcelinscritos.php?cod=<?php echo $cod ?>" target="_blank">Exportar Inscritos <img src="../images/exportexcel.png" alt="Exportar Resultados a Excel" width="32" height="32" border="0" align="absmiddle" /></a>
			<?php 
			
			$qrypub ="SELECT
					tblformatoinscripcionevepre.*
					FROM
					tblformatoinscripcionevepre 
					INNER JOIN pubcon 
					ON tblformatoinscripcionevepre.codformato = pubcon.codformato
					WHERE pubcon.codpub = $cod";
			$respub = mysql_query($qrypub, $enlace);
			$salida="<table border=0 cellpadding=0 cellspacing=0 width=100% class='textonegro'>";
			$salida.="<tr align='left' bgcolor='#99CC33'>";
			while($filpub=mysql_fetch_assoc($respub)){
			$salida.="<th>".$filpub["nombrepregunta"]."</th>";
			}
			$salida.="<th>Eliminar</th>";
			$salida.="</tr>";
			
			//consulto inscritos
			$qryins = " SELECT codinscrito FROM tblformatoinscripcioneveres WHERE codpub = $cod
			GROUP BY codinscrito";
			$resins= mysql_query($qryins, $enlace);
			
			$contador = 1;
			
			while($filins = mysql_fetch_assoc($resins)){

			//consulto las respuestas del inscrito
			$qryres="SELECT  i.codinscrito, i.texteva FROM tblformatoinscripcioneveres AS i
					INNER JOIN  tblformatoinscripcionevepre AS p 
					ON i.codpregunta = p.codpregunta 
					WHERE i.codinscrito = ".$filins["codinscrito"]." AND i.codpub = $cod ";
			$resres = mysql_query($qryres, $enlace);
			
			$salida.="<tr onMouseOver=this.style.backgroundColor='#E1EBD8'; class='pointer' onMouseOut=this.style.backgroundColor='#F5F5F5' bgcolor='#F5F5F5'>";
			
			$contadorres = 1;
			$numres = mysql_num_rows($resres);
			 
			while($filres=mysql_fetch_assoc($resres)){
				
				if($contadorres < $numres){
					$salida.="<td>".$filres["texteva"]."</td>";
				}else{
					$salida.="<td>".$filres["texteva"]."</td>";
					$salida.="<td align='center'><img src='../images/eliminarp2.gif' width='16' height='16' border='0' onclick='eliminarinscrito(".$filres["codinscrito"].")'  </td>";
				}
				
				$contadorres++;
				
			}
			
			$salida.="</tr>";
			$contador++;
			
			}
			$salida.="</table>";
			echo $salida;
			?>
				  </div></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="14"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="322"></td>
          <td colspan="4" valign="top"><div id="textos"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="726" height="17" valign="top">Texto Corto  (Obligatorio) </td>
          </tr>
            <tr>
              <td height="112" valign="top"><?php
			// Automatically calculates the editor base path based on the _samples directory.
			// This is usefull only for these samples. A real application should use something like this:
			// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
			$oFCKeditor = new FCKeditor('txt1texcorpubsi') ;
			$oFCKeditor->BasePath = '../fyles/fckeditor/';
			$oFCKeditor->Value = html_entity_decode($filreg["texcorpub"]);
			$oFCKeditor->Create() ;
			
			
			?>             </td>
          </tr>
            <tr>
              <td height="13"></td>
            </tr>
            <tr>
              <td height="13" valign="top" >Texto Extendido  (Opcional) </td>
          </tr>
            <tr>
              <td height="167" valign="top"> <?php
		// Automatically calculates the editor base path based on the _samples directory.
		// This is usefull only for these samples. A real application should use something like this:
		// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
		
		$oFCKeditor = new FCKeditor('txt1texextpubsi') ;
		$oFCKeditor->BasePath = '../fyles/fckeditor/';
		$oFCKeditor->Value = html_entity_decode($filreg["texextpub"]);
		$oFCKeditor->Create() ;
		?>             </td>
          </tr>
          </table></div>          </td>
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