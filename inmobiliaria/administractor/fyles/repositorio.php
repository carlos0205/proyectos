<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'repositorio.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();
?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" -->
<style type="text/css">
  .boton{
        font-size:10px;
        font-family:Verdana,Helvetica;
        font-weight:bold;
        color:white;
       	background:#000000;
        border:0px;
        width:60px;
        height:25px;
       }
</style>

<style type="text/css">
	 
	  .tooltip_content{
    	font: 12px "Trebuchet MS",Verdana,Arial,sans-serif; 
		background-color: #D3E8A8;
    	overflow:hidden;
    	margin:0;
    	padding:0;
	  }
	 
#caparepositorio{
	position:relative;
	left: 1px;
	width: 98%;
	height: 304px;
	top: 16px;
	overflow: scroll;
	overflow-x:hidden;
}

</style>
<link href="ventanas_js/themes/default.css" rel="stylesheet" type="text/css" >	 </link>
<link href="ventanas_js/themes/alphacube.css" rel="stylesheet" type="text/css" >	 </link>

<script language="javascript" type="text/javascript">
		
	function refresco()
	{
	var Select = document.getElementById("selectdir");
	var valor = Select.options[Select.selectedIndex].value;
	location.href='repositorio.php?dir='+valor;
	}
	
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
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
	  <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
        <!--DWLayoutTable-->
        <tr>
          <td height="33" colspan="3" valign="top" bgcolor="#FFFFFF">
		  <script type="text/javascript" src="ventanas_js/javascripts/prototype.js"> </script> 
			<script type="text/javascript" src="ventanas_js/javascripts/window.js"> </script>
			<script type="text/javascript" src="ventanas_js/javascripts/tooltip.js"> </script>
		  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
        <!--DWLayoutTable-->
        <tr>
          <td width="22" height="41">&nbsp;</td>
          <td width="858">&nbsp;</td>
          <td width="13">&nbsp;</td>
          <td width="59" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="subir" type="submit" value="subir" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img width="32" src="../images/upload_f2.png"  /><br>
                  Subir</button></td>
          <td width="58" rowspan="3" align="center"  valign="middle"><button class="textonegro"   name="crear" type="submit" value="crear" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" ><img src="../images/new_f2.png" width="32" height="32"  /><br>
                  Crear</button></td>
          <td width="75" rowspan="3" align="center"  valign="middle"><button class="textonegro"   name="cancelar" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"  ><img width="32" src="../images/guardar.png"  /><br>
                  Cancelar</button></td>
          <td width="14">&nbsp;</td>
        </tr>
        <tr>
          <td height="18"></td>
          <td valign="top" class="textoerror"><div align="right">
            <?php


$qryruta = "SELECT rut, rutabs FROM licusu";
$resruta = mysql_query($qryruta, $enlace);
$filruta = mysql_fetch_assoc($resruta);

$ruta = $_SERVER['DOCUMENT_ROOT'];
$mosConfig_absolute_path = $ruta.'/comertex/administractor';

$ruta1 =  $filruta["rut"];

$mosConfig_live_site = $ruta1;
define( 'COM_MEDIA_BASE', $mosConfig_absolute_path . '/' . 'repositorio' );
define( 'COM_MEDIA_BASEURL', $mosConfig_live_site  );
		
	
//ruta original de repositorio
$rutaoriginal = "../repositorio/";

//valido si enviaron borrar archivo
if( isset($_GET['fileborra']))
{
	$rutafile = $_GET["fileborra"];
	$ruta = $_GET["ruta"];
	$rutafile = $rutaoriginal.$rutafile;
	if (file_exists( $rutafile ))
	{
		unlink( $rutafile );
		$ruta = substr ("$ruta", 16);
		?>
            <script language="javascript" type="text/javascript">
		var ruta = '<? echo $ruta?>';
		location = 'repositorio.php?dir='+ruta
		</script>
            <?
	}
}

