<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'info.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

$qryreg= "SELECT nomemp, emaemp, diremp, telemp, telofiemp, faxemp, imgfonreq, imgfon, imgfonx, imgfony, colfon, fondofijo  FROM licusu";
$resreg = mysql_query($qryreg, $enlace);
$filreg = mysql_fetch_assoc($resreg);

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

/* CONVERSOR DE txt2colfonsi A RGB Y VICEVERSA
Este script y otros muchos pueden
descarse on-line de forma gratuita
en El Código: www.elcodigo.com
*/

//HEX to RGB
//-------------------------------------
function hex_a_decimal( miformu ) {
	miformu.txt1rno.value = hex_a_R(miformu.txt2colfonsi.value)
	miformu.txt1gno.value = hex_a_G(miformu.txt2colfonsi.value)
	miformu.txt1bno.value = hex_a_B(miformu.txt2colfonsi.value)
	
	//txt1muestrano el color en el textarea
	cambiaColor( miformu )
}

//decimal para el rojo
function hex_a_R(valor_hex) {
	valor_hex = quita_almoadilla(valor_hex)
	return parseInt( valor_hex.substring(0,2), 16 )
}

//decimal para el verde
function hex_a_G(valor_hex) {
	valor_hex = quita_almoadilla(valor_hex)
	return parseInt( valor_hex.substring(2,4), 16 )
}

//decimal para el azul
function hex_a_B(valor_hex) {
	valor_hex = quita_almoadilla(valor_hex)
	return parseInt( valor_hex.substring(4,6), 16 )
}

//elimina el caracter # si esta presente
function quita_almoadilla(valor_hex) { 
	return (valor_hex.charAt(0)=="#") ? valor_hex.substring(1,7) : valor_hex
}

//RGB to HEX
//-------------------------------------
function RGB_a_hex( miformu ) {
	var valor_R = miformu.txt1rno.value
	var valor_G = miformu.txt1gno.value
	var valor_B = miformu.txt1bno.value
	miformu.txt2colfonsi.value = decimal_a_hex(valor_R) + decimal_a_hex(valor_G) + decimal_a_hex(valor_B)
	
	//txt1muestrano el color en el textarea
	cambiaColor( miformu )	
}

function decimal_a_hex(numero) {
	if (numero == null)
		return "00"
	
	numero = parseInt(numero)
	
	if (isNaN(numero))
		return "00"
	else if (numero <= 0 )
		return "00"
	else if (numero > 255)
		return "FF"

	numero = Math.round(numero)
	
	return "0123456789ABCDEF".charAt((numero - numero % 16)/16) + "0123456789ABCDEF".charAt(numero % 16)
}

