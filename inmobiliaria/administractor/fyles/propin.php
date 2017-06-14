<?php 
session_start();
include("general/paginador.php") ;
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'proedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//capturo codigo de sucursal
$cod = $_GET["cod"];

$qrynompro = "SELECT pd.nompro FROM pro p,  prodet pd WHERE p.codpro = '$cod' AND p.codpro = pd.codpro AND pd.codidi = 1";
$resnompro = mysql_query($qrynompro, $enlace);
$filnompro = mysql_fetch_assoc($resnompro);

$currentPage = $_SERVER["PHP_SELF"];

$query_registros = "SELECT pp.* FROM propin pp WHERE pp.codpro = '$cod' ";

include("general/paginadorinferior.php") ;

//consulto parametros de publicacion
$qrypub= "SELECT provisori, provismin FROM pubpar";
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
          <td height="63" colspan="4" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="10" height="16"></td>
                  <td width="1086"></td>
                  <td width="12"></td>
                  <td width="68" rowspan="3" align="center" valign="middle" ><button class="textonegro" name="eliminar" type="submit" value="eliminar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/eliminar.png"  /><br>
                  Eliminar</button></td>
                  <td width="12"></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
              <td></td>
            </tr>
            
            
            <tr>
              <td height="28" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php
//boton guardar cambios
if (isset($_POST['enviar']))
{

//Verifico si se inserta imagen de la publicación
		 $file_name = $_FILES['imgfile']['name'];
		 $file_name1 = $_FILES['imgfile1']['name'];
		 if( $file_name <> "" && $file_name1 <> "")
		 {
				$continua = TRUE; 
				
				$ref = $_POST ["txtref"];
				
				$qryref = "SELECT codpropin FROM propin WHERE refpro = '$ref'";
				$resref = mysql_query ($qryref, $enlace);
				$numref = mysql_num_rows($resref);
				
				if ($numref > 0){
				echo "La referencia ya existe en la base de datos";
				$continua = false; 
				}
				
				if ($continua){//1
				
				
			  //Ruta donde guardamos las imágenes
				$ruta_miniaturas = "../productos/pintas/mini";
				$ruta_original = "../productos/pintas";
				$ruta_minisec = "../productos/pintas/minisec";
				
			 //El ancho de la miniatura
				$ancho_miniatura = $filpub["provismin"];
				$ancho_original = $filpub["provisori"];  
			  
			 //Extensiones permitidas
			   $extensiones = array(".gif",".jpg",".png",".jpeg");
	
			   $datosarch = $_FILES["imgfile"];
			   $file_type = $_FILES['imgfile']['type'];
			   $file_size = $_FILES['imgfile']['size'];
			   $file_tmp = $_FILES['imgfile']['tmp_name'];
			   
			   //miniatura
			   $datosarch1 = $_FILES["imgfile1"];
			   $file_type1 = $_FILES['imgfile1']['type'];
			   $file_size1 = $_FILES['imgfile1']['size'];
			   $file_tmp1 = $_FILES['imgfile1']['tmp_name'];
			  
			  //validar la extension
			   $ext = strrchr($file_name,'.');
			   $ext = strtolower($ext);
			   if (!in_array($ext,$extensiones)) {		   
				 echo "¡El tipo de archivo no es permitido!";
				 $continua = FALSE;			  
			   }
			   
			   //miniatura
			   $ext1 = strrchr($file_name1,'.');
			   $ext1 = strtolower($ext1);
			   
			    if (!in_array($ext1,$extensiones)) {		   
				 echo "¡El tipo de archivo no es permitido!";
				 $continua = FALSE;			  
			   }
			   
			   if($continua){ //2
			   
			   // validar tamaño de archivo	   
				if  ($file_size > 8368308 || $file_size1 > 8368308) //bytes = 8368308 = 8392kb = 8Mb
						/*Copia el archivo en una directorio específico del servidor*/
						{
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

						  //consulto ultimo codigo de fotografia insertado para nombre de la imagen siguiente
						  $qryult = "select max(codpropin) as maximo from propin";
					   	  $result = mysql_query($qryult, $enlace);
						  $filult= mysql_fetch_array($result);
						  $siguiente = $filult["maximo"] + 1;
						  $nombre_nuevaimg = $siguiente.".".$file_ext;

						   //guardamos la imagen
						   ImageJpeg ($redimensionada,"$ruta_miniaturas/$nombre_nuevaimg", 250);
						   ImageDestroy ($redimensionada);
				
						 }
							//Subimos la imagen original
							ImageJpeg ($redimensionada1,"$ruta_original/$nombre_nuevaimg", 640);
							//move_uploaded_file ($redimensionada1, "$ruta_original/$nombre_nuevaimg");
							ImageDestroy ($redimensionada1);
							ImageDestroy ($nueva_imagen);
							
							
							//subo miniatura
							$nombre_nuevaimg1 = $siguiente.$ext1;
							move_uploaded_file($file_tmp1,"$ruta_minisec/$nombre_nuevaimg1");
							
							
							//actualizo subgrupo
							$qryfotins="INSERT INTO propin VALUES ('0','$cod','$nombre_nuevaimg','$ref','$nombre_nuevaimg1')";							
							$resfotins=mysql_query($qryfotins,$enlace);

							//refresco contenido
							echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	
							
						}//fin si continua2
					}//fin si continua3
				}
				}
		else
		{
		echo "Seleccione la fotografía a cargar y la vista mini";
		}
}

