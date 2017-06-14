<?php 
session_start();
include("../../administractor/fyles/general/paginador.php") ;
include("../../administractor/fyles/general/conexion.php") ;
include("../../administractor/fyles/general/sesion.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'inmueblesedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//capturo codigo de album
$cod = $_GET["cod"];

//mysql_real_escape_string($parametro), que corrije la inserción de caracteres que puedan dar lugar a Inyección SQL, dejando "limpia" la variable que le pasamos
$qryinmueble = mysql_query("SELECT inm.codinmueble,inm.nominmueble, inmtip.nomtipinmueble  FROM inmuebles as inm
LEFT JOIN inmuebletipo as inmtip
ON (inm.codtipinmueble = inmtip.codtipinmueble) 
WHERE inm.codinmueble = $cod", $enlace);
$filinmueble = mysql_fetch_assoc($qryinmueble);


$query_registros = "SELECT finm.* FROM inmueblesvis as finm WHERE finm.codinmueble = '$cod' ORDER BY finm.orden";

include("../../administractor/fyles/general/paginadorinferior.php") ;

//consulto parametros de publicacion
$qrypub= mysql_query("SELECT proyecmin, proyecori FROM pubpar", $enlace);
$filpub = mysql_fetch_assoc($qrypub);
?>
<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../../administractor/js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<script type="text/javascript"  src="../../administractor/fyles/general/validaform.js"></script>
<script type="text/javascript">
function quitar() 
{ 
//alert("No funciona"); 
return false; 
} 
document.oncontextmenu = quitar;
</script>
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
    <td width="100%" valign="bottom" bgcolor="#000000" class="textogris" style="background-image:url(../../administractor/images/fon_adm.png)"><div align="right"><a href="../../administractor/fyles/general/cerrar_sesion.php"><img src="../../administractor/images/cerses.png" alt="Cerrar Ses&oacute;n de Usuario" width="150" height="32" border="0" /></a></div></td>
  </tr>
  <tr>
    <td height="19" colspan="2" valign="top" bgcolor="#F5F5F5"><?php if ($_SESSION["grupo"] == 1){ ?><script type="text/javascript" language="JavaScript1.2" src="../../administractor/js/mnusuperadm.js"></script><?php }else{ ?><script type="text/javascript" language="JavaScript1.2" src="../../administractor/js/mnuadm.js"></script><?php } ?></td>
  </tr>
  <tr>
    <td height="313" colspan="2" valign="top"><!-- InstanceBeginEditable name="contenido" -->
	<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
	<!--DWLayoutTable-->
	<tr>
	<td height="64" colspan="5" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
	<!--DWLayoutTable-->
	<tr>
	<td width="9" height="16"></td>
	<td width="975"></td>
	<td width="25"></td>
	<td width="65" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" ><img width="32" src="../../administractor/images/aplicar.png"  /><br>
                  Aplicar</button></td>
	<td width="68" rowspan="3" align="center" valign="middle" ><button class="textonegro" name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../../administractor/images/eliminar.png"  /><br>
                  Eliminar</button></td>
	<td width="10" ></td>
	</tr>
	<tr>
	  <td height="19"></td>
	  <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
	<td></td>
	  <td ></td>
	  </tr>
	
	<tr>
	<td colspan="2" rowspan="2" valign="top" class="textoerror"><div align="right">
	  <?php
	
				if (isset($_POST['aplicarno'])){
					$resact = mysql_query($query_limit_registros, $enlace);
					while($filact=mysql_fetch_assoc($resact)){
						$codalb =$filact["codinmueblevis"];
						$orden = $_POST['txt'.$codalb];
						$qryact1="UPDATE inmueblesvis SET orden = '$orden', comfot = '".$_POST['txtd'.$codalb]."' WHERE codinmueblevis= '$codalb' ";
						$resact1 = mysql_query($qryact1, $enlace);
					}
					echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	
				}
				//boton guardar cambios
				if (isset($_POST['enviar'])){
					set_time_limit (900);
					$error = 0;
					$arcerror = "";
					for($i=1;$i<6;$i++){
						//Verifico si se inserta imagen de la publicaci&oacute;n
						$file_name = $_FILES['imgfile'.$i]['name'];
						if( $file_name <> ""){
							$continua = TRUE; 
							//Ruta donde guardamos las imágenes
							$ruta_miniaturas = "../inmuebles/vistas/mini";
							$ruta_original = "../inmuebles/vistas";
					
							//El ancho de la miniatura
							$ancho_miniatura = $filpub["proyecmin"];
							$ancho_original = $filpub["proyecori"]; 
							
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
										$qryult = "select max(codinmueblevis) as maximo from inmueblesvis";
										$result = mysql_query($qryult, $enlace);
										$filult= mysql_fetch_array($result);
										$siguiente = $filult["maximo"] + 1;
										$nombre_nuevaimg = $siguiente.".".$file_ext;
						
										//guardamos la imagen
										ImageJpeg ($redimensionada,"$ruta_miniaturas/$nombre_nuevaimg", $filpub["proyecmin"]);
										ImageDestroy ($redimensionada);
									}
									//Subimos la imagen original
									ImageJpeg ($redimensionada1,"$ruta_original/$nombre_nuevaimg", $filpub["proyecori"]);
										
									//move_uploaded_file ($redimensionada1, "$ruta_original/$nombre_nuevaimg");
									ImageDestroy ($redimensionada1);
									ImageDestroy ($nueva_imagen);
													
									//actualizo subgrupo
									$ordenfoto= $_POST["txtorden".$i];
									$comentario= $_POST["txtcomfot".$i];
									$qryfotins=mysql_query("INSERT INTO inmueblesvis VALUES ('0','$cod','$nombre_nuevaimg','$ordenfoto','$comentario')");								
									$resfotins=mysql_query($qryfotins,$enlace);
						
									//refresco contenido
								//	echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	
								}//fin si continua2
							}//fin si continua3
						}
						flush();
					}//fin for
					if($error > 0){
						echo "Algunas fotografias no pudieron cargarse ".$arcerror." <br> este error puede deberse al formato o tama&ntilde;o de los archivos. <br> por favor espere, en 5 segundos esta ventana se refrescar&aacute; con las im&aacute;genes que pudieron cargarse.";
						echo "<META HTTP-EQUIV='refresh' CONTENT='5'>";
					}else{
						echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	
					}
				}
				
				
	if (isset($_POST['eliminar'])){		
		if(!empty($_POST['foto'])) {
			function array_envia($codimg) { 
				$tmp = serialize($codimg); 
				$tmp = urlencode($tmp); 
				return $tmp; 
			} 
			$codimg=array_values($_POST['foto']); 
			$codimg=array_envia($codimg); 
			?>
	  <script type="text/javascript" language="javascript1.2">
			var entrar = confirm("¿Desea Eliminar los registros seleccionados?")
			if(entrar){
				location = "inmueblesfoteli.php?codinmueblevis=<?php echo $codimg?>&codinmueble=<?php echo $cod?>"	
			}
			</script>
	  <?php
		}else{
			echo "Seleccione las fotos que desea eliminar";
		}
	}
	if (isset($_POST['ver'])){
		$_SESSION["numreg"]=$_POST["selnumreg"];	
		echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";		
	}
	
	?>
	  </div></td>
	<td height="23">&nbsp;</td>
	<td >&nbsp;</td>
	</tr>
	<tr>
	  <td height="6"></td>
	  <td></td>
	<td></td>
	<td></td>
	</tr>
	
	</table></td>
	</tr>
	<tr>
	<td width="4" height="25">&nbsp;</td>
	<td width="858">&nbsp;</td>
	<td width="223">&nbsp;</td>
	<td width="4">&nbsp;</td>
	<td width="7">&nbsp;</td>
	</tr>
	<tr>
	<td height="61">&nbsp;</td>
	<td colspan="4" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
	<!--DWLayoutTable-->
	<tr>
	<td width="939" height="52" valign="top" class="titulos"><img src="../../administractor/images/vistas.png" width="48" height="48" align="absmiddle" />Fotografias del Inmueble   <span class="textoerror"><?php echo $filinmueble['nominmueble'];?><strong>
	<script type="text/javascript" language="JavaScript" src="../../administractor/fyles/general/validaform.js"></script>
	</strong></span></td>
	<td width="268" valign="top"><div align="right"><span class="textonegro">Volver al Inmueble <a href="../../administractor/fyles/inmueblesedi.php?cod=<?php echo $cod ?>&acc=1"><img src="../../administractor/images/back.png" width="32" height="32" border="0" align="absmiddle" /></a></span></div></td>
	</tr>
	<tr>
	<td height="9"></td>
	<td></td>
	</tr>
	</table></td>
	</tr>
	<tr>
	<td height="138"></td>
	<td valign="top">Cargar Imagenes (Ancho: <?php echo $filpub["proyecori"]; ?> px) 
	  <br>
	  Imagen 1 
	  <input name="imgfile1" type="file" id="imgfile1" />
	Orden
	<input name="txtorden1" type="text" id="txtorden1"  onKeyPress="onlyDigits(event,'noDec')" size="5" maxlength="5">
	Comentario 
	<input name="txtcomfot1" type="text" id="txtcomfot1" size="60" maxlength="200">
	<br>
	Imagen 2
    <input name="imgfile2" type="file" id="imgfile2" />