//valido si enviaron borrar directorio
if( isset($_GET['dirborra']))
{
	$ruta = $_GET["dirbor"];
	$dirborra = $_GET["dirborra"];
	$dirborra = $ruta.$dirborra;
	if (is_dir( $dirborra ))
	{
		$del_html = $dirborra . '/index.html';
		$dir = opendir( $dirborra );
		while ($entry = readdir( $dir )) {
		if( $entry != "." & $entry != ".." & strtolower($entry) != "index.html" )
			$entry_count++;
		}
		closedir( $dir );
		
		if ($entry_count < 1) {
		@unlink( $del_html );
		rmdir( $dirborra );
		}

		$ruta = substr ("$ruta", 16);
		?>
            <script language="javascript" type="text/javascript">
		var ruta = '<? echo $ruta?>';
		location = 'repositorio.php?dir='+ruta
		</script>
            <?
	}
}


//validacion de acceso a directorio
if( isset($_GET['dir']))
{
	$ruta = $_GET["dir"];
	$ruta1 = $ruta;
	$pasaruta = 2;
}
else
{
	$ruta = $rutaoriginal;
	$ruta1 = "";
	$rutavolver = "";
	$pasaruta = 1;
}

//validar puntos de diretorio en ruta

if ($pasaruta <> 1)
{
	if (is_int(strpos($ruta, ".."))&&$ruta!='') 
	{
		echo "NO HACER HACKING POR FAVOR" ;
		$mostrar = "no";
	}
	else
	{
		$mostrar = "si";
		$ruta = $rutaoriginal.'/'.$ruta.'/';
		
	}
}
else
{
	$mostrar = "si";
}
/*
* Chmods files and directories recursively to mos global permissions. Available from 1.0.0 up.
* @param path The starting file or directory (no trailing slash)
* @param filemode Integer value to chmod files. NULL = dont chmod files.
* @param dirmode Integer value to chmod directories. NULL = dont chmod directories.
* @return TRUE=all succeeded FALSE=one or more chmods failed
*/
function mosChmod($path) {
	global $mosConfig_fileperms, $mosConfig_dirperms;
	$filemode = NULL;
	if ($mosConfig_fileperms != '')
		$filemode = octdec($mosConfig_fileperms);
	$dirmode = NULL;
	if ($mosConfig_dirperms != '')
		$dirmode = octdec($mosConfig_dirperms);
	if (isset($filemode) || isset($dirmode))
		return mosChmodRecursive($path, $filemode, $dirmode);
	return TRUE;
} // mosChmod

//boton crear directorio
if (isset($_POST['crear']))
{
$ruta1 = substr ("$ruta", 16);
if($ruta1 <> "//" and $ruta1 <> "/" and $ruta1 <> ""){
		//averiguo si hay permisos para crear directorio
		if (ini_get('safe_mode')=='On')
		{
			echo"Creación del directorio no permitida mientras que este activado el MODO SEGURO (SAFE MODE OFF)";
		}
		 else
		{
			$folder_name =  $_POST["txtdir"];
			if(strlen($folder_name) >0)
			{
				if (eregi("[^0-9a-zA-Z_]", $folder_name))
				{
					echo "El nombre del directorio sólo puede contener carácteres alfanuméricos y sin espacios en blanco." ;
				}
				else
				{
					$folder = $ruta .'/'. $folder_name;
					if(!is_dir( $folder ) && !is_file( $folder ))
					{			
					echo "Directorio creado con éxito";
					mkdir($folder, 0777);
					$fp = fopen( $folder . "/index.html", "w" );
					fwrite( $fp, "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>" );
					fclose( $fp );
					mosChmod( $folder."/index.html" );
					//refresco contenido
					echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	
					}
		
				}				
			}
		}
	}else{
	echo "No es posible crear mas directorios en la raiz";
	}
}
//valido si elimina directorio o archivo
	echo '<SCRIPT LANGUAGE="JavaScript" type="text/javascript">
	function borrafolder(total,dir,ruta)
	{
		if (total > 0)
		{
		alert ("No es posible eliminar el directorio, no esta vacio.");
		}
		else
		{
			<!--
			var entrar = confirm("Se eliminará el directorio: "+dir+" ¿Desea continuar?")
			if ( entrar ) 
			{
			location = "repositorio.php?dirborra="+dir+"&dirbor="+ruta
			}

			//-->
		}
	}
	
	function borrafile(file,nombre,ruta)
	{
			<!--
			var entrar = confirm("Se eliminará el archivo: "+nombre+"  ¿Desea continuar?")
			if ( entrar ) 
			{
			location = "repositorio.php?fileborra="+file+"&ruta="+ruta
			}

			//-->
	}
				</SCRIPT>';

