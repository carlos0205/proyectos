<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php");
sesion(1);
include("fckeditor/fckeditor.php") ;


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'linedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();
//capturo codigo 
$cod = $_GET["cod"];

$qryreg = "SELECT l.*, ld.nomlin, ld.deslin FROM linneg AS l INNER JOIN linnegdet AS ld ON l.codlin = ld.codlin AND ld.codidi =1 
WHERE l.codlin =$cod";

$respro = mysql_query($qryreg, $enlace);
$filreg = mysql_fetch_assoc($respro);

//consulto parametros de publicacion
$qrypub= "SELECT linmin, linori FROM pubpar ";
$respub = mysql_query($qrypub, $enlace);
$filpub = mysql_fetch_assoc($respub);


?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
<script  language="javascript" type="text/javascript">
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


function eliminaidioma(idioma){
var entrar = confirm("¿Desea eliminar el idioma de la linea?")
		if ( entrar ) 
		{
			location = "lineliidi.php?cod=<?php echo $cod ?>&codidi="+idioma;
		}else{
			return false;
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
		document.form1.hid1imglinsi.value ="<?php echo $cod?>."+tipo;
	}else{
		alert("El tipo dearchivo no es permitido");
		document.form1.img1fileno.value="";
		document.form1.hid1imglinsi.value ="<?php echo $filreg["imglin"]?>";
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
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="61" colspan="2" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="6" height="20">&nbsp;</td>
                  <td width="812">&nbsp;</td>
                  <td width="32">&nbsp;</td>
                  <td width="62" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro(); "><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
                  <td width="62" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
                  <td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="11">&nbsp;</td>
            </tr>
            <tr>
              <td height="19">&nbsp;</td>
              <td valign="top" >Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            
            <tr>
              <td height="26" colspan="2" align="right" valign="top" class="textoerror"><?php
	
function cargarimagen(){
	global $enlace;
	global $filpub;
	global $cod;
	
	$continua = TRUE;

	//Verifico si se inserta imagen de la publicaci&oacute;n
	$file_name = $_FILES['img1fileno']['name'];
	if( $file_name <> ""){ //if 3
		
		$continua = TRUE; 

		//Ruta donde guardamos las im&aacute;genes
		$ruta_miniaturas = "../lineas/mini";
		$ruta_original = "../lineas";
								
		//El ancho de la miniatura
		$ancho_miniatura = $filpub["linmin"];
		$ancho_original = $filpub["linori"]; 
		
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
			echo "&iexcl;El tipo de archivo no es permitido!";
			$continua = FALSE;			  
		} // fin if 5
		if($continua){  //if
			// validar tama&ntilde;o de archivo	   
			if  ($file_size > 8368308) //bytes = 8368308 = 8392kb = 8Mb
			/*Copia el archivo en una directorio espec&iacute;fico del servidor*/
			{ //if 7
				echo "&iexcl;El archivo debe ser inferior a 8MB!";						
				$continua = FALSE;				
			} //fin if 7
			if ($continua){ //if 
			
				if(file_exists($ruta_original."/".$filreg["imglin"])){
					//eliminamos la imagen original
					unlink($ruta_original."/".$filreg["imglin"]);
					unlink($ruta_miniaturas."/".$filreg["imglin"]);
				}
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
						
						$nombre_nuevaimg = $cod.".".$file_ext; 
		
						//guardamos la imagen
						ImageJpeg ($redimensionada,"$ruta_miniaturas/$nombre_nuevaimg", $filpub["linmin"]);
						ImageDestroy ($redimensionada);
						
					}
					//Subimos la imagen original
					ImageJpeg ($redimensionada1,"$ruta_original/$nombre_nuevaimg", $filpub["linori"]);
					
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
	
		$qryregdetact = "UPDATE linnegdet SET nomlin = '".$_POST["txt2nomlinno"]."', deslin = '".$_POST["txt1deslinno"]."' WHERE codlin = '$cod' AND codidi = '1'";
		$resprodetact = mysql_query($qryregdetact, $enlace);
		
		auditoria($_SESSION["enlineaadm"],'Lineas',$cod,'4');
		
		actualizar("linneg",2,$cod,"codlin","lin.php");

	
	}
}
if (isset($_POST['aplicarno'])){
	$continua = cargarimagen();
	
	if($continua){
		$qryregdetact = "UPDATE linnegdet SET nomlin = '".$_POST["txt2nomlinno"]."', deslin = '".$_POST["txt1deslinno"]."' WHERE codlin = '$cod' AND codidi = '1'";
		$resprodetact = mysql_query($qryregdetact, $enlace);
		
		auditoria($_SESSION["enlineaadm"],'Lineas',$cod,'4');
		
		actualizar("linneg",2,$cod,"codlin","linedi.php?cod=$cod&acc=1");

		
	}			
}

							 
//boton cancelar cambios
if (isset($_POST['cancelarno'])){
	echo '<script language = JavaScript>
	location = "lin.php";
	</script>';
}
	
	
$qrytmp ="DELETE FROM tblmaestratemporal WHERE codusu = ".$_SESSION["enlineaadm"]."";
$restmp = mysql_query($qrytmp, $enlace);
				 ?></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
            </tr>
            
              </table></td>
        </tr>
        <tr>
          <td width="4" height="25">&nbsp;</td>
          <td width="1178">&nbsp;</td>
          </tr>
        <tr>
          <td height="52">&nbsp;</td>
          <td valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/linea.png" width="48" height="48" align="absmiddle" /> L&iacute;neas [Edita]  <strong>
                
                </strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="363">&nbsp;</td>
          <td valign="top"><table width="58%" height="302" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="9" height="13"></td>
                  <td width="220"></td>
                  <td width="224"></td>
                  <td width="237"></td>
                  <td width="25"></td>
                  <td width="322"></td>
                  <td width="17"></td>
            </tr>
            <tr>
              <td height="24"></td>
              <td colspan="2" valign="top" ><p>Nombre de L&iacute;nea 
                <input name="txt2nomlinno" type="text" id="txt2nomlinno"  title="Nombre" value="<?php echo $filreg["nomlin"]?>" size="40"maxlength="100"/>
              </p></td>
                  <td valign="top">Hits <?php echo $filreg["hits"]; ?></td>
              <td>&nbsp;</td>
              <td rowspan="5" align="center" valign="top" bgcolor="#FFFF99">IMAGEN ACTUAL<br>
                <?php
				  if($filreg["imglin"]<>"logocli.jpg"){
				   echo "<img src=\"../lineas/mini/".$filreg["imglin"]."\" width=\"114\"  />"; 
				   }else{
				   echo "<img src=\"../images/".$filreg["imglin"]."\" width=\"114\"  />";
				   }
				   ?></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="7"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            

            
            
            
            
            
            <tr>
              <td height="24"></td>
              <td valign="top">
                <label>                </label>                <label>Imagen  (Ancho: <?php echo $filpub["linori"]; ?> px) </label></td>
                  <td colspan="2" valign="top" ><span class="textonegro">
                    <input name="img1fileno" type="file" id="img1fileno" onChange="nombrefoto()"  title="Imagen de linea"/>
                  </span><span class="textonegro">
                  <input name="hid1imglinsi" type="hidden" id="hid1imglinsi" title="Nombre del alb&uacute;m" value="<?php echo $filreg["imglin"]; ?>" size="10"maxlength="100" />
                  </span></td>
                  <td >&nbsp;</td>
                  <td >&nbsp;</td>
            </tr>
            
            
            
            
            
            
            
            <tr>
              <td height="14"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="24"></td>
              <td colspan="3" valign="top" >Descripci&oacute;n de la l&iacute;nea </td>
                  <td></td>
                  <td></td>
            </tr>
            
            <tr>
              <td height="26"></td>
              <td colspan="3" rowspan="2" valign="top"><?php
				// Automatically calculates the editor base path based on the _samples directory.
				// This is usefull only for these samples. A real application should use something like this:
				// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
				
				$oFCKeditor = new FCKeditor('txt1deslinno') ;
				$oFCKeditor->BasePath = '../fyles/fckeditor/';
				$oFCKeditor->Value = html_entity_decode( $filreg["deslin"] ) ;
				$oFCKeditor->Height	= '240';
				$oFCKeditor->Create() ;
				?></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="155"></td>
              <td>&nbsp;</td>
              <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
                <!--DWLayoutTable-->
                <tr>
                  <td width="322" height="24" valign="top"><img src="../images/idilin.png" width="24" height="24" align="absmiddle" /> Otros idiomas para la l&iacute;nea </td>
              </tr>
                <tr>
                  <td height="105" valign="top" bgcolor="#FFFF99"><?php 
						$qrylinidi = "SELECT ld.codlindet, ld.codlin, ld.nomlin, i.nomidi, i.codidi FROM idipub i, linnegdet ld WHERE ld.codlin = '$cod'  AND ld.codidi <> '1' AND ld.codidi = i.codidi";
						$reslinidi = mysql_query($qrylinidi, $enlace);
						$numfil = mysql_num_rows($reslinidi);
						echo "<table width=100% class=textonegro>\n";
						if ($numfil>0){
							echo "<tr bgcolor=\"#FFCC00\" >\n";
							echo "<th >Editar</th><th >Idioma</th><th >Nombre</th><th>Eliminar</th>\n";
							echo "</tr>\n";
						  	while ($fillinidi=mysql_fetch_array($reslinidi)){
						 		echo "<tr>";
						  		echo "<td  align = center><a href=linediidi.php?cod=$cod&codidi=".$fillinidi['codidi']."><img src=\"../images/publish_g.png\" alt=\"Editar idioma de producto \" border =\"0\"></a></td>\n";
						  		echo "<td>".$fillinidi['nomidi']."</td>";
								echo "<td>".$fillinidi['nomlin']."</td>";
						  		echo "<td   align = center><font  size=\"2\" ><img src=\"../images/publish_x.png\" title=\"Eliminar idioma de linea\" onClick='eliminaidioma(".$fillinidi['codidi'].")' border =\"0\" class='pointer'></td>\n";
						  		echo "</tr>";
						  	}
					  	}else{
					  		echo"<td>";
					  		echo "no se ha creado la l&iacute;nea en otros idiomas";
					  		echo"</td>";
					  	}
					   	echo "</table>";
					  	?></td>
              </tr>
                
                <tr>
                  <td height="26" valign="top"><a href="lincreidi.php?cod=<?php echo $cod;?>"><img src="../images/idilincre.png" width="24" height="24" border="0" align="absmiddle" /></a>Agregar Idioma </td>
                </tr>
                
              </table>
              </td>
              <td>&nbsp;</td>
            </tr>
            
            <tr>
              <td height="13"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            
            

          </table></td>
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