<?php
session_start();
include("general/conexion.php") ;

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

$cod = $_GET["cod"];


function validaidioma($form_entrada){
	
	global $enlace;
	global $cod;

	$respuesta = new xajaxResponse();

	$qryexi = "SELECT pim.codimgfon  FROM pagsitefondodiario AS pim WHERE pim.codidi = ".$form_entrada["cbo2codidisi"]." AND pim.coddiasemana = ".$form_entrada["cbo2coddiasemanasi"]." AND pim.codimgfon <> $cod";
	$resexi = mysql_query($qryexi, $enlace);
	
	if(mysql_num_rows($resexi > 0)){
		$respuesta->alert("La imagen de fondo ya existe para el idioma seleccionado");
		$respuesta->assign("cbo","innerHTML",$lista); 
		return $respuesta;
		
	}

}
$xajax->registerFunction("dias");
$xajax->registerFunction("validaidioma");

//El objeto xajax tiene que procesar cualquier petición 
$xajax->processRequest(); 




$qryreg = "SELECT pim.*,d.nomdiasemana, i.nomidi FROM pagsitefondodiario AS pim
	INNER JOIN idipub AS i ON pim.codidi = i.codidi 
	INNER JOIN diasemana AS d ON pim.coddiasemana = d.coddiasemana WHERE pim.codimgfon = $cod ";
$resreg = mysql_query($qryreg, $enlace);
$filreg = mysql_fetch_assoc($resreg);


//valido que haya registros
if(mysql_num_rows($resreg)==0){
	echo '<script language = JavaScript>
	location = "pagsitefondodiario.php";
	</script>';
}


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

function crearregistro(){
var pasa = validaenvia()

	if(pasa == false ){
		return false;
	}else{
		
			
		var entrar = confirm("¿Desea actualizar la imagen de fondo?")
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
		document.form1.hid1imgfondosi.value ="<?php echo $filreg["codimgfon"]?>."+tipo;
	}else{
		alert("El tipo de archivo no es permitido");
		document.form1.img1fileno.value="";
		document.form1.hid1imgfondosi.value ="";
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
		
		$continua = TRUE; 

			//Verifico si se inserta imagen del subgrupo
			$file_name = $_FILES['img1fileno']['name'];
			
			if($file_name <> ""){
				
					
				//Ruta donde guardamos las imágenes
				$ruta_original = "../../imgfondodiaria";

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
						
						//borro archivo actual
						unlink("../../imgfondodiaria/".$filreg["imgfondo"]);
					
						$nombre_nuevaimg = $filreg["codimgfon"].".".$file_ext; 
						
						if (!move_uploaded_file($file_tmp,"$ruta_original/$nombre_nuevaimg")){
							
							echo "Falló al subir el archivo";
							$continua = FALSE;	
							return($continua);	
						}else{
						
						
						return($continua);	
						}
	
					}//fin si continua2
				
				}//fin si continua3
					
			 }	
			  else{
					return($continua);
				}

	}// fin funcion
	
					//boton guardar cambios
					if (isset($_POST['guardarno'])){
						$continua = cargarimagen();
		
						if($continua){
						actualizar(substr(basename($_SERVER['PHP_SELF']),0,-7),2,$cod,"codimgfon","pagsitefondodiario.php");
						}
					}
					//boton aplicar cambios
					if (isset($_POST['aplicarno'])){
						$continua = cargarimagen();
		
						if($continua){
						actualizar(substr(basename($_SERVER['PHP_SELF']),0,-7),2,$cod,"codimgfon","pagsitefondodiarioedi.php?cod=$cod");
						}
					}
					
						//boton cancelar cambios
	if (isset($_POST['cancelarno'])){
	echo '<script language = JavaScript>
	location = "pagsitefondodiario.php";
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
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/fondodiario.png" width="48" height="48" align="absmiddle" />Imagen diaria de secci&oacute;n  [ Edita <strong>
              <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
]</strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="107">&nbsp;</td>
          <td valign="top"><table width="58%" height="175" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="9" height="13"></td>
                <td width="122"></td>
                <td width="309"></td>
                <td width="606"></td>
                <td width="19"></td>
            </tr>
            <tr>
              <td height="13"></td>
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
                  <td colspan="2" valign="top">D&iacute;a de la Semana</td>
              <td></td>
            </tr>
            <tr>
              <td height="22"></td>
              <td colspan="2" valign="top" id="dias"><label>
                    <select name="cbo2coddiasemanasi" id="cbo2coddiasemanasi" title="publica linea educativa" onChange="xajax_validaidioma()">
                      <option value="<?php echo $filreg["coddiasemana"]?>"><?php echo $filreg["nomdiasemana"]?></option>
					  <?php 
					  $qrylis = "SELECT d.coddiasemana, d.nomdiasemana  FROM diasemana AS d WHERE d.coddiasemana <> ".$filreg["coddiasemana"]." ORDER BY d.coddiasemana";
					$reslis = mysql_query($qrylis, $enlace);
					  while ($fillis = mysql_fetch_array($reslis)){
						echo "<option value='".$fillis["coddiasemana"]."'>".$fillis["nomdiasemana"]."</option>/n";
					}
					  ?>
                    </select>
                  </label></td>
              <td></td>
            </tr>
            
            
            <tr>
              <td height="31"></td>
              <td colspan="2" valign="top">
                <label>Imagen de fondo</label>
                <label></label></td>
              <td valign="top" >
                    Imagen de secci&oacute;n
                      <input name="img1fileno" type="file" id="img1fileno" title="Imagen de seccion"  onChange="nombrefoto()"/>
                      <input name="hid1imgfondosi" type="hidden" id="hid1imgfondosi" title="Nombre del alb&uacute;m" value="<?php echo $filreg["imgfondo"]; ?>" size="10"maxlength="100" /></td>
              <td></td>
            </tr>
            <tr>
              <td height="13"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="13"></td>
              <td colspan="3" valign="top">Imagen actual </td>
              <td></td>
            </tr>
            <tr>
              <td height="10"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="39"></td>
              <td colspan="3" valign="top">
                      <img src="../../imgfondodiaria/<?php echo $filreg["imgfondo"];?>"  width="50%">
                    </td>
              <td></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
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