//boton cancelar
if (isset($_POST['cancelar']))
{
echo "se ha enviado cancelar";
}

//boton ayuda
if (isset($_POST['ayuda']))
{
echo "se ha enviado ayuda";
}

//boton subir archivo
if (isset($_POST['subir']))
{
$ruta1 = substr ("$ruta", 16);
if($ruta1 <> "//" and $ruta1 <> "/" and $ruta1 <> ""){

		function carga_archivo($file, $dest_dir) {
			global $clearUploads;
			$continua = true;
		
			if (empty($file['name'])) {
				echo"El archivo que intenta subir no esta seleccionado" ;
				$continua = false;
				
			}
			elseif (file_exists($dest_dir.$file['name'])) {
				echo"Fallo al subir. El archivo que intenta subir ya existe" ;
				$continua = false;
			}
			if ($continua)
			{//si continua
				$format = substr( $file['name'], -3 );
				$permitido = array (
					'bmp',
					'csv',
					'doc',
					'epg',
					'gif',
					'ico',
					'jpg',
					'odg',
					'odp',
					'ods',
					'odt',
					'pdf',
					'png',
					'ppt',
					'swf',
					'txt',
					'xcf',
					'xls',
					'flv',
					'docx',
					'xlsx',
					'pptx',
					'cdr',
					'pst'
					);
		
			$noencontrados = 0;
			foreach( $permitido as $ext ) {
				if ( strcasecmp( $format, $ext ) == 0 ) {
					$noencontrados = 1;
				}
			}
			if(!$noencontrados){
				echo'El tipo de archivo no es soportado';
			}	
			elseif (!move_uploaded_file($file['tmp_name'], $dest_dir.strtolower($file['name']))){
				echo"Fallo al subir" ;
			} else {
				echo"¡Completado!";
			}
		
			$clearUploads = true;
		  }//fin si continua
	}
	$file = $_FILES["cargafile"];
	$dest_dir = $ruta;
	carga_archivo($file, $dest_dir);
}
else
{
	echo "No esposible subir archivos a la raiz";
}

}