if (isset($_POST['eliminar']))		
{		
				
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
			if ( entrar ) 
			{
			location = "propineli.php?codpropin=<?php echo $codimg?>&codpro=<?php echo $cod?>"	
			}
			</script>
                <?php
	
	}
	else
	{
	echo "Seleccione las fotos que desea eliminar";
	}
	
}

if (isset($_POST['ver']))		
{
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
          <td width="1037">&nbsp;</td>
          <td width="4">&nbsp;</td>
          <td width="7">&nbsp;</td>
        </tr>
        <tr>
          <td height="61">&nbsp;</td>
          <td colspan="3" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="939" height="52" valign="top" class="titulos"><img src="../images/pintas.png" width="48" height="48" align="absmiddle" /> Pintas de producto <span class="textoerror"><?php echo $filnompro['nompro'];?><strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
              </strong></span></td>
                <td width="211" valign="top"><div align="right"><span class="textonegro">Volver a Producto <a href="proedi.php?cod=<?php echo $cod ?>&acc=1"><img src="../images/back.png" width="32" height="32" border="0" align="absmiddle" /></a></span></div></td>
                <td width="13">&nbsp;</td>
            </tr>
            <tr>
              <td height="9"></td>
              <td></td>
              <td></td>
            </tr>
              </table></td>
          </tr>
        <tr>
          <td height="49"></td>
          <td valign="top" >Cargar Pinta 
              (<?php echo $filpub["provisori"]; ?>) px
              <input name="imgfile" type="file" id="imgfile" />
              Cargar Pinta-mini(<?php echo $filpub["provismin"]; ?>) px
              <input name="imgfile1" type="file" id="imgfile1" />
              Referencia 
              <input name="txtref" type="text" id="txtref" size="13" maxlength="13" />
            <input name="enviar" type="submit" id="enviar" value="Enviar" onClick="if (valida_texto1(form1.txtref.value,'el campo referencia')==false) {return false}" />          </td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="13"></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="271"></td>
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla">
            <!--DWLayoutTable-->
            
            <tr>
              <td width="10" height="12"></td>
                <td width="141"></td>
                <td width="1014"></td>
            </tr>
            <tr>
              <td height="114"></td>
              <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="textonegro" style="width: 100%">
                <!--DWLayoutTable-->
                
                <tr>
                  
                  <?php 
				  if($totalRows_registros > 0){
		$num=$startRow_registros;
		$numero = 0 ;
		do{
			if($numero == 5){
				$numero = 0;
				echo"<tr>" ;
				echo"<td></td>";
				echo"</tr>" ;
			}
		$codreg = $row_registros['codpropin'];
		   ?>
                  <td width="35" height="114" valign="top"><div align="center">
                    <input type="checkbox" name="foto[]" value="<?php echo $codreg;?>" />
                    </div></td>
                        <td width="125" valign="top">REF: <?php echo $row_registros['refpro']; ?><img src="../productos/pintas/mini/<?php echo $row_registros['imgpropin'];?>" width="102" height="114" /></td>
                                                                                                                            
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
                  <?php print $pages_navigation_registros[1]; ?><?php print $pages_navigation_registros[2]; ?></td>
            </tr>
          </table></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="24"></td>
          <td colspan="2" valign="top" class="textonegro"><div align="center">Ver # 
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