<?php
session_start();
include("general/conexion.php") ;
include("general/paginador.php") ;
//XAJAX
//incluímos la clase ajax 
require ('xajax/xajax_core/xajax.inc.php');
//instanciamos el objeto de la clase xajax 
$xajax = new xajax();

$xajax->configure('javascript URI', 'xajax/');


include("general/operaciones.php") ;


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'intpagedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();


function dias($form_entrada){
	
	global $enlace;

	$respuesta = new xajaxResponse();
	
	$qrylis = "SELECT d.coddiasemana, d.nomdiasemana  FROM diasemana AS d WHERE d.coddiasemana NOT IN (SELECT coddiasemana FROM pagsiteimgdiaria  WHERE codpag = ".$form_entrada["cbo2codpagsi"]." AND codidi = ".$form_entrada["cbo2codidisi"].") ORDER BY d.coddiasemana";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo2coddiasemanasi' id='cbo2coddiasemanasi' title='Dia de la semana' onChange='validaidioma()' >/n";
	$lista.="<option selected value='0'>Elige</option>\n";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["coddiasemana"]."'>".$fillis["nomdiasemana"]."</option>/n";
	}
	$lista.= "</select>";
	
	
	$respuesta->assign("dias","innerHTML",$lista); 
	
	return $respuesta;
}

function validaidioma($form_entrada){
	
	global $enlace;

	$respuesta = new xajaxResponse();
	
	if($form_entrada["cbo2codidisi"]==0){
		$respuesta->alert("Primero seleccione el idioma al cual desea asignar la imagen de sección");
		$respuesta->assign("cbo2coddiasemanasi","value",0);
	}
	else{
		$qryexi = "SELECT pim.codpagimg  FROM pagsiteimgdiaria AS pim WHERE pim.codpag = ".$form_entrada["cbo2codpagsi"]." AND codidi = ".$form_entrada["cbo2codidisi"]." AND pim.coddiasemana = ".$form_entrada["cbo2coddiasemanasi"]."";
		$resexi = mysql_query($qryexi, $enlace);
		
		if(mysql_num_rows($resexi > 0)){
			$respuesta->alert("La imagen de sección para la página seleccionada ya existe para el idioma seleccionado");
			$respuesta->assign("cbo","innerHTML",$lista); 
			
		}

	}
	
	return $respuesta;
}
$xajax->registerFunction("dias");
$xajax->registerFunction("validaidioma");

//El objeto xajax tiene que procesar cualquier petición 
$xajax->processRequest(); 


$cod = $_GET["cod"];

$qryreg = "SELECT pim.*,d.nomdiasemana, p.nompag, i.nomidi FROM pagsiteimgdiaria AS pim
	INNER JOIN pagsite AS p ON pim.codpag = p.codpag 
	INNER JOIN idipub AS i ON pim.codidi = i.codidi 
	INNER JOIN diasemana AS d ON pim.coddiasemana = d.coddiasemana WHERE pim.codpagimg = $cod ";
$resreg = mysql_query($qryreg, $enlace);
$filreg = mysql_fetch_assoc($resreg);


//valido que haya registros
if(mysql_num_rows($resreg)==0){
	echo '<script language = JavaScript>
	location = "pagsiteimgdiaria.php";
	</script>';
}


//consulto parametros de publicacion
$qrypub= "SELECT intpagori, intpagmin FROM pubpar ";
$respub = mysql_query($qrypub, $enlace);
$filpub = mysql_fetch_assoc($respub);

include("general/sesion.php");
sesion(1);
 
 $query_registros = "SELECT f.* FROM pagsiteimgdiariaslider AS f WHERE f.codpagimg = '$cod' ORDER BY orden";

include("general/paginadorinferior.php") ;
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

function dias(){

	xajax_dias(xajax.getFormValues("form1"));
}