//Cambia color txt1muestrano
function cambiaColor( formulario ) {
	formulario.txt1muestrano.style.backgroundColor = '#' + formulario.txt2colfonsi.value
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
              <td width="3" height="6"></td>
                  <td width="859"></td>
                  <td width="20"></td>
                
          <td width="66" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
          <td width="83" rowspan="3" align="center" valign="middle"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="19"></td>
            </tr>
            <tr>
              <td height="15"></td>
              <td valign="top" >Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
                  <td></td>
            </tr>
            
           <tr>
              <td height="30"></td>
              <td valign="top" class="textoerror"><div align="right">
                <?php
				//boton guardar cambios
				if (isset($_POST['aplicarno'])){
				
				actualizar("licusu",2,"1","codemp","info.php");
					}
					
					 
					 
					//boton cancelar cambios
					if (isset($_POST['cancelarno']))
					{
					echo '<script language = JavaScript>
					location = "index1.php";
					</script>';
					}
					 ?>                
              </div></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
           </tr>
            <tr>
              <td height="14"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            
            
            
            
            
            
            
            

              </table></td>
        </tr>
        <tr>
          <td width="4" height="25">&nbsp;</td>
          <td width="1172">&nbsp;</td>
          <td width="10">&nbsp;</td>
        </tr>
        
        <tr>
          <td height="52">&nbsp;</td>
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/empresa.png" width="48" height="48" align="absmiddle" />Informaci&oacute;n b&aacute;sica compa&ntilde;&iacute;a  <strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
                [ Edita ] </strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="180">&nbsp;</td>
          <td valign="top"><table width="58%" height="258" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="4" height="13"></td>
                  <td width="100"></td>
                  <td width="360"></td>
                  <td width="154"></td>
                  <td width="123"></td>
                  <td width="84"></td>
                  <td width="204"></td>
                  <td width="16"></td>
            </tr>
            <tr>
              <td height="26"></td>
                <td valign="top" ><p>Nombre compa&ntilde;&iacute;a  
                  
                  </p></td>
                  <td valign="top"><input name="txt2nomempsi" type="text" id="txt2nomempsi" size="50" value = "<?php  echo $filreg["nomemp"];?>"maxlength="100" title="Nombre empresa" /></td>
                <td colspan="2" valign="top" >COLOR DE FONDO DE PAGINA ACTUAL </td>
                <td colspan="2" valign="top" bgcolor="#<?php echo $filreg["colfon"]?>"><!--DWLayoutEmptyCell-->&nbsp;</td>
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
                <td></td>
            </tr>
            <tr>
              <td height="23"></td>
                <td valign="top" >e-mail compa&ntilde;&iacute;a </td>
                <td valign="top">
                  <input name="txt2emaempsi" type="text" id="txt2emaempsi" size="50" value = "<?php  echo $filreg["emaemp"];?>"maxlength="100" title="e-mail" />
                  </span></td>
                <td valign="top" class="titulos">COLOR RGB </td>
                <td colspan="2" valign="top">R
                    <input name="txt1rno" type="text" id="txt1rno" onChange="RGB_a_hex(this.form);" value="255" size="3" maxlength="3" onKeyPress="onlyDigits(event,'noDec')" />
G
<input name="txt1gno" type="text" id="txt1gno" onChange="RGB_a_hex(this.form);" value="255" size="3" maxlength="3" onKeyPress="onlyDigits(event,'noDec')"/>
B
<input name="txt1bno" type="text" id="txt1bno" onChange="RGB_a_hex(this.form);" value="255" size="3" maxlength="3" onKeyPress="onlyDigits(event,'noDec')"/>
                                                                                                </span></td>
                <td valign="top">txt1muestrano
                    <input name="txt1muestrano" type="text" id="txt1muestrano" style="background-color: #FFFFFF;" value="" size="4" />
                </span></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="17"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="26"></td>
                <td valign="top" >Direcci&oacute;n compa&ntilde;&iacute;a </td>
                <td valign="top">
                  <input name="txt1dirempsi" type="text" id="txt1dirempsi" size="50" value = "<?php  echo $filreg["diremp"];?>"maxlength="100" />
                  </span></td>
                <td valign="top" class="titulos">txt2colfonsi</td>
                <td colspan="3" valign="top">#
                  <input name="txt2colfonsi" type="text" id="txt2colfonsi" value="<?php echo $filreg["colfon"];?>" size="8"  onChange="hex_a_decimal(this.form);" /></td>
                <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="17"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
              <td height="26"></td>
                <td valign="top" >L&iacute;nea de atenci&oacute;n </td>
                <td valign="top">
                  <input name="txt1telempsi" type="text" id="txt1telempsi" size="50" value = "<?php  echo $filreg["telemp"];?>"maxlength="100" />
                  </span></td>
                <td colspan="3" rowspan="3" valign="top" >txt1muestrano Imagen de fondo
                  <select name="cbo2imgfonreqsi" id="cbo2imgfonreqsi" title="Publicar">
                    <?php
					  $qrypub="SELECT 'Si' AS publica
						UNION
						SELECT 'No' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['imgfonreq']."\">".$filreg['imgfonreq']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["imgfonreq"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
                    </select>
                  El fondo es fijo 
                  <select name="cbo2fondofijosi" id="cbo2fondofijosi" title="Publicar">
                    <?php
					  $qrypub="SELECT 'Si' AS publica
						UNION
						SELECT 'No' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['fondofijo']."\">".$filreg['fondofijo']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["fondofijo"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
                  </select>
                  <br>
                  <br>
                  Repite x
                  <select name="cbo2imgfonxsi" id="cbo2imgfonxsi" title="Publicar">
                    <?php
					  $qrypub="SELECT 'Si' AS publica
						UNION
						SELECT 'No' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['imgfonx']."\">".$filreg['imgfonx']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["imgfonx"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
                    </select>
                  Repite Y
                  <select name="cbo2imgfonysi" id="cbo2imgfonysi" title="Publicar">
                    <?php
					  $qrypub="SELECT 'Si' AS publica
						UNION
						SELECT 'No' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['imgfony']."\">".$filreg['imgfony']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["imgfony"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
                    </select></td>
                <td rowspan="6" align="center" valign="top" >muestra imagen defondo <br>
                  <img src="../../images/<?php echo $filreg["imgfon"]?>" width="200" height="110"/></td>
                <td></td>
            </tr>
            <tr>
              <td height="9"></td>
              <td ></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="22"></td>
              <td valign="top" >Oficinas</td>
              <td valign="top">
                <input name="txt1telofiempsi" type="text" id="txt1telofiempsi" size="50" value = "<?php  echo $filreg["telofiemp"];?>"maxlength="100" />
                </span></td>
              <td></td>
            </tr>
            <tr>
              <td height="22"></td>
                <td >&nbsp;</td>
              <td>&nbsp;</td>
              <td colspan="3" valign="top" >Imagen de Fondo Actual </td>
                <td></td>
            </tr>
            
            <tr>
              <td height="22"></td>
              <td valign="top" >Fax</td>
              <td valign="top">
                <input name="txt1faxempsi" type="text" id="txt1faxempsi" size="50" value = "<?php  echo $filreg["faxemp"];?>"maxlength="100" /></td>
              <td colspan="3" rowspan="2" valign="top"><input name="img1fileno" type="file" id="img1fileno" />
                <span class="textonegro">
                <input name="cambiarno" type="submit" id="cambiarno" value="Actualizar"/>
                <br>
                <?php
				if (isset($_POST['cambiarno'])){

					//Verifico si se inserta imagen de la publicaci&oacute;n
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
							echo "&iexcl;El tipo de archivo no es permitido!";
							$continua = FALSE;			  
						}
			   		   	if($continua){ //2
			      			// validar tama&ntilde;o de archivo	   
							if  ($file_size > 8368308) //bytes = 8368308 = 8392kb = 8Mb
							/*Copia el archivo en una directorio espec&iacute;fico del servidor*/
							{
								echo "&iexcl;El archivo debe ser inferior a 8MB!";						
								$continua = FALSE;				
							}
							if ($continua){ //3
								//Tomamos la extension
							   $getExt = explode ('.', $file_name);
							   $file_ext = $getExt[count($getExt)-1];  

								//Ruta donde guardamos los manuales
								$ruta = "../../images";
							
								//consulto ultimo codigo de fotografia insertado para nombre de la imagen siguiente 
								$nombre_nuevoarc = "fondo.".$file_ext;	
								move_uploaded_file($file_tmp,"$ruta/$nombre_nuevoarc");
							
							 	$qryact = "UPDATE licusu SET imgfon = '$nombre_nuevoarc'";
						 		$resact = mysql_query($qryact, $enlace);
						  
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
              <td height="28"></td>
              <td ></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="35"></td>
              <td>&nbsp;</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </table></td>
          <td>&nbsp;</td>
        </tr>
		<script language="javascript" type="text/javascript">
		hex_a_decimal(form1);  
		</script>
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