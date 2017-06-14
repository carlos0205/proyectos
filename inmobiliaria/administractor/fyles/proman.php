<?php 
session_start();
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
          <td height="63" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
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
		 if( $file_name <> "")
		 {
				$continua = TRUE; 
				
				$nom = $_POST ["txtnom"];
				
 
			  //Extensiones permitidas
			   $extensiones = array(".doc",".xls",".ppt",".pdf",".xlsx",".docx",".zip",".rar",".pptx");
	
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
						$ruta = "../productos/manuales";
							
						  //consulto ultimo codigo de fotografia insertado para nombre de la imagen siguiente
						  $qryult = "select max(codproman) as maximo from proman";
					   	  $result = mysql_query($qryult, $enlace);
						  $filult= mysql_fetch_array($result);
						  $siguiente = $filult["maximo"] + 1;
						  $nombre_nuevoarc = $siguiente.".".$file_ext;
							
							move_uploaded_file($file_tmp,"$ruta/$nombre_nuevoarc");
							
							//inserto manual
							
							$qrymanins="INSERT INTO proman VALUES ('0','$cod','$nom', '$nombre_nuevoarc','$file_size')";							
							$resmanins=mysql_query($qrymanins,$enlace);
							
							//refresco contenido
							echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";	
							 
						}//fin si continua3
					}//fin si continua2
		}
		else
		{
		echo "Seleccione la el archivo a cargar";
		}
}

if (isset($_POST['eliminar']))		
{		
				
	if(!empty($_POST['manual'])) {
	
	function array_envia($codman) { 

    $tmp = serialize($codman); 
    $tmp = urlencode($tmp); 

    return $tmp; 
	} 
	
	$codman=array_values($_POST['manual']); 
	$codman=array_envia($codman); 

?>
             <script type="text/javascript" language="javascript1.2">
			var entrar = confirm("¿Desea Eliminar los registros seleccionados?")
			if ( entrar ) 
			{
			location = "promaneli.php?codproman=<?php echo $codman?>&codpro=<?php echo $cod?>"	
			}
			</script>
                <?php
	
	}
	else
	{
	echo "Seleccione los manuales que desea eliminar";
	}
	
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
          <td width="11">&nbsp;</td>
          </tr>
        <tr>
          <td height="61">&nbsp;</td>
          <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="textonegro" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="939" height="52" valign="top" class="titulos"><img src="../images/manuales.png" width="48" height="48" align="absmiddle" />Manuales de Producto   <span class="textoerror"><?php echo $filnompro['nompro'];?><strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
              </strong></span></td>
                <td width="211" align="right" valign="top"><div align="right">Volver a Producto <a href="proedi.php?cod=<?php echo $cod ?>&acc=1"><img src="../images/back.png" width="32" height="32" border="0" align="absmiddle" /></a></div></td>
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
          <td height="27"></td>
          <td valign="top">Cargar Manual 
              <input name="imgfile" type="file" id="imgfile" />
              Nombre del Manual
              <input name="txtnom" type="text" id="txtnom" size="50" maxlength="100" />
              <input name="enviar" type="submit" id="enviar" value="Cargar documento" onClick="if (valida_texto1(form1.txtnom.value,'el campo nombre del manual')==false) {return false}" />
          </span></td>
          <td></td>
          </tr>
        <tr>
          <td height="13"></td>
          <td></td>
          <td></td>
          </tr>
        <tr>
          <td height="89"></td>
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla">
            <!--DWLayoutTable-->
            
            <tr>
              <td width="31" height="24" valign="top" bgcolor="#FFFFFF" >Item</td>
                <td width="370" valign="top" bgcolor="#FFFFFF" >Manual</td>
                <td width="121" valign="middle" bgcolor="#FFFFFF" >Archivo</td>
                <td width="643" valign="top" bgcolor="#FFFFFF" >Tama&ntilde;o</td>
                </tr>
            <tr>
              <?php $qryman = "SELECT * FROM proman WHERE codpro = '$cod'";
			  $resman = mysql_query($qryman, $enlace);
			  $numman=mysql_num_rows($resman);
		
			if ($numman > 0)
			{	/*Recorrido de cada campo de la consulta*/
				
			while ($filman = mysql_fetch_array($resman))
				{
 				$tamano=$filman["tamman"]/1000;
			  ?>
              <td height="22" valign="top">
                <input name="manual[]" type="checkbox" id="manual[]" value="<?php echo $filman['codproman']; ?>" />                
                &nbsp;</td>
                <td valign="top" class="textonegro"><?php echo $filman["nomman"]; ?></td>
			    <td valign="top" class="titmenu"><strong><a href="../productos/manuales/<? echo $filman['docman'];?>" target="_blank">abrir</a></strong></td>
                <td valign="top" class="textonegro"><?php echo $tamano." KB"; ?>&nbsp;</td>
                </tr>
            <?php } }?>
            
            <tr>
              <td height="37" colspan="4" valign="top" bgcolor="#FFFFFF" class="textonegro">
                <div align="center"></div></td>
              </tr>
            <tr>
              <td height="4"></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>    
          </table></td>
          <td></td>
          </tr>
        <tr>
          <td height="24" colspan="2" valign="top" class="textonegro"><div align="center"></div></td>
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