function crearregistro(){
var pasa = validaenvia()

	if(pasa == false ){
		return false;
	}else{
		
		<?php if($filreg["tipimg"]==3){ ?>
		if(document.form1.tipo[2].checked == false &&  document.form1.img1fileno.value==""){
			alert("Debe seleccionar la imagen de sección");
			return false;
			exit();
		}
		<?php } ?>
		
		if(document.form1.cbo2manvinsi.value=="Si" && document.form1.txt1urlsi.value==""){
			alert("Debe ingresar la url destino de la imagen de sección");
			return false;
			exit();
		}
			
		var entrar = confirm("¿Desea actualizar la imagen de sección?")
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
var extenciones = new Array("jpg","jpeg","png","gif","swf");
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
		document.form1.hid1imgpagsi.value ="<?php echo $filreg["codpagimg"]?>."+tipo;
	}else{
		alert("El tipo de archivo no es permitido");
		document.form1.img1fileno.value="";
		document.form1.hid1imgpagsi.value ="";
	}
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
      <table width="100%" height="1156" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="61" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="6" height="20"></td>
                  <td width="883">&nbsp;</td>
                  <td width="35">&nbsp;</td>
                  <td width="60" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro(); "><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
                  <td width="60" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
                  <td width="62" rowspan="3" align="center" valign="middle"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="12"></td>
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
		global $ultimo;
		
		$tipo = $_POST["hid1tipimgsi"]	;
		$continua = TRUE;
		
		if($tipo <> 3){
		
			if($tipo == 2){ // if tipo
			//Verifico si se inserta imagen del subgrupo
			$file_name = $_FILES['img1fileno']['name'];
			
			if($file_name <> ""){
				
					
				//Ruta donde guardamos las imágenes
				$ruta_original = "../../imgsecciondiaria";
				
				//El ancho de la miniatura
				$ancho_original = $filpub["intpagori"]; 
				  
				//Extensiones permitidas
				$extensiones = array(".gif",".jpg",".png",".jpeg");
				$datosarch = $_FILES["img1fileno"];
				$file_type = $_FILES['img1fileno']['type'];
				$file_size = $_FILES['img1fileno']['size'];
				$file_tmp = $_FILES['img1fileno']['tmp_name'];
					 
				//validar la extension
				$ext = strrchr($file_name,'.');
				$ext = strtolower($ext);
				if (!in_array($ext,$extensiones)) {		   
					echo "¡El tipo de archivo no es permitido!";
					$continua = FALSE;	
					return($continua);		  
				}
				if($continua){ //2
					// validar tamaño de archivo	   
					if  ($file_size > 8368308) //bytes = 8368308 = 8392kb = 8Mb
					/*Copia el archivo en una directorio específico del servidor*/
					{
						echo "¡El archivo debe ser inferior a 8MB!";						
						$continua = FALSE;	
						return($continua);			
					}
					if ($continua){ //3
						//Tomamos la extension
						$getExt = explode ('.', $file_name);
						$file_ext = $getExt[count($getExt)-1];  
						$ThumbWidth1 = $ancho_original;
						
						//borro archivo actual
						unlink("../../imgsecciondiaria/".$filreg["imgpag"]);
						
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
								$nuevo_ancho1 = $ThumbWidth1;
								$nuevo_alto1 = $ThumbWidth1/$imgratio;
							}else{
								$nuevo_alto1 = $ThumbWidth1;
								$nuevo_ancho1 = $ThumbWidth1*$imgratio;
							}
							
							$redimensionada1 = imagecreatetruecolor($nuevo_ancho1,$nuevo_alto1);
							imagecopyresized($redimensionada1, $nueva_imagen, 0, 0, 0, 0, $nuevo_ancho1, $nuevo_alto1, $width, $height);
								   
							$nombre_nuevaimg = $filreg["codpagimg"].".".$file_ext; 
						}
						//Subimos la imagen original
						ImageJpeg ($redimensionada1,"$ruta_original/$nombre_nuevaimg", $filpub["intpagori"]);
					
						//move_uploaded_file ($redimensionada1, "$ruta_original/$nombre_nuevaimg");
						ImageDestroy ($redimensionada1);
						ImageDestroy ($nueva_imagen);
						
						return($continua);	
	
					}//fin si continua2
				
				}//fin si continua3
					
			 }	
			  else{
					return($continua);
					}
			
			 }elseif($tipo==1){  //else if tipo
				$file_name = $_FILES['img1fileno']['name'];
						if( $file_name == ""){
							return($continua);
							$continua = FALSE;
						}
						if($continua)
						{//9
							$continua = TRUE;
							
							//Extensiones permitidas
							$extensiones = array(".swf");
							$datosarch = $_FILES["img1fileno"];
							$file_type = $_FILES['img1fileno']['type'];
							$file_size = $_FILES['img1fileno']['size'];
							$file_tmp = $_FILES['img1fileno']['tmp_name'];
								  
							//validar la extension
							$ext = strrchr($file_name,'.');
							$ext = strtolower($ext);
							if (!in_array($ext,$extensiones)) {		   
								echo "¡El tipo de archivo no es permitido solo archivos SWF!";
								$continua = FALSE;			  
							}
							if($continua){ //10
								// validar tamaño de archivo	   
								if  ($file_size > 8368308) //bytes = 8368308 = 8392kb = 8Mb
								/*Copia el archivo en una directorio específico del servidor*/
								{
									echo "¡El archivo debe ser inferior a 8MB!";						
									$continua = FALSE;				
								}
								if ($continua){ //11
									//Tomamos la extension
									$getExt = explode ('.', $file_name);
									$file_ext = $getExt[count($getExt)-1];  
					
									//Ruta donde guardamos los manuales
									$ruta = "../../imgsecciondiaria";
									chmod ($ruta,0777); 
	
									$nombre_nuevaimg = $filreg["codpagimg"].".".$file_ext; 	
									
									
									//borro archivo actual
									unlink("../../imgsecciondiaria/".$filreg["imgpag"]);
									
									
									//cargo nuevo archivo
									move_uploaded_file($file_tmp,"$ruta/$nombre_nuevaimg");							
									
									return($continua);
									
								}//fin 11
							}//fin 10
						}//fin 9
	
			 }
			  else{
					return($continua);
					}
		}else{ //else if tipo
		
	
					if($filreg["tipimg"]<>3){
						$ruta1 ="../../imgsecciondiaria";
						unlink($ruta1."/".$filreg["imgpag"]);
						if ($filreg["tipimg"]==2){
							$ruta1 ="../../imgsecciondiaria/mini";
							unlink($ruta1."/".$filreg["imgpag"]);
						}
						 
					}
					
					$qryfotact="UPDATE pagsiteimgdiaria SET imgpag= '', tipimg=3, url='' WHERE codpagimg = '$cod'";	
						$resfotact=mysql_query($qryfotact,$enlace);
						
					return($continua);

				}
				
			}
	
			//boton guardar cambios
				if (isset($_POST['enviarfotos'])){
					set_time_limit (900);
					$error = 0;
					$arcerror = "";
					for($i=1;$i<4;$i++){
						//Verifico si se inserta imagen de la publicaci&oacute;n
						$file_name = $_FILES['imgfile'.$i]['name'];
						if( $file_name <> ""){
							$continua = TRUE; 
							//Ruta donde guardamos las imágenes
							$ruta_miniaturas = "../../imgsecciondiariaslider/mini";
							$ruta_original = "../../imgsecciondiariaslider";
					
							//El ancho de la miniatura
							$ancho_miniatura = $filpub["intpagmin"];
							$ancho_original = $filpub["intpagori"]; 
							
							//Extensiones permitidas
							$extensiones = array(".gif",".jpg",".png",".jpeg");
							$datosarch = $_FILES["imgfile".$i];
							$file_type = $_FILES['imgfile'.$i]['type'];
							$file_size = $_FILES['imgfile'.$i]['size'];
							$file_tmp = $_FILES['imgfile'.$i]['tmp_name'];
									  
							//validar la extension
							$ext = strrchr($file_name,'.');
							$ext = strtolower($ext);
							if (!in_array($ext,$extensiones)) {		   
								//echo "&iexcl;El tipo de archivo no es permitido!";
								$error ++;
								$arcerror .= "'".$file_name."' ; ";
								$continua = FALSE;			  
							}
							if($continua){ //2
								// validar tama&ntilde;o de archivo	   
								if  ($file_size > 8368308) //bytes = 8368308 = 8392kb = 8Mb
								//Copia el archivo en una directorio espec&iacute;fico del servidor
								{
									//echo "&iexcl;El archivo debe ser inferior a 8MB!";
									$error ++;
									$arcerror .= "'".$file_name."' ; ";					
									$continua = FALSE;				
								}
								if ($continua){ //3
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
						
										//consulto ultimo codigo de fotografia insertado para nombre de la imagen siguiente
										$qryult = "SELECT IFNULL(MAX(codpagintdiaslider),0) AS maximo FROM pagsiteimgdiariaslider";
										$result = mysql_query($qryult, $enlace);
										$filult= mysql_fetch_array($result);
										$siguiente = $filult["maximo"] + 1;
										$nombre_nuevaimg = $siguiente.".".$file_ext;
						
										//guardamos la imagen
										ImageJpeg ($redimensionada,"$ruta_miniaturas/$nombre_nuevaimg", $filpub["intpagmin"]);
										ImageDestroy ($redimensionada);
									}
									//Subimos la imagen original
									ImageJpeg ($redimensionada1,"$ruta_original/$nombre_nuevaimg", $filpub["intpagori"]);
										
									//move_uploaded_file ($redimensionada1, "$ruta_original/$nombre_nuevaimg");
									ImageDestroy ($redimensionada1);
									ImageDestroy ($nueva_imagen);
													
									//actualizo subgrupo
									$ordenfoto= $_POST["txtorden".$i];
									$comentario= $_POST["txtcomfot".$i];
									$manvin= $_POST["cbomanvin".$i];
									$url= $_POST["txturl".$i];
									$abre= $_POST["cboabre".$i];
									
									
									$qryfotins=mysql_query("INSERT INTO pagsiteimgdiariaslider VALUES ('0','$cod','$comentario','$nombre_nuevaimg','$manvin','$url','$abre','$ordenfoto')");								
									$resfotins=mysql_query($qryfotins,$enlace);
						
									//refresco contenido
									echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	
								}//fin si continua2
							}//fin si continua3
						}
						flush();
					}//fin for
					if($error > 0){
						echo "Algunas fotografias no pudieron cargarse ".$arcerror." <br> este error puede deberse al formato o tama&ntilde;o de los archivos. <br> por favor espere, en 5 segundos esta ventana se refrescar&aacute; con las im&aacute;genes que pudieron cargarse.";
						echo "<META HTTP-EQUIV='refresh' CONTENT='5'>";
					}else{
						//echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	
					}
				}
				
				
	if (isset($_POST['eliminar'])){		
		if(!empty($_POST['foto'])) {
			function array_envia($codreg) { 
				$tmp = serialize($codreg); 
				$tmp = urlencode($tmp); 
				return $tmp; 
			} 
			$codreg=array_values($_POST['foto']); 
			$codreg=array_envia($codreg); 
			?>
	  <script type="text/javascript" language="javascript1.2">
			var entrar = confirm("¿Desea Eliminar los registros seleccionados?")
			if(entrar){
				location = "pagsiteimgdiariaslidereli.php?codreg=<?php echo $codreg?>&codpag=<?php echo $cod?>"	
			}
			</script>
	  <?php
		}else{
			echo "Seleccione las imágenes que desea eliminar";
		}
	}
	
	
	
		if (isset($_POST['actualizaslider'])){
		
			$qrysli="SELECT f.* FROM pagsiteimgdiariaslider AS f WHERE f.codpagimg = '$cod' ORDER BY orden";
			$ressli = mysql_query($qrysli, $enlace);
			while($filsli=mysql_fetch_assoc($ressli)){
				
				$qryact = "UPDATE pagsiteimgdiariaslider SET intslider = '".$_POST["txtd".$filsli["codpagintdiaslider"].""]."', manvin='".$_POST["cbomanvinculo".$filsli["codpagintdiaslider"]]."', url='".$_POST["txturls".$filsli["codpagintdiaslider"]]."', abre='".$_POST["cboabrevinculo".$filsli["codpagintdiaslider"]]."',orden='".$_POST["txt".$filsli["codpagintdiaslider"]]."' 
				WHERE codpagintdiaslider = ".$filsli["codpagintdiaslider"]."";
				$resact = mysql_query($qryact, $enlace);	
			}
			
		echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	
			
		}
			/*return($continua);{*/
		
					//boton guardar cambios
					if (isset($_POST['guardarno'])){
						$continua = cargarimagen();
		
						if($continua){
						actualizar(substr(basename($_SERVER['PHP_SELF']),0,-7),2,$cod,"codpagimg","pagsiteimgdiaria.php");
				
						}
					}
					//boton aplicar cambios
					if (isset($_POST['aplicarno'])){
						$continua = cargarimagen();
		
						if($continua){
						actualizar(substr(basename($_SERVER['PHP_SELF']),0,-7),2,$cod,"codpagimg","pagsiteimgdiariaedi.php?cod=$cod");
						}
					}
					
						//boton cancelar cambios
	if (isset($_POST['cancelarno'])){
	echo '<script language = JavaScript>
	location = "pagsiteimgdiaria.php";
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
          <td width="4" height="25">&nbsp;</td>
          <td width="9">&nbsp;</td>
          <td width="1146">&nbsp;</td>
        </tr>
        
        <tr>
          <td height="52">&nbsp;</td>
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/imgsecdiaria.png" width="48" height="48" align="absmiddle" />Imagen diaria de secci&oacute;n  [ Edita <strong>
              <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
]</strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="1004"></td>
          <td></td>
          <td valign="top"><table width="58%" height="966" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="5" height="21"></td>
                  <td width="218"></td>
                  <td width="82"></td>
                  <td width="162"></td>
                  <td width="109"></td>
                  <td width="136"></td>
                  <td width="120"></td>
                  <td width="138"></td>
                  <td width="161"></td>
                  <td width="13"></td>
                </tr>
            <tr>
              <td height="21"></td>
                  <td colspan="2" rowspan="2" valign="top"><p>Secci&oacute;n<br>
                      <select name="cbo2codpagsi" id="cbo2codpagsi"  onChange="dias()" title="sección del sitio">
                        <option value="<?php echo $filreg["codpag"]?>"><?php echo $filreg["nompag"]?></option>
                        <?
						$qrypag= "SELECT p.* FROM pagsite p WHERE p.codpag <> ".$filreg["codpag"]." ORDER BY p.nompag ";
						$respag = mysql_query($qrypag, $enlace);
						while ($filpag = mysql_fetch_array($respag))
						echo "<option value=\"".$filpag["codpag"]."\">".$filpag["nompag"]."</option>\n";
						mysql_free_result($respag);
					?>
                      </select>
                      <br>
                  </p></td>
                  <td rowspan="2" valign="top">Idioma<br>
                      
                      <select name="cbo2codidisi" id="cbo2codidisi" title="Idioma">
                        <option value="<?php echo $filreg["codidi"]?>"><?php echo $filreg["nomidi"]?></option>
                        <?
			$qryidi = "SELECT * FROM idipub WHERE codidi <> ".$filreg["codidi"]." ORDER BY nomidi";
			$residi = mysql_query($qryidi, $enlace);
			while ($filidi = mysql_fetch_array($residi))
			echo "<option value=\"".$filidi["codidi"]."\">".$filidi["nomidi"]."</option>\n";
			mysql_free_result($residi);
			?>
                    </select>                  </td>
                  <td colspan="5" valign="top">D&iacute;a de la Semana (A continuaci&oacute;n se listan los dias de la semana que a&uacute;n no tienen imagen) </td>
                  <td></td>
                </tr>
            <tr>
              <td height="36"></td>
                  <td colspan="5" valign="top" id="dias"><label>
                    <select name="cbo2coddiasemanasi" id="cbo2coddiasemanasi" title="publica dia publicado">
                      <option value="<?php echo $filreg["coddiasemana"]?>"><?php echo $filreg["nomdiasemana"]?></option>
                    </select>
                  </label></td>
                  <td></td>
                </tr>
            
            
            <tr>
              <td height="50"></td>
                  <td colspan="3" valign="top">
                    <label>Imagen de Secci&oacute;n
                    <input name="tipo" type="radio" value="1" onClick="javascript:document.form1.hid1tipimgsi.value=1"  <?php if ($filreg["tipimg"]==1){?>checked="checked"<?php } ?>/>
                    Flash </label>
                    <label>
                    <input name="tipo" type="radio" value="2" onClick="javascript:document.form1.hid1tipimgsi.value=2"  <?php if ($filreg["tipimg"]==2){?>checked="checked"<?php } ?>/>
                        
                    Imagen </label>
                    <input name="tipo" type="radio" value="3" onClick="javascript:document.form1.hid1tipimgsi.value=3"<?php if ($filreg["tipimg"]==3){?>checked="checked"<?php } ?>/>
                    Slider
                      
                  <label>(Ancho: <?php echo $filpub["intpagori"]; ?> px) </label></td>
                  <td colspan="5" valign="top" >
                    Imagen de secci&oacute;n
                    <input name="img1fileno" type="file" id="img1fileno" title="Imagen de seccion"  onChange="nombrefoto()"/>
                    <input name="hid1imgpagsi" type="hidden" id="hid1imgpagsi" title="Nombre del alb&uacute;m" value="<?php echo $filreg["imgpag"]; ?>" size="10"maxlength="100" />
                  <input name="hid1tipimgsi" type="hidden" id="hid1tipimgsi" title="Nombre del alb&uacute;m" value="<?php echo $filreg["tipimg"]; ?>" size="10"maxlength="100" /></td>
                  <td></td>
                </tr>
            <tr>
              <td height="25"></td>
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
              <td height="28"></td>
                  <td valign="top">La imagen tiene vinculo? </td>
                  <td colspan="3" valign="top">Vinculo de la imagen (ejm: www.sitio.com) </td>
                  <td colspan="4" valign="top">Abre ( _blank = Nueva ventana , _parent = misma ventana ) </td>
                  <td></td>
                </tr>
            <tr>
              <td height="36"></td>
                  <td valign="top"><select name="cbo2manvinsi" id="cbo2manvinsi" title="La imagen tiene vinculo">
                      <?php 
				    $qryvin="SELECT 'Si' AS maneja
						UNION
						SELECT 'No' AS maneja";
						$resvin = mysql_query($qryvin, $enlace);
						echo "<option selected value=\"".$filreg['manvin']."\">".$filreg['manvin']."</option>\n";
						while ($filvin = mysql_fetch_array($resvin)){
							if($filvin["maneja"] <> $filreg["manvin"]){
								echo "<option value=\"".$filvin["maneja"]."\">".$filvin["maneja"]."</option>\n";
							}
						}
				  ?>
                      
                  </select></td>
                  <td colspan="3" valign="top"><label>
                    <input name="txt1urlsi" type="text" id="txt1urlsi" title="vinculo de la imagen" value="<?php echo $filreg["url"]; ?>" size="40" maxlength="200">
                  </label></td>
                  <td colspan="4" valign="top"><select name="cbo1abresi" id="cbo1abresi" title="publica linea educativa">
                      
                      <?php 
				    $qryabre="SELECT '_blank' AS abre
						UNION
						SELECT '_parent' AS abre";
						$resabre = mysql_query($qryabre, $enlace);
						echo "<option selected value=\"".$filreg['abre']."\">".$filreg['abre']."</option>\n";
						while ($filabre = mysql_fetch_array($resabre)){
							if($filabre["abre"] <> $filreg["abre"]){
								echo "<option value=\"".$filabre["abre"]."\">".$filabre["abre"]."</option>\n";
							}
						}
				  ?>
                      
                  </select></td>
                  <td></td>
                </tr>
            <tr>
              <td height="21"></td>
                  <td colspan="8" valign="top">Imagen actual </td>
                  <td></td>
                </tr>
            <tr>
              <td height="16"></td>
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
              <td height="46"></td>
              <td colspan="8" valign="top"><?php if ($filreg["tipimg"]==2){ ?>
                    <img src="../../imgsecciondiaria/<?php echo $filreg["imgpag"];?>" >
                    <?php } elseif($filreg["tipimg"]==1) {  
				$datos = GetImageSize('../../imgsecciondiaria/'.$filreg["imgpag"].''); 
				$x = $datos[0]; 
				$y = $datos[1];
				/*$base = 400;
				if ($x > 360){
				$x = $x/$base;
				$y = $y/$x;
				
				}*/
					?>
                    <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase=			"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="<?php echo $x;?>" height="<?php echo $y;?>">
                      <param name="movie" value="../../imgsecciondiaria/<?php echo $filreg["imgpag"];?>">
                      <param name="quality" value="high">
                      <param name="loop" value="false">
                      <embed src="../../imgsecciondiaria/<?php echo $filreg["imgpag"];?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="<?php echo $x;?>" height="<?php echo $y;?>"></embed>
                    </object>
                    <?php
					}?></td>
                  <td></td>
            </tr>
            <tr>
              <td height="357"></td>
              <td colspan="8" valign="top" bgcolor="#FFFFCC"><span class="titulos">Si el tipo de imagen de secci&oacute;n es slider por favor ingrese las im&aacute;genes que hacen parte del slider </span><br>
                    <br>
                    <br>
                    <br>
                    Cargar Imagenes (Ancho: <?php echo $filpub["intpagori"]; ?> px) <br>
                    Imagen 1 
                    &nbsp;&nbsp;&nbsp;
                    <input name="imgfile1" type="file" id="imgfile1" />
                    Orden
                    <input name="txtorden1" type="text" id="txtorden1"  onKeyPress="onlyDigits(event,'noDec')" size="5" maxlength="5">
                    <br>
                    Comentario
                    <input name="txtcomfot1" type="text" id="txtcomfot1" size="60" maxlength="200">
                    tiene vinculo?
                    <select name="cbomanvin1" id="cbomanvin1" title="Publicar">
                      <option value="No">No</option>
                      <option value="Si">Si</option>
                    </select>
                    url
                    <input name="txturl1" type="text" id="txturl1" size="60" maxlength="200">
                    Abre
                    <select name="cboabre1" id="cboabre1" title="Maneja Vinculo">
                      <option value="_parent">_parent</option>
                      <option value="_blank">_blank</option>
                    </select>
                    <br>
                    <br>
                    Imagen 2
                    &nbsp;&nbsp;&nbsp;
                    <input name="imgfile2" type="file" id="imgfile2" />
                    Orden
                    <input name="txtorden2" type="text" size="5" maxlength="5"  onKeyPress="onlyDigits(event,'noDec')">
                    <br>
                    Comentario
                    <input name="txtcomfot2" type="text" id="txtcomfot2" size="60" maxlength="200">
                    tiene vinculo?
                    <select name="cbomanvin2" id="cbomanvin2" title="Publicar">
                      <option value="No">No</option>
                      <option value="Si">Si</option>
                    </select>
                    url
                    <input name="txturl2" type="text" id="txturl2" size="60" maxlength="200">
                    Abre
                    <select name="cboabre2" id="cboabre2" title="Maneja Vinculo">
                      <option value="_parent">_parent</option>
                      <option value="_blank">_blank</option>
                    </select>
                    <br>
                    <br>
                    Imagen 3
                    &nbsp;&nbsp;&nbsp;
                    <input name="imgfile3" type="file" id="imgfile3" />
                    Orden
                    <input name="txtorden3" type="text" size="5" maxlength="5"  onKeyPress="onlyDigits(event,'noDec')">
                    <br>
                    Comentario
                    <input name="txtcomfot3" type="text" id="txtcomfot3" size="60" maxlength="200">
                    tiene vinculo?
                    <select name="cbomanvin3" id="select3" title="Publicar">
                      <option value="No">No</option>
                      <option value="Si">Si</option>
                    </select>
                    url
                    <input name="txturl3" type="text" id="txturl3" size="60" maxlength="200">
                    Abre
                    <select name="cboabre3" id="cboabre3" title="Maneja Vinculo">
                      <option value="_parent">_parent</option>
                      <option value="_blank">_blank</option>
                    </select></td>
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
              <td></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="61"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>&nbsp;</td>
              <td  align="center" valign="middle" ><button class="textonegro" name="enviarfotos" type="submit" value="enviarfotos" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><span class="textonegro" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/upload_f2.png"  /></span><br>
                  Cargar Fotos</button></td>
                  <td  align="center" valign="middle" ><button class="textonegro" name="actualizaslider" type="submit" value="actualizaslider" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/aplicar.png"  /><br>
                  Actualizar Fotos </button></td>
                  <td  align="center" valign="middle" ><button class="textonegro" name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/eliminar.png"  /><br>
                  Eliminar Fotos </button></td>
                  <td></td>
            </tr>
            <tr>
              <td height="197"></td>
              <td colspan="8" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla">
	    <!--DWLayoutTable-->
	    <tr>
	      <td width="7" height="114"></td>
	  <td width="166" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" style="width: 100%">
	    <!--DWLayoutTable-->
	    <tr>
	      <?php
