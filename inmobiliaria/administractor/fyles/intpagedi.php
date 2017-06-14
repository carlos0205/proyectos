<?php
session_start();
include("general/paginador.php") ;
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);
include("fckeditor/fckeditor.php") ;

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'intpagedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();
//variable altura editor
$_SESSION["editorheight"]=300;

$cod=$_GET["cod"];
$titpag = $_GET["titpag"];

//consulto parametros de publicacion
$qrypub= "SELECT intpagori, intpagmin FROM pubpar ";
$respub = mysql_query($qrypub, $enlace);
$filpub = mysql_fetch_assoc($respub);

$query_registros = "SELECT f.* FROM pagsiteintslider AS f WHERE f.codpag = '$cod' ORDER BY orden";

	include("general/paginadorinferior.php") ;
?>





<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<script type="text/javascript"  src="general/validaform.js"></script>
<script language="javascript" type="text/javascript">

function crearregistro(){
	
	var pasa = validaenvia()

	if(pasa == false ){
		return false;
	}else{
	
		var entrar = confirm("¿Desea actualizar el registro?")
		if ( entrar ) 
		{
			return true;	
		}else{
			return false;
		}
	
	}
}
</script>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" -->
<link href="../fckeditor/sample.css" rel="stylesheet" type="text/css" />
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
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
	  <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
        <!--DWLayoutTable-->
        <tr>
          <td height="62" colspan="6" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
        <!--DWLayoutTable-->
        <tr>
          <td width="10" height="35">&nbsp;</td>
          <td width="333">&nbsp;</td>
          <td width="600">&nbsp;</td>
          <td width="21">&nbsp;</td>
          <td width="60" rowspan="2" align="center" valign="middle"><button class="textonegro"   name="volver" type="submit" value="volver" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"  ><img src="../images/volver.png" width="32" height="32"  /><br>
                  Volver</button></td>
          <td width="69" rowspan="2" align="center" valign="middle"><button class="textonegro"   name="enviar" type="submit" value="enviar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"  ><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
          <td width="10">&nbsp;</td>
        </tr>
        <tr>
          <td height="27"></td>
          <td valign="top" class="textonegro">Idioma del contenido <font size="2" face="Arial, Helvetica, sans-serif"><strong>
           <select name="selidi" id="select4">
			<?
			$qryidi = "SELECT * FROM idipub ORDER BY nomidi";
			$residi = mysql_query($qryidi, $enlace);
			while ($filidi = mysql_fetch_array($residi))
			echo "<option value=\"".$filidi["codidi"]."\">".$filidi["nomidi"]."</option>\n";
			mysql_free_result($residi);
			?>
			</select>
			<input name="idioma" type="submit" id="idioma" value="Enviar"/>
			</strong></font></td>
			<td valign="top" class="textoerror"><div align="right">
			  <?php
			//boton seleccion de idioma
			if (isset($_POST['idioma'])){
				$idi =  $_POST["selidi"];
				?>
			  <script language = JavaScript>
				location = "intpagedi.php?cod=<?php echo $cod ?>&idi=<?php echo $idi?>&titpag=<?php echo $titpag?>";	
				</script>
			  <?
			}
			$idi=$_GET["idi"];
				
			//selecciono informacion de compañía en el idioma seleccionado
			$qryint="SELECT p.*,pin.*, t.nomtrasces FROM pagsiteint AS pin 
			INNER JOIN pagsite AS p ON pin.codpag = p.codpag
			INNER JOIN pagsitetransiciones AS t ON p.codtrasc = t.codtrasc
			 WHERE pin.codpag = '$cod' AND pin.codidi = '$idi'";
			
			
			
			$resint=mysql_query($qryint, $enlace);
			$numfilint = mysql_num_rows($resint);
			if ($numfilint < 1)
			{
				$idi = 0;
			}
			$filint=mysql_fetch_assoc($resint);	
			$codpagint = $filint["codpagint"];
					
			//boton guardar cambios
			if (isset($_POST['enviar'])){
				$introduccion = $_POST["txtint"];
				if (get_magic_quotes_gpc()){
					$introduccion = htmlspecialchars( stripslashes( $introduccion ) ) ;
				}else{
					$introduccion = htmlspecialchars( $introduccion ) ;
				}
				//actualizo informacion de la compañía
				$qryhisact="UPDATE pagsiteint SET intpag = '$introduccion', nompag='".$_POST["txt2nompagsi"]."' WHERE codpag = '$cod' AND codidi = '$idi'";
				$reshisact=mysql_query($qryhisact,$enlace);
				
				$qryhisact="UPDATE pagsite SET codtrasc = '".$_POST["cbotransicion"]."',slices='".$_POST["txtrecorrido"]."',animspeed='".$_POST["txtvelocidad"]."' WHERE codpag = '$cod'";
				$reshisact=mysql_query($qryhisact,$enlace);
				
				
				
				 auditoria($_SESSION["enlineaadm"],'Introduccion Pagina',$cod,'4');
				
				
				echo "Información Actualizada";	
				
				//refresco contenido
				echo "<META HTTP-EQUIV='refresh' CONTENT='2'>";	
			}
			if (isset($_POST['volver'])){
				?>
			  <script language="javascript" type="text/javascript">
				location = "intpag.php";
				</script>
			  <?
			}
			if (isset($_POST['cambiar'])){
				$tipo = $_POST["tipo"];
				
			
				//valido tipo de imagen o animacion de seccion
				if($tipo==2){
					//valido si tiene vinculo
					$vinculo = $_POST["vinculo"];
					if($vinculo == 1){
						$abre =  $_POST["abre"];
						$url = $_POST["txturl"];
					}else{
						$abre = "_parent";
						$url = "";
					}
					//Verifico si se inserta imagen de la publicación
					$file_name = $_FILES['imgfile']['name'];
					if( $file_name <> ""){
						$continua = TRUE; 
					
						//Ruta donde guardamos las imágenes
						$ruta_miniaturas = "../../imgseccion/mini";
						$ruta_original = "../../imgseccion";
									
						//consulto parametros de publicacion
						$qrypub= "SELECT intpagmin, intpagori FROM pubpar ";
						$respub = mysql_query($qrypub, $enlace);
						$filpub = mysql_fetch_assoc($respub);
								
						//El ancho de la miniatura
						$ancho_miniatura = $filpub["intpagmin"];
						$ancho_original = $filpub["intpagori"]; 
								  
						//Extensiones permitidas
						$extensiones = array(".gif",".jpg",".png",".jpeg");
						
						$datosarch = $_FILES["imgfile"];
						$file_type = $_FILES['imgfile']['type'];
						$file_size = $_FILES['imgfile']['size'];
						$file_tmp = $_FILES['imgfile']['tmp_name'];
								  
						//validar la extension
						$ext = strrchr($file_name,'.');
						$ext = strtolower($ext);
						if (!in_array($ext,$extensiones)) {		   
							 echo "¡El tipo de archivo no es permitido!";
							 $continua = FALSE;			  
						}
						if($continua){ //2
							   
						    // validar tamaño de archivo	   
							if  ($file_size > 8368308) //bytes = 8368308 = 8392kb = 8Mb
								/*Copia el archivo en una directorio específico del servidor*/{
								echo "¡El archivo debe ser inferior a 8MB!";						
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
										
					
									//consulto 
									$nombre_nuevaimg = $filint["codpagint"].".".$file_ext;
												
									if(file_exists($ruta_original."/".$filint["imgpag"])){
										//eliminamos la imagen original
										if ($filint["tipimg"]<>3){
											unlink($ruta_original."/".$filint["imgpag"]);
										}
										//eliminamos la imagen
										if ($filint["tipimg"]==2){
											unlink($ruta_miniaturas."/".$filint["imgpag"]);	
										}
									}
								   	ImageJpeg ($redimensionada,"$ruta_miniaturas/$nombre_nuevaimg", $filpub["intpagmin"]);
									ImageDestroy ($redimensionada);
								}
								ImageJpeg ($redimensionada1,"$ruta_original/$nombre_nuevaimg", $filpub["intpagori"]);
								ImageDestroy ($redimensionada1);
								ImageDestroy ($nueva_imagen);
				
								//actualizo imagen seccion
								$qryfotact="UPDATE pagsiteint SET imgpag= '$nombre_nuevaimg' ,  tipimg=2, manvin='$vinculo', url='$url', abre='$abre' WHERE codpagint = '$codpagint'";							
								$resfotact=mysql_query($qryfotact,$enlace);
								
								 auditoria($_SESSION["enlineaadm"],'Introduccion Pagina',$cod,'4');
								
								echo "Información actualizada";
							
								//echo $qryfotact;
								echo "<META HTTP-EQUIV='refresh' CONTENT='2'>";	
											
							}//fin si continua2
						}//fin si continua3
					}else{
						$qryfotact="UPDATE pagsiteint SET  tipimg=2, manvin='$vinculo', url='$url', abre='$abre' WHERE codpagint = '$codpagint'";							
						$resfotact=mysql_query($qryfotact,$enlace);
							
						echo "Información actualizada";
							
						echo "<META HTTP-EQUIV='refresh' CONTENT='2'>";	
					}
				}elseif($tipo==1){
					
					//Verifico si se inserta imagen de la publicación
					$file_name = $_FILES['imgfile']['name'];
					if( $file_name <> ""){
						$continua = TRUE;
						
						//Extensiones permitidas
						$extensiones = array(".swf");
							   
						$vinculo = 2;
						$abre = "_parent";
						$url = "";
					
						$datosarch = $_FILES["imgfile"];
						$file_type = $_FILES['imgfile']['type'];
						$file_size = $_FILES['imgfile']['size'];
						$file_tmp = $_FILES['imgfile']['tmp_name'];
							  
						//validar la extension
						$ext = strrchr($file_name,'.');
						$ext = strtolower($ext);
						if (!in_array($ext,$extensiones)) {		   
							echo "¡El tipo de archivo no es permitido solo archivos SWF!";
							$continua = FALSE;			  
						}
						if($continua){ //2
							// validar tamaño de archivo	   
							if  ($file_size > 8368308) //bytes = 8368308 = 8392kb = 8Mb
							/*Copia el archivo en una directorio específico del servidor*/{
								echo "¡El archivo debe ser inferior a 8MB!";						
								$continua = FALSE;				
							}
							if ($continua){ //3
								//Tomamos la extension
								$getExt = explode ('.', $file_name);
								$file_ext = $getExt[count($getExt)-1];  
				
								//Ruta donde guardamos los manuales
								$ruta = "../../imgseccion";
								
								//consulto ultimo codigo de fotografia insertado para nombre de la imagen siguiente
								$nombre_nuevoarc = $filint["codpagint"].".".$file_ext;
								
								//elimino archivo exitente
								if ($filint["tipimg"]<>3){
									unlink($ruta."/".$filint["imgpag"]);
								}
								if ($filint["tipimg"]==2){
									$ruta1 ="../../imgseccion/mini";
									unlink($ruta1."/".$filint["imgpag"]);
								}
								
								//cargo nuevo archivo
								move_uploaded_file($file_tmp,"$ruta/$nombre_nuevoarc");							
				
								//inserto manual
								$qryfotact="UPDATE pagsiteint SET imgpag= '$nombre_nuevoarc', tipimg=1, manvin='$vinculo', url='$url', abre='$abre' WHERE codpagint = '$codpagint'";							
								$resfotact=mysql_query($qryfotact,$enlace);
											
								//refresco contenido
								echo "<META HTTP-EQUIV='refresh' CONTENT='0'>"; 
							}//fin si continua3
						}//fin si continua2
					}else{
						echo "Seleccione la el archivo a cargar";
					}
				}//fin si tipo = 1
				elseif($tipo==3){
					if($filint["tipimg"]<>3 && $filint["tipimg"]<>4){
						$ruta1 ="../../imgseccion";
						unlink($ruta1."/".$filint["imgpag"]);
						if ($filint["tipimg"]==2){
							$ruta1 ="../../imgseccion/mini";
							unlink($ruta1."/".$filint["imgpag"]);
						}
						$qryfotact="UPDATE pagsiteint SET imgpag= '', tipimg=3, url='' WHERE codpagint = '$codpagint'";	
						$resfotact=mysql_query($qryfotact,$enlace);
						
						echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	 
					}
				}
				else{
					if($filint["tipimg"]<>3 && $filint["tipimg"]<>4){
						$ruta1 ="../../imgseccion";
						unlink($ruta1."/".$filint["imgpag"]);
						if ($filint["tipimg"]==2){
							$ruta1 ="../../imgseccion/mini";
							unlink($ruta1."/".$filint["imgpag"]);
						}
						
					}
					
					$qryfotact="UPDATE pagsiteint SET imgpag= '', tipimg=4, url='' WHERE codpagint = '$codpagint'";	
					$resfotact=mysql_query($qryfotact,$enlace);
					
					echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	 

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
							$ruta_miniaturas = "../../imgseccionslider/mini";
							$ruta_original = "../../imgseccionslider";
					
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
										$qryult = "SELECT MAX(codpagintslider) AS maximo FROM pagsiteintslider";
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
									
									
									$qryfotins=mysql_query("INSERT INTO pagsiteintslider VALUES ('0','$cod','$comentario','$nombre_nuevaimg','$manvin','$url','$abre','$ordenfoto')");								
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
				location = "intpagslidereli.php?codreg=<?php echo $codreg?>&codpag=<?php echo $cod?>&idi=<?php echo $_GET["idi"]?>&titpag=<?php echo $_GET["titpag"]?>"	
			}
			</script>
	  <?php
		}else{
			echo "Seleccione las imágenes que desea eliminar";
		}
	}
	
	
	
		if (isset($_POST['actualizaslider'])){
		
			$qrysli="SELECT f.* FROM pagsiteintslider AS f WHERE f.codpag = '$cod' ORDER BY orden";
			$ressli = mysql_query($qrysli, $enlace);
			while($filsli=mysql_fetch_assoc($ressli)){
				
				$qryact = "UPDATE pagsiteintslider SET intslider = '".$_POST["txtd".$filsli["codpagintslider"].""]."', manvin='".$_POST["cbomanvinculo".$filsli["codpagintslider"]]."', url='".$_POST["txturls".$filsli["codpagintslider"]]."', abre='".$_POST["cboabrevinculo".$filsli["codpagintslider"]]."',orden='".$_POST["txt".$filsli["codpagintslider"]]."' 
				WHERE codpagintslider = ".$filsli["codpagintslider"]."";
				$resact = mysql_query($qryact, $enlace);	
			}
			
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
          <td width="7" height="25">&nbsp;</td>
          <td width="767">&nbsp;</td>
          <td width="108">&nbsp;</td>
          <td width="135">&nbsp;</td>
          <td width="134">&nbsp;</td>
          <td width="8">&nbsp;</td>
        </tr>
        <tr>
          <td height="48">&nbsp;</td>
          <td colspan="4" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td width="1374" height="23" valign="top" class="titulos"><img src="../images/intropagina.png" width="48" height="48" align="absmiddle" /> Introducci&oacute;n de p&aacute;gina   <span class="textoerror"><?php echo $titpag; ?> ( <?PHP if ($idi <> 0)
		  { 
		  	//selecciono nombre de idioma
			$qryidi = "SELECT nomidi FROM idipub WHERE codidi = '$idi' ";
			$residi = mysql_query($qryidi, $enlace);
			$filidi = mysql_fetch_array($residi);
		  echo $filidi["nomidi"];
		  }
?> ) </span>[Edita] </td>
        </tr>
      </table></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="337"></td>
          <td colspan="4" valign="top"><table width="100" border="0" cellpadding="0" cellspacing="0" class="textonegro" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="734" height="35" valign="middle">Nombre pagina 
                <label>
                <input name="txt2nompagsi" type="text" id="txt2nompagsi" value="<?php echo $filint["nompag"]; ?>" size="50" maxlength="50">
                </label></td>
              <td width="15">&nbsp;</td>
              <td width="395" rowspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
                  <!--DWLayoutTable-->
                  <tr>
                    <td width="495" height="21" valign="middle" >
                        <label>
                          Imagen secci&oacute;n
tipo:                          
                          <input name="tipo" type="radio" value="1" <?php if ($filint["tipimg"]==1){?>checked="checked"<?php } ?> />
                          Flash                  </label>
                        <label>
                        <input name="tipo" type="radio" value="2" <?php if ($filint["tipimg"]==2){?>checked="checked"<?php } ?> /> 
                          Imagen</label>
                         <input name="tipo" type="radio" value="4" <?php if ($filint["tipimg"]==4){?>checked="checked"<?php } ?> />
Slider
<input name="tipo" type="radio" value="3" <?php if ($filint["tipimg"]==3){?>checked="checked"<?php } ?> />
Ninguna</td>
                      </tr>
                  <tr>
                    <td height="95" valign="top"><?php if ($filint["tipimg"]==2){ ?>
                      <img src="../../imgseccion/mini/<?php echo $filint["imgpag"];?>" >
                      <?php } elseif($filint["tipimg"]==1) {  
				$datos = GetImageSize('../../imgseccion/'.$filint["imgpag"].''); 
				$x = $datos[0]; 
				$y = $datos[1];
				$base = 400;
				if ($x > 360){
				$x = $x/$base;
				$y = $y/$x;
				
				}
					?>
						<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase=			"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="<?php echo $base;?>" height="<?php echo $y;?>">
						  <param name="movie" value="../../imgseccion/<?php echo $filint["imgpag"];?>">
						  <param name="quality" value="high">
						  <param name="loop" value="false">
						  <embed src="../../imgseccion/<?php echo $filint["imgpag"];?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="352" height="72"></embed>
					      </object>
					    <?php
					}?></td>
                      </tr>
                  <tr>
                    <td height="29" valign="top"></td>
                  </tr>
                  <tr>
                    <td height="192" valign="top" ><p>Actualizar imagen  (Ancho: <?php echo $filpub["intpagori"]; ?> px)
                        
                        <input name="imgfile" type="file" id="imgfile" />
                        <br>
                        La imagen lleva vinculo?
                      </p>
                      <label>
                      <input name="vinculo" type="radio" value="1" <?php if ($filint["manvin"]=="Si"){?>checked="checked"<?php } ?> /> 
                      Si</label>
                      <label><input name="vinculo" type="radio" value="2" <?php if ($filint["manvin"]==2){?>checked="checked"<?php } ?> /> 
                      No                      </label>
<br>
                      Vinculo (ingrese la direcci&oacute;n ejm: www.pagina.com)
<input name="txturl" type="text" id="txturl" size="60" maxlength="200" value="<?php echo $filint["url"]; ?>">
                      <label>
                      
<input name="abre" type="radio" value="_blank" <?php if ($filint["abre"]=="_blank"){?>checked="checked"<?php } ?> />
Abre Nueva ventana </label>
                      <label>
                      <input name="abre" type="radio" value="_parent" <?php if ($filint["abre"]=="_parent"){?>checked="checked"<?php } ?> /> 
                      Abre en la misma ventana</label>
                      <br>                      <input name="cambiar" type="submit" id="cambiar" value="Actualizar imagen de secci&oacute;n"/>                      </td>
                    </tr>
                  
                </table></td>
            </tr>
            <tr>
              <td height="302" valign="top"><?php
				// Automatically calculates the editor base path based on the _samples directory.
				// This is usefull only for these samples. A real application should use something like this:
				// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
				
				$oFCKeditor = new FCKeditor('txtint') ;
				$oFCKeditor->BasePath = '../fyles/fckeditor/';
				if ($idi <> 0)
				{
				$oFCKeditor->Value = html_entity_decode( $filint["intpag"] ) ;
				}
				else
				{
				$oFCKeditor->Value = 'POR FAVOR SELECCIONE EL IDIOMA A MODIFICAR'  ;
				}
				$oFCKeditor->Create() ;
				?>
                <p>&nbsp;</p></td>
            <td>&nbsp;</td>
            </tr>
              </table></td>
          <td></td>
        </tr>
        <tr>
          <td height="20"></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td></td>
        </tr>
        <tr>
          <td height="218"></td>
          <td colspan="4" valign="top" bgcolor="#FFFFCC"><span class="titulos">Si el tipo de imagen de secci&oacute;n es slider por favor ingrese las im&aacute;genes que hacen parte del slider<br>
            <br>
            <br>
</span>Transcision del Slider : 
<select name="cbotransicion" id="cbotransicion" title="Transcision del Slider" >
  <option value="<?php echo $filint["codtrasc"]?>"><?php echo $filint["nomtrasces"]?></option>
  <?
			$qrytra = "SELECT * FROM pagsitetransiciones WHERE codtrasc <> ".$filint["codtrasc"]." ORDER BY nomtrasces";
			$restra = mysql_query($qrytra, $enlace);
			while ($filtra= mysql_fetch_array($restra)){
			echo "<option value=\"".$filtra["codtrasc"]."\">".$filtra["nomtrasces"]."</option>\n";
			}
			?>
</select>



Recorrido
<input name="txtrecorrido" type="text" id="txtrecorrido"  onKeyPress="onlyDigits(event,'noDec')" size="5" maxlength="5" value="<?php echo $filint["slices"]?>"> 
Velocidad
<input name="txtvelocidad" type="text" id="txtvelocidad"  onKeyPress="onlyDigits(event,'noDec')" size="5" maxlength="5" value="<?php echo $filint["animspeed"]?>">
 <br>
            <br>
            Cargar Imagenes (Ancho: <?php echo $filpub["intpagori"]; ?> px) 
	        <br>
	  Imagen 1 
	  &nbsp;&nbsp;&nbsp;<input name="imgfile1" type="file" id="imgfile1" />
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
    &nbsp;&nbsp;&nbsp;<input name="imgfile2" type="file" id="imgfile2" />
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
&nbsp;&nbsp;&nbsp;<input name="imgfile3" type="file" id="imgfile3" />
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
</select>
<br></td>
          <td></td>
        </tr>
        
        
       
        <tr>
          <td height="8"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="64"></td>
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
          <td height="9"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        
        
        
        
        
        
        
        
        
        
        <tr>
          <td height="216"></td>
          <td colspan="4" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla">
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
	        <input type="checkbox" name="foto[]" value="<?php echo $row_registros['codpagintslider']; ?>" />
	        <br>
	        Orden<br>
	        <input name="txt<?php echo $row_registros['codpagintslider'];?>" type="text" size="4" maxlength="5" value="<?php echo $row_registros['orden']; ?>" onKeyPress="onlyDigits(event,'noDec')" >
	        </div></td>
	  <td width="125" align="center" valign="top" class="textonegro"><img src="../../imgseccionslider/mini/<?php echo $row_registros['imgslider'];?>"   /><br>
	    Comentario<br>
	    <input name="txtd<?php echo $row_registros['codpagintslider'];?>" type="text" size="20" maxlength="200" value="<?php echo $row_registros['intslider']; ?>"  >
Tiene vinculo<br>
<select name="cbomanvinculo<?php echo $row_registros['codpagintslider']; ?>" id="cbomanvinculo<?php echo $row_registros['codpagintslider']; ?>" title="maneja vinculo">

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
  <input name="txturls<?php echo $row_registros['codpagintslider'];?>" type="text" size="20" maxlength="200" value="<?php echo $row_registros['url']; ?>"  >		
 Abre
 <select name="cboabrevinculo<?php echo $row_registros['codpagintslider']; ?>" id="cboabrevinculo<?php echo $row_registros['codpagintslider']; ?>" title="maneja vinculo">

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
	    
	    
	      </table>          </td>
          <td></td>
        </tr>
        <tr>
          <td height="60"></td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td></td>
        </tr>
		</form>
      </table>
    <!-- InstanceEndEditable --></td>
  </tr>
  
  <tr>
    <td height="32" colspan="2" valign="top"><div align="center" class="textonegro"><img src="../images/guacamayo.png" width="40" height="81" align="middle">  <strong>ADMIN-WEB</strong> , </div></td>
  </tr>
</table>
</form>
</body>
<!-- InstanceEnd --></html>