//funcion para llenar combo con direcciones de directorio
function listar_directorios($ruta)
{
// abrir un directorio y listarlo recursivo
if (is_dir($ruta)) 
{
	if ($dh = opendir($ruta)) 
	{
		while (($file = readdir($dh)) !== false) 
		{
		//esta línea la utilizaríamos si queremos listar todo lo que hay en el directorio
		//mostraría tanto archivos como directorios
		//echo "Nombre de archivo: $file : Es un:" . filetype($ruta . $file);
		if (is_dir($ruta . $file) & $file!="." & $file!=".." & strtolower($file) != "index.html"  )
			{
			//solo si el archivo es un directorio, distinto que "." y ".."
			$ruta1 = substr ("$ruta", 15); // devuelve "bcd"
			echo "<option value=\"".$ruta1.$file."\">".$ruta1.$file."</option>\n";
			//echo "Directorio: $ruta1$file";
			listar_directorios($ruta . $file . "/");
			}
		}
	closedir($dh);
	}
}
else
echo "No es ruta valida";
}

		?>
          </div></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="3"></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        
        
        
        <!--DWLayoutTable-->
      </table></td>
        </tr>
        <tr>
          <td width="11" height="25">&nbsp;</td>
          <td width="1183">&nbsp;</td>
          <td width="9">&nbsp;</td>
        </tr>
        <tr>
          <td height="45">&nbsp;</td>
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
        <!--DWLayoutTable-->
        <tr>
          <td width="789" rowspan="2" valign="top" class="titulos"><img src="../images/mediamanager.png" width="48" height="48" align="absmiddle" /> Repositorio Multimedia           </td>
          <td width="160" height="22" valign="top" >Crear Directorio </td>
          <td width="421" valign="top" style="padding-right:10px;white-space:nowrap"><input name="txtdir" type="text" id="txtdir"  style="width:400px" /></td>
        </tr>
        <tr>
          <td height="33" valign="top" >C&oacute;digo Imagen/Url </td>
          <td valign="top" style="padding-right:10px;white-space:nowrap"><input name="txturlfile" type="text" id="txturlfile"  style="width:400px" /></td>
        </tr>
      </table></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="156">&nbsp;</td>
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="marcotabla">
            <!--DWLayoutTable-->
            <tr>
              <td width="7" height="13"></td>
                  <td width="599"></td>
                  <td width="729"></td>
                  <td width="21"></td>
                </tr>
            
            <tr>
              <td height="35"></td>
                  <td valign="top" >Directorio 
 
                    <select name="selectdir" id="selectdir"  onblur="refresco()">
				    <?
				    echo "<option value=\"/ \">/</option>\n";
					listar_directorios("../repositorio/");
				    ?>
                    </select>

                    <? echo "<a href=\"javascript:history.go(-1)\"><img src=\"../images/btnFolderUp.gif\" alt=\"Subir\" width=\"15\" height=\"15\" border=\"0\" /></a>"; ?> <? $ruta1 = substr ("$ruta", 16);
			echo "Esta en: ".$ruta1; ?></td>

                  <td valign="top" class="textonegro"><div align="right">Subir archivo <small>[ Max = <?php echo ini_get( 'post_max_size' );?> ]</small> &nbsp;&nbsp;&nbsp;&nbsp;
                      <input class="inputbox" type="file" name="cargafile" id="cargafile" size="63" />
                  </div></td>
                  <td></td>
                </tr>
            <tr>
              <td height="16"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
      <tr>
              <td height="191"></td>
