<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php");
include("fckeditor/fckeditor.php") ;
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'info.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();


$qryreg= "SELECT * FROM metatags";
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

					actualizar("metatags",2,"1","codemp","metatags.php");
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
                  <td width="1390" height="52" valign="top" class="titulos"><img src="../images/workstation1.png" width="48" height="48" align="absmiddle" /> Informaci&oacute;n de Metatags <strong></strong></td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td height="577">&nbsp;</td>
            <td valign="top"><table width="100%" border="0" align="center" cellpadding="2" cellspacing="5" bgcolor="#F5F5F5" class="marcotabla">
              <!--DWLayoutTable-->
      <tr>
        <td width="975" height="39"><strong>TITULO</strong>:La etiqueta del T&iacute;tulo   no debe contener m&aacute;s de 70 caracteres.<br />
          <label>
            <input type="text" name="txt1titulosi" id="txt1titulosi" size="50" value="<?php echo $filreg["titulo"]?>" title="título" />
          </label>        </td></tr>
       <tr>
         <td height="39"><strong>AUTOR</strong>:La etiqueta del autor   es la persona propietaria del sitio web.<br />
           <label>
             <input type="text" name="txt1autorsi" id="txt1autorsi" size="50" value="<?php echo $filreg["autor"]?>" title="autor" />
           </label>        </td></tr>
      <tr>
        <td height="39"><strong>TEMA</strong>:Esta etiqueta define la   tem&aacute;tica de tu sitio web: Negocios,   m&uacute;sica, motor, ... Hasta 100 caracteres.<br />
          <label>
            <input type="text" name="txt1temasi" id="txt1temasi" size="50" value="<?php echo $filreg["tema"]?>" title="tema" />
          </label>        </td></tr>
       <tr>
         <td height="52"><strong>DESCRIPCION</strong>:Descripci&oacute;n del sitio   web, hasta 150 caracteres (generalmente   de 200 a 250 caracteres pueden ser indexados, aunque solo se mostrar&aacute;   una peque&ntilde;a proporci&oacute;n).<br />
           <label>
             <textarea name="txt1descripcionsi" cols="100" id="txt1descripcionsi" title="descripci&oacute;n"><?php echo $filreg["descripcion"]?></textarea>
           </label>        </td></tr>
       <tr>
         <td height="39"><strong>CALIFICACION</strong>:La etiqueta de   Clasificaci&oacute;n es similar a la de descripci&oacute;n   pero en m&aacute;s detalle.<br />
           <label>
             <textarea name="txt1clasificacionsi" cols="100" id="txt1clasificacionsi"><?php echo $filreg["clasificacion"]?></textarea>
           </label>        </td></tr>
       <tr>
         <td height="39"><strong>PALABRAS</strong>:Palabras significativas   sobre tu sitio web, palabras clave que   la gente utilizar&aacute; para encontrar tus p&aacute;ginas. Hasta 200 caracteres.<br />
           <label>
             <textarea name="txt1palabrassi" cols="100" id="txt1palabrassi" title="palabras clave"><?php echo $filreg["palabras"]?></textarea>
           </label>        </td></tr>
       <tr>
         <td height="39"><strong>LOCALIDAD</strong>:Pa&iacute;s o territorio de   donde procede la web.<br />
           <label>
             <input type="text" name="txt1localidadsi" id="txt1localidadsi" size="50" title="localidad" value="<?php echo $filreg["localidad"]?>" />
           </label>        </td></tr>
       <tr>
         <td height="39"><strong>IDIOMA</strong>:Idioma utilizado en el   sitio: Spanish, English, French...<br />
           <label>
             <input type="text" name="txt1idiomasi" id="txt1idiomasi"  title="idiomas del sitio" size="50" value="<?php echo $filreg["idioma"]?>" />
           </label>        </td></tr>
       <tr>
         <td height="52"><strong>EXPIRA</strong>:Utiliza			      &quot;never&quot;   (nunca) a menos que tu sitio tenga   fecha de caducidad. (Ej: Un sitio que ofrezca una oferta de tiempo   limitado no le interesa que pasado el l&iacute;mite de tiempo su oferta siga   apareciendo en los buscadores).(Formato debe ser como este  y en ingl&eacute;s.   &quot;Tue, 18 Apr 2006 14:57:09 GMT&quot;
<label>
    <br />
    <input type="text" name="txt1expirasi" title="expira" id="txt1expirasi" size="50" value="<?php echo $filreg["expira"]?>" />
  </label>        </td></tr>
       <tr>
         <td height="39"><strong>CONTROL CACHE</strong>:Nivel del control del   cache.<br />
           <select name="cbo1controlcachesi" id="cbo1controlcachesi" title="Control de Cache">
             <?php
					  $qrypub="SELECT 'PUBLIC' AS publica
						UNION
						SELECT 'PRIVATE' AS publica
						UNION
						SELECT 'NO-CACHE' AS publica
						UNION
						SELECT 'NO-STORE' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['controlcache']."\">".$filreg['controlcache']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["controlcache"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
                                 </select></td></tr>
       <tr>
         <td height="17"></td>
         <tr>
         <td height="37"><strong>ESTADO CACHE</strong>:Esta directiva indica   que la informaci&oacute;n guardada en cache no   debe ser usada.<br />
           <select name="cbo1estadocachesi" id="cbo1estadocachesi" title="Estado de Cache">
             <?php
					  $qrypub="SELECT 'NO-CACHE' AS publica
						UNION
						SELECT '' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['estadocache']."\">".$filreg['estadocache']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["estadocache"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
                                 </select></td></tr>
       <tr>
         <td height="39"><strong>COPYRIGHT</strong>:Quien es el propietario   del sitio, Ej: Nombre Compa&ntilde;&iacute;a<br />
           <label>
             <input type="text" name="txt1copyrightsi" id="txt1copyrightsi" size="50" value="<?php echo $filreg["copyright"]?>" />
           </label>        </td></tr>
       <tr>
         <td height="39"><strong>DISE&Ntilde;ADOR</strong>:Nombre del Webmaster <br />
           <label>
           <input type="text" name="txt1disennadorsi" id="txt1disennadorsi" size="50" value="<?php echo $filreg["disennador"]?>" />
         </label>        </td></tr>
       <tr>
         <td height="39"><strong>PUBLICADO</strong>:Propietario, Webmaster,   Nombre de empresas.<br />
           <label>
             <input type="text" name="txt1publicadosi" id="txt1publicadosi" size="50" value="<?php echo $filreg["publicado"]?>" />
           </label>        </td></tr>
       <tr>
         <td height="39"><strong>REVISITAR</strong>:Define con que   frecuencia quieres que los buscadores te   vuelvan a visitar. (Ej. 21 days)<br />
           <label>
             <input type="text" name="txt1revisitarsi" id="txt1revisitarsi" size="50" value="<?php echo $filreg["revisitar"]?>" />
           </label>        </td></tr>
      <tr>
        <td height="39"><strong>DISTRIBUCION</strong>:Utilizar [Global] a   menos que sea un sitio &uacute;nicamente de   &aacute;mbito local [Local].<br />
          <select name="cbo1distribucionsi" id="cbo1distribucionsi" title="Distribución">
            <?php
					  $qrypub="SELECT 'Global' AS publica
						UNION
						SELECT 'Local' AS publica
						UNION
						SELECT 'UI' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['distribucion']."\">".$filreg['distribucion']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["distribucion"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
                              </select></td>
        </tr>
      <tr>
        <td height="39"><strong>ROBOTS</strong>:Los valores ALL y NONE   establecen todas las directivas on o   off: [ALL=INDEX,FOLLOW] y [NONE=NOINDEX,NOFOLLOW].<br />
          <select name="cbo1robotssi" id="cbo1robotssi" title="Robots">
            <?php
					  $qrypub="SELECT 'INDEX,FOLLOW' AS publica
						UNION
						SELECT 'NOINDEX,NOFOLLOW' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['robots']."\">".$filreg['robots']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["robots"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
                              </select></td>
      </tr>
      

       <tr>
         <td height="39"><strong>CIUDAD</strong>:Tu ciudad <br />
         <label>
           <input type="text" name="txt1ciudadsi" id="txt1ciudadsi" size="50" value="<?php echo $filreg["ciudad"]?>" />
         </label>        </td></tr>
       <tr>
         <td height="39"><strong>PAIS</strong>:Tu pa&iacute;s.<br />
           <label>
             <input type="text" name="txt1paissi" id="txt1paissi" size="50" value="<?php echo $filreg["pais"]?>" />
           </label>        </td></tr>
      
        <tr>
          <td height="8"></td>
          </tr>
        <tr>
          <td height="32" valign="top"><strong>ESTADO</strong>: Estado delos Metatags<br />
            <select name="cbo2estadosi" id="cbo2estadosi" title="Estado Metatags">
              <?php
					  $qrypub="SELECT 'Activo' AS publica
						UNION
						SELECT 'Inactivo' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['estado']."\">".$filreg['estado']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["estado"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
                        </select></td>
        </tr>  
		
		 <tr>
          <td height="8"></td>
          </tr>
        <tr>
          <td height="32" valign="top"><strong>ESTADO</strong>: Maneja Google Analytics? <br />
            <select name="cbo2manejagooglesi" id="cbo2manejagooglesi" title="Maneja Google Analytics">
              <?php
					  $qrypub="SELECT 'Si' AS publica
						UNION
						SELECT 'No' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['manejagoogle']."\">".$filreg['manejagoogle']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["manejagoogle"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
                    </select></td>
        </tr>  
		 <tr>
          <td height="8"></td>
          </tr>
        <tr>
          <td height="32" valign="top"><strong>CODIGO GOOGLE ANALYTICS </strong>:<br />
            <input type="text" name="txt1codigogooglesi" id="txt1codigogooglesi" size="50" value="<?php echo $filreg["codigogoogle"]?>" /></td>
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
</body><!-- InstanceEnd -->
</html>