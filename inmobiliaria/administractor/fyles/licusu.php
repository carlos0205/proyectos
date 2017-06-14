<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php");
include("fckeditor/fckeditor.php") ;
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'licusu.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();


$qryinfo = "SELECT * FROM licusu";
$resinfo = mysql_query($qryinfo, $enlace);
$filinfo = mysql_fetch_assoc($resinfo);

?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>

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
            <td height="61" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
                <!--DWLayoutTable-->
                <tr>
                  <td width="6" height="20"></td>
                  <td width="859">&nbsp;</td>
                  <td width="23"></td>
                  
	<td width="59" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
	<td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="15"></td>
                </tr>
                <tr>
                  <td height="15"></td>
                  <td valign="top" >Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
                  <td></td>
                </tr>
                
                <tr>
                  <td height="26" colspan="2" valign="top" class="textoerror"><div align="right">
                      <?php
				//boton guardar cambios
				if (isset($_POST['aplicarno'])){
					
					$introduccion = $_POST["txt1infmulno"];
					if (get_magic_quotes_gpc()){
						$introduccion = htmlspecialchars( stripslashes( $introduccion ) ) ;
					}else{
						$introduccion = htmlspecialchars( $introduccion ) ;
					}
					
					//actualizo tipo de pqrs
					$qryinfoact="UPDATE licusu SET infmul='$introduccion' ";
					$resinfoact=mysql_query($qryinfoact,$enlace);
					
					actualizar("licusu",2,"1","codemp","licusu.php");
				}
			
				 
				//boton cancelar cambios
				if (isset($_POST['cancelarno'])){
					echo '<script language = JavaScript>
					location = "index1.php";
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
            <td width="1379">&nbsp;</td>
            <td width="11">&nbsp;</td>
          </tr>
          <tr>
            <td height="52">&nbsp;</td>
            <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
                <tr>
                  <td width="1390" height="52" valign="top" class="titulos"><img src="../images/workstation1.png" width="48" height="48" align="absmiddle" /> Informaci&oacute;n de compa&ntilde;&iacute;a <strong>
                    <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
                  </strong></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="577">&nbsp;</td>
            <td valign="top"><table width="58%" height="501" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
                <!--DWLayoutTable-->
                <tr>
                  <td width="11" height="14"></td>
                  <td width="135"></td>
                  <td width="320"></td>
                  <td width="108"></td>
                  <td width="440"></td>
                  <td width="23"></td>
                </tr>
                <tr>
                  <td height="23"></td>
                  <td valign="top" ><p>Nombre compa&ntilde;&iacute;a </p></td>
                  <td valign="top"><input name="txt2nomempsi" type="text" id="txt2nomempsi" size="50" value = "<?php  echo $filinfo["nomemp"];?>"maxlength="100" title="Nombre empresa" /></td>
                  <td valign="top" >Ruta Videos </td>
                  <td valign="top">
                    <input name="txt2rutvidsi" type="text" id="txt2rutvidsi" size="50" value = "<?php  echo $filinfo["rutvid"];?>"maxlength="150" title="Ruta de los videos" />
                  </span></td>
                  <td></td>
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
                  <td height="26"></td>
                  <td valign="top" >e-mail compa&ntilde;&iacute;a </td>
                  <td valign="top">
                    <input name="txt2emaempsi" type="text" id="txt2emaempsi" size="50" value = "<?php  echo $filinfo["emaemp"];?>"maxlength="100" title="e-mail empresa" />
                  </span></td>
                  <td colspan="2" valign="top" >Logo Compania </td>
                  <td></td>
                </tr>
                <tr>
                  <td height="7"></td>
                  <td></td>
                  <td></td>
                  <td colspan="2" rowspan="5" valign="top"><?php 
				$datos = GetImageSize('../images/logocli.jpg'); 
				$x = $datos[0]; 
				$y = $datos[1];
				?>
                      <img src="../images/logocli.jpg" width="<?php echo $x;?>" height="<?php echo $y;?>"></td>
                  <td></td>
                </tr>
                <tr>
                  <td height="26"></td>
                  <td valign="top" >Direcci&oacute;n compa&ntilde;&iacute;a </td>
                  <td valign="top">
                    <input name="txt2dirempsi" type="text" id="txt2dirempsi" size="50" value = "<?php  echo $filinfo["diremp"];?>"maxlength="100" title="Dirección empresa" />
                  </span></td>
                  <td></td>
                </tr>
                <tr>
                  <td height="8"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td height="23"></td>
                  <td valign="top" >Tel&eacute;fono compa&ntilde;&iacute;a</td>
                  <td valign="top">
                    <input name="txt1telempsi" type="text" id="txt1telempsi" size="50" value = "<?php  echo $filinfo["telemp"];?>"maxlength="100" title="Teléfono empresa" />
                  </span></td>
                  <td></td>
                </tr>
                <tr>
                  <td height="9"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td height="27"></td>
                  <td valign="top">Licencias de Usuario </span></td>
                  <td valign="top">
                    <input name="txt2licususi" type="text" id="txt2licususi" size="50" value = "<?php  echo $filinfo["licusu"];?>"maxlength="100" title="Licensias de usuario" />
                  </span></td>
                  <td colspan="2" valign="top"><span class="textonegro"> 
                    <input name="img1fileno" type="file" id="img1fileno" />
                    </span>
                        <input name="cambiar" type="submit" id="cambiar" value="Actualizar"/>
                    </span>
                      <?php
				if (isset($_POST['cambiar'])){
					//Verifico si se inserta imagen de la publicación
					$file_name = $_FILES['img1fileno']['name'];
					if( $file_name <> ""){
						$continua = TRUE; 
						//Extensiones permitidas
						$extensiones = array(".jpg");
					
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
						}
						if($continua){ //2
							// validar tamaño de archivo	   
							if  ($file_size > 8368308) //bytes = 8368308 = 8392kb = 8Mb
							/*Copia el archivo en una directorio específico del servidor*/
							{
								echo "¡El archivo debe ser inferior a 8MB!";						
								$continua = FALSE;				
							}
							if ($continua){ //3
								//Tomamos la extension
								$getExt = explode ('.', $file_name);
								$file_ext = $getExt[count($getExt)-1];  
				
								//Ruta donde guardamos los manuales
								$ruta = "../images";
											
								//consulto ultimo codigo de fotografia insertado para nombre de la imagen siguiente 
								$nombre_nuevoarc = "logocli.".$file_ext;
								move_uploaded_file($file_tmp,"$ruta/$nombre_nuevoarc");
								
								//refresco contenido
								echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	
							}//fin si continua3
						}//fin si continua2
					}else{
						echo "Seleccione la el archivo a cargar";
					}
				}
			  ?></td>
                  <td></td>
                </tr>
                <tr>
                  <td height="13"></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td></td>
                </tr>
                <tr>
                  <td height="27"></td>
                  <td valign="top">Url del Sitio Web </span></td>
                  <td valign="top">
                    <input name="txt2urlsi" type="text" id="txt2urlsi" size="50" value = "<?php  echo $filinfo["url"];?>"maxlength="100" title="Url sitio web" />
                  </span></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
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
                  <td height="25"></td>
                  <td valign="top">Nombre BD </span></td>
                  <td valign="top">
                    <input name="txt2nombdsi" type="text" id="txt2nombdsi" size="50" value = "<?php  echo $filinfo["nombd"];?>"maxlength="100" title="Nombre base de datos" />
                  </span></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td></td>
                </tr>
                <tr>
                  <td height="13"></td>
                  <td>&nbsp;</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td height="24"></td>
                  <td valign="top">Repositorio ruta </span></td>
                  <td colspan="3" valign="top">
                    <input name="txt2rutsi" type="text" id="txt2rutsi" size="50" value = "<?php  echo $filinfo["rut"];?>"maxlength="150"  title="Ruta del repositorio"/>
                    en servidor web debe ser</span><span class="titmenu"> /administractor</span></td>
                  <td></td>
                </tr>
                <tr>
                  <td height="6"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td height="23"></td>
                  <td valign="top">Repositorio ruta absol </span></td>
                  <td colspan="3" valign="top">
                    <input name="txt2rutabssi" type="text" id="txt2rutabssi" size="50" value = "<?php  echo $filinfo["rutabs"];?>"maxlength="150" title="Ruta absoluta del repositorio" />
                    en servidor web debe ser<span class="titmenu"> /administractor</span></span></td>
                  <td></td>
                </tr>
                <tr>
                  <td height="17"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td height="14"></td>
                  <td colspan="4" valign="top" >Informaci&oacute;n Multimedia </td>
                  <td></td>
                </tr>
                <tr>
                  <td height="105"></td>
                  <td colspan="4" valign="top">
                    <?php
				// Automatically calculates the editor base path based on the _samples directory.
				// This is usefull only for these samples. A real application should use something like this:
				// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
				
				$oFCKeditor = new FCKeditor('txt1infmulno') ;
				$oFCKeditor->BasePath = '../fyles/fckeditor/';
				$oFCKeditor->Value = html_entity_decode($filinfo["infmul"]);
				$oFCKeditor->Create() ;
				?></td>
                  <td></td>
                </tr>
                <tr>
                  <td height="46"></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
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