<? if ($mostrar == "si"){?>
              <td colspan="2" valign="top"><div id="caparepositorio"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
                <!--DWLayoutTable-->
<?
			
			
			  function listar_directorios_ruta($ruta,$ruta1){ 
			   // abrir un directorio y listarlo recursivo 
			   if (is_dir($ruta)) { 
				  if ($dh = opendir($ruta)) { 
				  	$contador = 0;
					$contador1 = 0;
					 while (($file = readdir($dh)) !== false )
					 { 
						//listo de 8 imagenes horizontal
						if ($contador == 10)
						{
						?>
						<tr></tr>
						<?
						$contador = 0;
						}
						 //si archivo diferente de directorio . y .. 
						 if ($file != "." & $file != ".."& strtolower($file) != "index.html" )
						 {
							
							//print "<p>Esto es una comilla simple: ' y esto una comilla doble: \"</p>";

							$img_url_link 	= "<img src="."\'".COM_MEDIA_BASEURL.$ruta1.rawurlencode( $file )."\'"."/>";
							?>
							<td>
							<table width="80" border="0" cellpadding="0" cellspacing="0" class="marcotabla" >
							<!--DWLayoutTable-->
							<tr>
							<td width="80" height="60" valign="top"><table width="80" border="0" cellpadding="0" cellspacing="0" >
							
							<!--DWLayoutTable-->
							
							<?php
							
							//averiguo si es directorio y pongo icono de directorio
							if (is_dir ($ruta.$file))
							{
							///////averiguo si tiene archivos dentro para no permitir eliminarlo
								  if ($sdh = opendir($ruta.$file)) { 
									$totalfiles = 0;
										 while (($files = readdir($sdh)) !== false )
										 {
										 if ($files != "." && $files != ".." & strtolower($files) != "index.html" )
											{
											$totalfiles++;
											}
										 }
									 }
							////////////////
							?>	
								<div id="tooltip_content<? echo $contador1 ;  ?>" style="display:none">
										<div class="tooltip_content">
										<h4><? echo $file ; ?></h4>
										<? echo "contiene: ".$totalfiles." archivos / carpetas" ; ?>
										<br />
										* Clic para abrir *										</div>
										</div>
							<tr>
								 <td width="70" height="60" align="center" valign="middle"  class="tooltip html_tooltip_content<? echo $contador1 ;  ?>"><a href="repositorio.php?dir=<?php echo $ruta1.$file ;?>"><img src="../images/folder.png" width="32" height="32" border = 0 alt= "clic para entrar al directorio"/></a></td>
							<?			
							}
							else
							{
							
								//pongo lapiz de edicion
							?>
							<tr>
							<?
								//calculo tamñano y dimensiones del archivo
								$filesize = filesize ($ruta . $file);
								$tamano = getimagesize($ruta . $file);
								if($filesize < 1024) {
								$filesize = $filesize.' bytes';
								} else if($filesize >= 1024 && $filesize < 1024*1024) {
								$filesize = sprintf('%01.2f',$filesize/1024.0).' Kb';
								} else {
								$filesize = sprintf('%01.2f',$filesize/(1024.0*1024)).' Mb';
								}
	
								//averiguo si archivo es un documento y pongo icono
								$format = substr( $file, -3 );
								$documentos = array (
								
								'csv',
								'doc',
								'odg',//formato de documento open oficce
								'odp',//formato de documento open oficce
								'ods',//formato de documento open oficce
								'odt',//formato de documento open oficce
								'pdf',
								'ppt',
								'swf',
								'txt',
								'xcf',//fotmato de imagen
								'xls',
								'flv',
								'docx',
								'xlsx',
								'pptx',
								'cdr',
								'pst'
							);
				
								$noencontrado = 0;
								foreach( $documentos as $ext )
								{
									//averiguo si las cadenas son iguales
									if ( strcasecmp( $format, $ext ) == 0)
									{
										$noencontrado = 1;
									}
								}
								if ($noencontrado == 1)
								{
									$iconfile= "../images/".substr($file,-3)."_32.png";
									if (file_exists($iconfile))	{
										$icon = '../images/'.(substr($file,-3)).'_32.png'	;
										?>
										<div id="tooltip_content<? echo $contador1 ;  ?>" style="display:none">
										<div class="tooltip_content">
										<h4><? echo $file ; ?></h4>
										tamaño: <? echo $filesize ; ?>
										
										<br />
										* Clic para el código del archivo *										</div>
										</div>
										<td width="80" height="60" align="center" valign="middle"  class="tooltip html_tooltip_content<? echo $contador1 ;  ?>" onClick="txturlfile.value = '<? echo $img_url_link; ?>'" ><img src="<? echo $icon ;?>" /></td>
										<?
									} else {
									$icon = '../images/con_info.png';
										?>
										<div id="tooltip_content<? echo $contador1 ;  ?>" style="display:none">
										<div class="tooltip_content">
										<h4><? echo $file ; ?></h4>
										tamaño: <? echo $filesize ; ?>
										<br />
										* Clic para el código del archivo *										</div>
										</div>
										<td  width="80" height="60" align="center" valign="middle" onMouseOver="dentro(this)" onMouseOut="fuera(this)" class="tooltip html_tooltip_content<? echo $contador1 ;  ?>" onClick="txturlfile.value = '<? echo $img_url_link; ?>'" ><img src="<? echo $icon ; ?>" /></td>
										<?
									}
								}//fin si noencontrado
								else
								{
								if ($format == ".db"){
								$icon = '../images/con_info.png'	;	
								?>
										<div id="tooltip_content<? echo $contador1 ;  ?>" style="display:none">
										<div class="tooltip_content">
										<h4><? echo $file ; ?></h4>
										tamaño: <? echo $filesize ; ?>
										<br />
										* Clic para el código del archivo *										</div>
										</div>
										<td  width="80" height="60" align="center" valign="middle"  class="tooltip html_tooltip_content<? echo $contador1 ;  ?>" onClick="txturlfile.value = '<? echo $img_url_link; ?>'" ><img src="<? echo $icon ; ?>"  /></td>
								<?
								
								}else{
									
								?>
								<div id="tooltip_content<? echo $contador1 ;  ?>" style="display:none">
										<div class="tooltip_content">
										<h4><? echo $file ; ?></h4>
										ancho:<? echo $tamano[0];?> px <br />  alto: <? echo $tamano[1]; ?> px <br /> tamaño: <? echo $filesize ; ?>
										<br />
										* Clic para ampliar *
										<br />
										* Clic para el código del archivo *										</div>
									  </div>
								<td width="80" height="60" align="center" valign="middle" onMouseOver="dentro(this)" onMouseOut="fuera(this)" class="tooltip html_tooltip_content<? echo $contador1 ;  ?>" onClick="txturlfile.value = '<? echo $img_url_link; ?>'" ><img src="<? echo $ruta.$file ;?>"   <? if ($tamano[0] > 90){ ?>width="70" height="50" <? } ?> /></td>
								<?
									}
								}							
							}
							?>
							</tr>
							</table>
							</td>
							</tr>
							<tr>
							<?
							if (is_dir ($ruta . $file))
							{
							?>
								<td height="39" valign="top" bgcolor="#FFFF99" class="textomedio">Dir: <? echo $file; ?><br />
							    <img src="../images/edit_trash.gif" border = "0" alt = "eliminar" onmouseover ="style.cursor='hand'" onclick ="borrafolder('<?  echo $totalfiles ;?>','<? echo $file; ?>','<?php echo $ruta;?>')"/></td>
							<?
							}
							else
							{
							?>
								<td height="39" valign="top" bgcolor="#FFFF99" class="textomedio"><? echo $file ; ?><br />
							    <img src="../images/edit_pencil.gif" border = "0" alt = "codigo" onmouseover ="style.cursor='hand'" onClick="txturlfile.value = '<? echo $img_url_link; ?>'" /> <img src="../images/edit_trash.gif" border ="0" alt = "eliminar" onmouseover ="style.cursor='hand'" onclick ="borrafile('<?  echo $ruta1.$file ;?>','<?  echo $file ;?>','<?php echo $ruta;?>')"></td>
								<?
							}
							?>
							</tr>
						   </table>
						   <br />

							<?
							$contador++;
							$contador1++;
						   }
					  
					 } 
		
				  closedir($dh); 

				  } 
				 
			  }else 
			  
				  echo "<br />No es ruta valida"; 
			} 
			listar_directorios_ruta($ruta,$ruta1);
?> 
                   </table>
              </div></td></tr>
			  <? }  ?>

<tr>		 
              <td></td>
</tr>
            <tr>
              <td height="66"></td>
              <td>&nbsp;</td>
              <td></td>
              <td></td>
            </tr>
            

          </table>
		  <script type="text/javascript" language="javascript">
TooltipManager.init("tooltip", {url: "tooltip_ajax.html", options: {method: 'get'}},{className: 'alphacube'}, {showEffect: Element.show, hideEffect: Element.hide}); 
</script>
		  </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="41">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
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