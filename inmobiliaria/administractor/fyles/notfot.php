<?php 
session_start();
include("general/paginador.php") ;
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'notedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//capturo codigo de sucursal
$cod = $_GET["cod"];

$qrynompub = "SELECT p.nompub FROM pubcon p WHERE p.codpub = '$cod' ";
$resnompub = mysql_query($qrynompub, $enlace);
$filnompub = mysql_fetch_assoc($resnompub);


$query_registros= "SELECT pf.* FROM pubconfot pf WHERE pf.codpub = '$cod' ORDER BY orden";

include("general/paginadorinferior.php") ;

//consulto parametros de publicacion
$qrypub= "SELECT fotnotori FROM pubpar ";
$respub = mysql_query($qrypub, $enlace);
$filpub = mysql_fetch_assoc($respub);

?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
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
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
        <!--DWLayoutTable-->
        <tr>
          <td height="63" colspan="6" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="9" height="16"></td>
                  <td width="919"></td>
                  <td width="101"></td>
                  <td width="62" rowspan="3" align="center" valign="middle"><button class="textonegro" name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/eliminar.png"  /><br>
                  Eliminar</button></td>
                  <td width="14"></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top" >Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="28" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php
				if (isset($_POST['guardar'])){
					$resact = mysql_query($query_limit_fotos, $enlace);
					while($filact=mysql_fetch_assoc($resact)){
						$codalb =$filact["codpubfot"];
						$orden = $_POST['txt'.$codalb];
						$qryact1="UPDATE albfotfot SET orden = '$orden', desfot='$des'  WHERE codalbfotimg= '$codalb' ";
						$resact1 = mysql_query($qryact1, $enlace);
					}
					//echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	
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
							//Ruta donde guardamos las im&aacute;genes
							$ruta_miniaturas = "../publicaciones/fotos/mini";
							$ruta_original = "../publicaciones/fotos";
								
							//consulto parametros de publicacion
							$qrypub= "SELECT fotnotmin, fotnotori FROM pubpar ";
							$respub = mysql_query($qrypub, $enlace);
							$filpub = mysql_fetch_assoc($respub);
									
							//El ancho de la miniatura
							$ancho_miniatura = $filpub["fotnotmin"];
							$ancho_original = $filpub["fotnotori"];
							
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
										$qryult = "select max(codpubfot) as maximo from pubconfot";
										$result = mysql_query($qryult, $enlace);
										$filult= mysql_fetch_array($result);
										$siguiente = $filult["maximo"] + 1;
										$nombre_nuevaimg = $siguiente.".".$file_ext;
						
										//guardamos la imagen
										ImageJpeg ($redimensionada,"$ruta_miniaturas/$nombre_nuevaimg", $filpub["fotnotmin"]);
										ImageDestroy ($redimensionada);
									}
									//Subimos la imagen original
									ImageJpeg ($redimensionada1,"$ruta_original/$nombre_nuevaimg", $filpub["fotnotori"]);
										
									//move_uploaded_file ($redimensionada1, "$ruta_original/$nombre_nuevaimg");
									ImageDestroy ($redimensionada1);
									ImageDestroy ($nueva_imagen);
													
									//actualizo subgrupo
									$ordenfoto= $_POST["txtorden".$i];
									$qryfotins="INSERT INTO pubconfot VALUES ('0','$cod','$nombre_nuevaimg','$ordenfoto', '0')";							
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
					var entrar = confirm("&iquest;Desea Eliminar los registros seleccionados?")
					if (entrar){
						location = "notfoteli.php?codpubfot=<?php echo $codimg?>&cod=<?php echo $cod?>"	
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
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td width="4" height="25">&nbsp;</td>
          <td width="432">&nbsp;</td>
          <td width="306">&nbsp;</td>
          <td width="343">&nbsp;</td>
          <td width="4">&nbsp;</td>
          <td width="10">&nbsp;</td>
        </tr>
        <tr>
          <td height="61">&nbsp;</td>
          <td colspan="5" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="939" height="52" valign="top" class="titulos"><img src="../images/vistas.png" width="48" height="48" align="absmiddle" />Publicaci&oacute;n fotografias    <span class="textoerror"><?php echo $filnompub['nompub'];?><strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
              </strong></span></td>
                <td width="211" valign="top"><div align="right"><span class="textonegro">Volver a publicaci&oacute;n <a href="notedi.php?cod=<?php echo $cod ?>&acc=1"><img src="../images/back.png" width="32" height="32" border="0" align="absmiddle" /></a></span></div></td>
                <td width="14">&nbsp;</td>
            </tr>
            <tr>
              <td height="9"></td>
              <td></td>
              <td></td>
            </tr>
              </table></td>
          </tr>
        <tr>
          <td height="24"></td>
          <td valign="top">Cargar Imagen
              <input name="imgfile1" type="file" id="imgfile1" />
Orden
<input name="txtorden1" type="text" size="5" maxlength="5"  onKeyPress="onlyDigits(event,'noDec')">
                              </td>
          <td rowspan="5" valign="top"><p>El ancho de las imagenes es: <?php echo $filpub["fotnotori"]; ?> px</p>            <p>
            <input name="enviar" type="submit" class="botonext" id="enviar" value="Cargar Fotografias" onClick="if (valida_texto(form1.txtorden1.value,'el campo orden')==false) {return false}"/>
            </p></td>
          <td>&nbsp;</td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="24"></td>
          <td valign="top">Cargar Imagen
              <input name="imgfile2" type="file" id="imgfile2" />
Orden
<input name="txtorden2" type="text" size="5" maxlength="5"  onKeyPress="onlyDigits(event,'noDec')">
                                                  </td>
          <td>&nbsp;</td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="24"></td>
          <td valign="top">Cargar Imagen
              <input name="imgfile3" type="file" id="imgfile3" />
Orden
<input name="txtorden3" type="text" size="5" maxlength="5"  onKeyPress="onlyDigits(event,'noDec')">
                                                            </td>
          <td>&nbsp;</td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="24"></td>
          <td valign="top">Cargar Imagen
              <input name="imgfile4" type="file" id="imgfile4" />
Orden
<input name="txtorden4" type="text" size="5" maxlength="5"  onKeyPress="onlyDigits(event,'noDec')">
                    </td>
          <td>&nbsp;</td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="25"></td>
          <td valign="top">Cargar Imagen
              <input name="imgfile5" type="file" id="imgfile5" />
Orden
<input name="txtorden5" type="text" size="5" maxlength="5"  onKeyPress="onlyDigits(event,'noDec')">
                    </td>
          <td>&nbsp;</td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="19"></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="241"></td>
          <td colspan="3" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla">
            <!--DWLayoutTable-->
            <tr>
              <td width="10" height="12"></td>
                <td width="141"></td>
                <td width="1014"></td>
            </tr>
            <tr>
              <td height="114"></td>
              <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" style="width: 100%">
                <!--DWLayoutTable-->
                <tr>
                  <?php if($totalRows_registros > 0){
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
                    <input type="checkbox" name="foto[]" value="<?php echo $row_registros['codpubfot']; ?>" />
                    <br>
                    Orden<br>
                    <input name="txt<?php echo $row_registros['codpubfot'];?>" type="text" size="4" maxlength="5" value="<?php echo $row_registros['orden']; ?>" onKeyPress="onlyDigits(event,'noDec')" >
                    </div></td>
                        <td width="125" valign="top" class="textonegro"><img src="../publicaciones/fotos/mini/<?php echo $row_registros['img'];?>"   /></td>
                                                                                                                            
                    <?php $numero++; } while ($row_registros = mysql_fetch_assoc($consulta)); }?>
                  </tr>
                
                </table></td>
            <td></td>
            </tr>
            <tr>
              <td height="76"></td>
              <td>&nbsp;</td>
              <td></td>
            </tr>
              <tr>
                <td height="37" colspan="3" align="center" valign="top" bgcolor="#FFFFFF" class="textonegro"><?php 
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
          <td></td>
        </tr>
        <tr>
          <td height="24"></td>
          <td colspan="4" valign="top" class="textonegro"><div align="center">Ver # 
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
    <td height="32" colspan="2" valign="top"><div align="center" class="textonegro"><img src="../images/guacamayo.png" width="40" height="81" align="middle">  <strong>ADMIN-WEB</strong> , </div></td>
  </tr>
</table>
</form>
</body>
<!-- InstanceEnd --></html>
<?php
mysql_free_result($consulta);
?>