Orden
<input name="txtorden2" type="text" size="5" maxlength="5"  onKeyPress="onlyDigits(event,'noDec')">
Comentario
<input name="txtcomfot2" type="text" id="txtcomfot2" size="60" maxlength="200">
<br>
Imagen 3
<input name="imgfile3" type="file" id="imgfile3" />
Orden
<input name="txtorden3" type="text" size="5" maxlength="5"  onKeyPress="onlyDigits(event,'noDec')">
Comentario
<input name="txtcomfot3" type="text" id="txtcomfot3" size="60" maxlength="200">
<br>
Imagen 4
<input name="imgfile4" type="file" id="imgfile4" />
Orden
<input name="txtorden4" type="text" size="5" maxlength="5"  onKeyPress="onlyDigits(event,'noDec')">
Comentario
<input name="txtcomfot4" type="text" id="txtcomfot4" size="60" maxlength="200">
<br>
Imagen 5
<input name="imgfile5" type="file" id="imgfile5" />
Orden
<input name="txtorden5" type="text" size="5" maxlength="5"  onKeyPress="onlyDigits(event,'noDec')">
Comentario
<input name="txtcomfot5" type="text" id="txtcomfot5" size="60" maxlength="200"></td>
	<td align="right" valign="top"><input name="enviar" type="submit" id="enviar" value="Cargar fotos" /></td>
	<td></td>
	<td></td>
	</tr>
	<tr>
	  <td height="27"></td>
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
	  <td></td>
	  <td></td>
	  </tr>
	
	<tr>
	  <td height="167"></td>
	  <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla">
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
	        <input type="checkbox" name="foto[]" value="<?php echo $row_registros['codinmueblevis']; ?>" />
	        <br>
	        Orden<br>
	        <input name="txt<?php echo $row_registros['codinmueblevis'];?>" type="text" id="txt<?php echo $row_registros['codinmueblevis'];?>" onKeyPress="onlyDigits(event,'noDec')" value="<?php echo $row_registros['orden']; ?>" size="4" maxlength="5" >
	        </div></td>
	  <td width="125" align="center" valign="top" class="textonegro"><img src="../inmuebles/vistas/mini/<?php echo $row_registros['imginmueble'];?>"   /><br>
	    <input name="txtd<?php echo $row_registros['codinmueblevis'];?>" type="text" id="txtd<?php echo $row_registros['codinmueblevis'];?>" value="<?php echo $row_registros['comfot']; ?>" size="20" maxlength="200"  ></td>
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
	      <td height="36" colspan="3" valign="top" bgcolor="#FFFFFF" class="textonegro"><?php if($totalRows_registros > 0) { ?>
	            <div align="center"><a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, 0, $queryString_registros); ?>">&lt;&lt;Primero</a> <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, max(0, $pageNum_registros - 1), $queryString_registros); ?>">&lt;Anterior</a> | <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, min($totalPages_registros, $pageNum_registros + 1), $queryString_registros); ?>">Siguiente&gt;</a> <a href="<?php printf("%s?pageNum_registros=%d%s", $currentPage, $totalPages_registros, $queryString_registros); ?>">&Uacute;ltimo&gt;&gt;</a>
	              <?php } ?>
	                  </div></td>
	  </tr>
	    
	    
	      </table></td>
	<td></td>
	  <td></td>
	</tr>
	<tr>
	  <td height="24"></td>
	  <td colspan="3" valign="top" class="textonegro"><div align="center">Ver # 
	    <select name="selnumreg" id="selnumreg" >
	      <option value="1">1</option>
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
    <td height="32" colspan="2" valign="top"><div align="center" class="textonegro">  <strong>ADMIN-WEB</strong> , </div></td>
  </tr>
</table>
</form>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($consulta);
?>