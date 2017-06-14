<?php
session_start();
include("general/conexion.php") ;


//XAJAX
//incluímos la clase ajax 
require ('xajax/xajax_core/xajax.inc.php');
//instanciamos el objeto de la clase xajax 
$xajax = new xajax();

$xajax->configure('javascript URI', 'xajax/');

include("general/operaciones.php");


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'intpagedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

$qrypub= "SELECT intpagori FROM pubpar ";
$respub = mysql_query($qrypub, $enlace);
$filpub = mysql_fetch_assoc($respub);

//consulto ultimo registro para nombre de foto
$qryult = "SELECT max(codpagimg) as maximo FROM pagsiteimgdiaria";
$result = mysql_query($qryult, $enlace);
$filult= mysql_fetch_assoc($result);
$ultimo = $filult["maximo"] + 1;


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


include("general/sesion.php");
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
function dias(){

	xajax_dias(xajax.getFormValues("form1"));
}

function validaidioma(){

	xajax_validaidioma(xajax.getFormValues("form1"));
}

function crearregistro(){
var pasa = validaenvia()

	if(pasa == false ){
		return false;
	}else{
		
		if(document.form1.tipo[2].checked == false &&  document.form1.img1fileno.value==""){
			alert("Debe seleccionar la imagen de sección");
			return false;
			exit();
		}
		
		if(document.form1.cbo2manvinsi.value=="Si" && document.form1.txt1urlsi.value==""){
			alert("Debe ingresar la url destino de la imagen de sección");
			return false;
			exit();
		}
			
		var entrar = confirm("¿Desea crear la imagen de sección?")
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
		document.form1.hid1imgpagsi.value ="<?php echo $ultimo?>."+tipo;
	}else{
		alert("El tipo de archivo no es permitido");
		document.form1.img1fileno.value="";
		document.form1.hid1imgpagsi.value ="";
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
	  <form id="form1" name="form1" method="post" action=""  onSubmit="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="61" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="6" height="20"></td>
                  <td width="883">&nbsp;</td>
                  <td width="34">&nbsp;</td>
                  <td width="69" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro(); "><img width="32" src="../images/guardar.png"  /><br>
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
		global $ultimo;
		
		$tipo = $_POST["hid1tipimgsi"]	;
		$continua = TRUE; 
		
		
		
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
								   
							$nombre_nuevaimg = $ultimo.".".$file_ext; 
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
					echo "Seleccione la imagen a cargar";
					}
			
			 }else if($tipo==1){  //else if tipo
				$file_name = $_FILES['img1fileno']['name'];
						if( $file_name == ""){
							echo "Seleccione la animación a cargar";
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
	
									$nombre_nuevaimg = $ultimo.".".$file_ext; 	
									//cargo nuevo archivo
									move_uploaded_file($file_tmp,"$ruta/$nombre_nuevaimg");							
									
									return($continua);
									
								}//fin 11
							}//fin 10
						}//fin 9
	
		}else{
			return($continua);
		}
	}// fin funcion
	
	//boton guardar cambios
	if (isset($_POST['guardarno'])){
		$continua = cargarimagen();
		
		if($continua){
			guardar1("pagsiteimgdiaria",1,"codpagimg",1);
		}

	}
	
	//boton aplicar cambios
	if (isset($_POST['aplicarno'])){
		$continua = cargarimagen();
		
		if($continua){
			guardar1("pagsiteimgdiaria",2,"codpagimg",1);
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
          <td width="1039">&nbsp;</td>
          <td width="9">&nbsp;</td>
        </tr>
        
        <tr>
          <td height="52">&nbsp;</td>
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/imgsecdiaria.png" width="48" height="48" align="absmiddle" />Imagen diaria de secci&oacute;n   [ Crea  <strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
                ]
              </strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="107">&nbsp;</td>
          <td valign="top"><table width="58%" height="205" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="9" height="13"></td>
                <td width="214"></td>
                <td width="75"></td>
                <td width="144"></td>
                <td width="98"></td>
                <td width="509"></td>
                <td width="18"></td>
            </tr>
            <tr>
              <td height="13"></td>
              <td colspan="2" rowspan="2" valign="top"><p>Secci&oacute;n<br>
                <select name="cbo2codpagsi" id="cbo2codpagsi" title="sección del sitio">
                      <option value="0">Elige</option>
                      <?
						$qrypag= "SELECT p.* FROM pagsite p ORDER BY p.nompag ";
						$respag = mysql_query($qrypag, $enlace);
						while ($filpag = mysql_fetch_array($respag))
						echo "<option value=\"".$filpag["codpag"]."\">".$filpag["nompag"]."</option>\n";
						mysql_free_result($respag);
					?>
                    </select>
                <br>
              </p></td>
                  <td rowspan="2" valign="top">Idioma<br>
                    
                      <select name="cbo2codidisi" id="cbo2codidisi" title="Idioma"  onChange="dias()" >
                        <option value="0">Elige</option>
                        <?
			$qryidi = "SELECT * FROM idipub ORDER BY nomidi";
			$residi = mysql_query($qryidi, $enlace);
			while ($filidi = mysql_fetch_array($residi))
			echo "<option value=\"".$filidi["codidi"]."\">".$filidi["nomidi"]."</option>\n";
			mysql_free_result($residi);
			?>
                    </select>
                  </td>
                  <td colspan="2" valign="top">D&iacute;a de la Semana (A continuaci&oacute;n se listan los dias de la semana que a&uacute;n no tienen imagen) </td>
              <td></td>
            </tr>
            <tr>
              <td height="22"></td>
              <td colspan="2" valign="top" id="dias"><label>
                    <select name="cbo2coddiasemanasi" id="cbo2coddiasemanasi" title="publica linea educativa" >
                      <option value="0">Elige</option>
					 
                    </select>
                  </label></td>
              <td></td>
            </tr>
            
            
            <tr>
              <td height="31"></td>
              <td colspan="3" valign="top">
                <label>Imagen de Secci&oacute;n
                <input name="tipo" type="radio" value="1" onClick="javascript:document.form1.hid1tipimgsi.value=1"/>
Flash </label>
                <label>
                <input name="tipo" type="radio" onClick="javascript:document.form1.hid1tipimgsi.value=2" value="2" checked/>
Imagen
<input name="tipo" type="radio" value="3" onClick="javascript:document.form1.hid1tipimgsi.value=3"/>
slider  
(Ancho: <?php echo $filpub["intpagori"]; ?> px) </label></td>
              <td colspan="2" valign="top" >
                    Imagen de secci&oacute;n
                      <input name="img1fileno" type="file" id="img1fileno" title="Imagen de seccion"  onChange="nombrefoto()"/>
                      <input name="hid1imgpagsi" type="hidden" id="hid1imgpagsi" size="10"maxlength="100" title="Nombre del alb&uacute;m" />
                    <input name="hid1tipimgsi" type="hidden" id="hid1tipimgsi" title="Nombre del alb&uacute;m" value="2" size="10"maxlength="100" /></td>
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
            </tr>
            <tr>
              <td height="18"></td>
              <td valign="top">La imagen tiene vinculo? </td>
              <td colspan="3" valign="top">Vinculo de la imagen (ejm: www.sitio.com) </td>
              <td valign="top">Abre ( _blank = Nueva ventana , _parent = misma ventana ) </td>
              <td></td>
            </tr>
            <tr>
              <td height="22"></td>
              <td valign="top"><select name="cbo2manvinsi" id="cbo2manvinsi" title="La imagen tiene vinculo">
                  <option value="0">Elige</option>
                <option value="Si">Si</option>
                <option value="No">No</option>
                                          </select></td>
              <td colspan="3" valign="top"><label>
                <input name="txt1urlsi" type="text" id="txt1urlsi" title="vinculo de la imagen" size="40" maxlength="200">
              </label></td>
              <td valign="top"><select name="cbo1abresi" id="cbo1abresi" title="publica linea educativa">
                <option value="_blank">_blank</option>
                <option value="_parent">_parent</option>
                                                        </select></td>
              <td></td>
            </tr>
            <tr>
              <td height="75"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
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