if($totalRows_registros > 0){
		$num=$startRow_registros;
		$numero = 0 ;
		do{
			if($numero == 6){
				$numero = 0;
				echo"<tr>" ;
				echo"<td></td>";
				echo"</tr>" ;
			}
	?>
	      <td width="35" height="114" valign="top" class="textomedio"><div align="center">
	        <input type="checkbox" name="foto[]" value="<?php echo $row_registros['codpagintdiaslider']; ?>" />
	        <br>
	        Orden<br>
	        <input name="txt<?php echo $row_registros['codpagintdiaslider'];?>" type="text" size="4" maxlength="5" value="<?php echo $row_registros['orden']; ?>" onKeyPress="onlyDigits(event,'noDec')" >
	        </div></td>
	  <td width="125" align="center" valign="top" class="textonegro"><img src="../../imgsecciondiariaslider/mini/<?php echo $row_registros['imgslider'];?>"   /><br>
	    Comentario<br>
	    <input name="txtd<?php echo $row_registros['codpagintdiaslider'];?>" type="text" size="20" maxlength="200" value="<?php echo $row_registros['intslider']; ?>"  >
Tiene vinculo<br>
<select name="cbomanvinculo<?php echo $row_registros['codpagintdiaslider']; ?>" id="cbomanvinculo<?php echo $row_registros['codpagintdiaslider']; ?>" title="maneja vinculo">

                  <?php
					  $qryvin="SELECT 'Si' AS publica
						UNION
						SELECT 'No' AS publica";
						$resvin = mysql_query($qryvin, $enlace);
						echo "<option selected value=\"".$row_registros["manvin"]."\">".$row_registros["manvin"]."</option>\n";
						while ($filvin = mysql_fetch_array($resvin)){
							if($filvin["publica"] <> $row_registros["manvin"]){
								echo "<option value=\"".$filvin["publica"]."\">".$filvin["publica"]."</option>\n";
							}
						}
					 ?>
                </select>
<br>
Url
  <input name="txturls<?php echo $row_registros['codpagintdiaslider'];?>" type="text" size="20" maxlength="200" value="<?php echo $row_registros['url']; ?>"  >		
 Abre
 <select name="cboabrevinculo<?php echo $row_registros['codpagintdiaslider']; ?>" id="cboabrevinculo<?php echo $row_registros['codpagintdiaslider']; ?>" title="maneja vinculo">

                  <?php
					  $qryvin="SELECT '_parent' AS publica
						UNION
						SELECT '_blank' AS publica";
						$resvin = mysql_query($qryvin, $enlace);
						echo "<option selected value=\"".$row_registros["abre"]."\">".$row_registros["abre"]."</option>\n";
						while ($filvin = mysql_fetch_array($resvin)){
							if($filvin["publica"] <> $row_registros["abre"]){
								echo "<option value=\"".$filvin["publica"]."\">".$filvin["publica"]."</option>\n";
							}
						}
					 ?>
                </select>  </td>
	  <?php $numero++; } while ($row_registros = mysql_fetch_assoc($consulta)); }?>
	      </tr>
	    </table></td>
	  <td width="911"></td>
	  </tr>
	    <tr>
	      <td height="15"></td>
	  <td></td>
	  <td></td>
	  </tr>
	    <tr>
	      <td height="36" colspan="3" align="center" valign="top" bgcolor="#FFFFFF" class="textonegro"><?php 
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
                  <td></td>
            </tr>
            <tr>
              <td height="35"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td></td>
            </tr>
            
          
            
          </table></td>
          </tr>
        <tr>
          <td height="14"></td>
          <td></td>
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