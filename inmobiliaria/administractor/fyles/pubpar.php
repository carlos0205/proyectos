<?php 
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'pubpar.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//consulto parametros del producto
$qrypar= "SELECT * FROM pubpar ";
$respar = mysql_query($qrypar, $enlace);
$filpar = mysql_fetch_assoc($respar);
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
	  <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" height="584" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="63" colspan="2" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="8" height="16"></td>
                  <td width="930"></td>
                  <td width="60"></td>
                  <td width="66" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
                  <td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="13"></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
                  <td></td>
            </tr>
            <tr>
              <td colspan="2" rowspan="2" valign="top" class="textoerror"><div align="right">
                <?php
				if (isset($_POST['aplicarno'])){
				
				actualizar("pubpar",2,"1","codemp","pubpar.php");
					}
				//boton cancelar cambios
				if (isset($_POST['cancelarno'])){
					echo '<script language = JavaScript>
					location = "index1.php";
					</script>';
				}
				?>
              </div></td>
                  <td height="24"></td>
                  <td></td>
            </tr>
            <tr>
              <td height="5"></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
         </table></td>
        </tr>
       
        <tr>
          <td width="5" height="48"></td>
          <td width="981" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="981" height="36" valign="top" class="titulos"><img src="../images/63.png" width="48" height="48" align="absmiddle" /> Par&aacute;metros de publicaciones  </td>
                </tr>
            
          </table></td>
          </tr>
      

        <tr>
          <td height="453">&nbsp;</td>
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla">
            <!--DWLayoutTable-->
            <tr>
              <td width="9" height="5"></td>
                  <td width="223"></td>
                  <td width="7"></td>
                  <td width="58"></td>
                  <td width="52"></td>
                  <td width="98"></td>
                  <td width="533"></td>
                </tr>
            <tr>
              <td height="19"></td>
                  <td></td>
                  <td></td>
                  <td valign="top" class="textonegro">Original</td>
                  <td valign="top" class="textonegro">Miniatura</td>
                  <td valign="top">Tama&ntilde;o Archivo </td>
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
                </tr>
            <tr>
              <td height="24"></td>
                  <td valign="top" class="textonegro">Introducci&oacute;n Pagina </td>
                  <td>&nbsp;</td>
                  <td valign="top" class="textonegro"><p>
                    <label></label>
                    <label></label>
                    <label></label>
                    <label></label>
                    <input name="txt2intpagorisi" type="text" id="txt2intpagorisi" value="<?php echo $filpar["intpagori"];?>" size="3" title="Introduccion pagina" />
px<br>
                  </p></td>
                  <td valign="top"><input name="txt2intpagminsi" type="text" id="txt2intpagminsi" value="<?php echo $filpar["intpagmin"];?>" size="3" title="Introduccion pagina mini" />
px</td>
                  <td>&nbsp;</td>
                  <td></td>
                </tr>
            
            <tr>
              <td height="24"></td>
                  <td valign="top" class="textonegro">Album</td>
                  <td>&nbsp;</td>
                  <td valign="top" class="textonegro"><label></label>                <label>
                    <input name="txt2alborisi" type="text" id="txt2alborisi" value="<?php echo $filpar["albori"];?>" size="3" title="Album portada" />
                  px</label></td>
                  <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                  <td></td>
                  <td></td>
                </tr>
            <tr>
              <td height="24"></td>
                  <td valign="top">Fotografias De Album</td>
                  <td>&nbsp;</td>
                  <td valign="top" class="textonegro"><label></label>                <label>
                    <input name="txt2albfotorisi" type="text" id="txt2albfotorisi" value="<?php echo $filpar["albfotori"];?>" size="3" title="Fotos de albúm" />
                  px</label></td>
                  <td valign="top"><input name="txt2albfotminsi" type="text" id="txt2albfotminsi" value="<?php echo $filpar["albfotmin"];?>" size="3" title="Fotos de albúm mini"  />
px</td>
                  <td></td>
                  <td></td>
                </tr>
            <tr>
              <td height="24"></td>
                  <td valign="top" class="textonegro">Imagen Cabezote </td>
                  <td>&nbsp;</td>
                  <td valign="top">
                    <label></label>
                    <label>
                    <input name="txt2imgcaborisi" type="text" id="txt2imgcaborisi" value="<?php echo $filpar["imgcabori"];?>" size="3" title="Imagen Cabezote" />
                    </label>
                    <label></label>
                  px</td>
                  <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                  <td></td>
                  <td></td>
                </tr>
            
            
            <tr>
              <td height="24"></td>
                  <td valign="top" class="textonegro">Noticias</td>
                  <td>&nbsp;</td>
                  <td valign="top">
                    <label>
                    <input name="txt2notorisi" type="text" id="txt2notorisi" value="<?php echo $filpar["notori"];?>" size="3" title="Foto noticias"  />
                    </label>
                    <label></label>
                  px</td>
                  <td valign="top">
                    <input name="txt2notminsi" type="text" id="txt2notminsi" value="<?php echo $filpar["notmin"];?>" size="3" title="Foto noticias mini"  />
                  px</td>
                  <td></td>
                  <td></td>
                </tr>
            <tr>
              <td height="25"></td>
                  <td valign="top">Fotografia Noticias</td>
                  <td>&nbsp;</td>
                  <td valign="top"><input name="txt2fotnotorisi" type="text" id="txt2fotnotorisi" value="<?php echo $filpar["fotnotori"];?>" size="3" title="Fotos de noticia" />
px</td>
                  <td valign="top">
                    <input name="txt2fotnotminsi" type="text" id="txt2fotnotminsi" value="<?php echo $filpar["fotnotmin"];?>" size="3"  title="Fotos de noticia mini" />
                  px</td>
                  <td></td>
                  <td></td>
                </tr>
            <tr>
              <td height="22">&nbsp;</td>
                  <td valign="top" class="textonegro">Oferta</td>
                  <td>&nbsp;</td>
                  <td valign="top"><input name="txt2ofeorisi" type="text" id="txt2ofeorisi" value="<?php echo $filpar["ofeori"];?>" size="3" title="Imagen de oferta" />
px</td>
                  <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                  <td></td>
                  <td></td>
                </tr>
            
            
            <tr>
              <td height="24"></td>
                  <td valign="top" class="textonegro"> Producto </td>
                  <td>&nbsp;</td>
                  <td valign="top">              
                    
                    <label>
                    <input name="txt2proorisi" type="text" id="txt2proorisi" value="<?php echo $filpar["proori"];?>" size="3" title="Foto de Producto" />
                    </label>
                    <label></label>
                    
                  px
                  <td valign="top">                              
                    <input name="txt2prominsi" type="text" id="txt2prominsi" value="<?php echo $filpar["promin"];?>" size="3" title="Foto de Producto mini" />
                    
                  px
                  <td></td>
                  <td></td>
                </tr>
            
            
            <tr>
              <td height="24"></td>
                  <td valign="top">Clasificaci&oacute;n de Productos </td>
                  <td>&nbsp;</td>
                  <td valign="top">                                                                                                                                          
                    
                    <label>
                    <input name="txt2linorisi" type="text" id="txt2linorisi" value="<?php echo $filpar["linori"];?>" size="3" title="Foto de clasificacion"  />
                    </label>
                    <label></label>
                    
                  px
                  <td valign="top">                                                                                                                                            
                    <input name="txt2linminsi" type="text" id="txt2linminsi" value="<?php echo $filpar["linmin"];?>" size="3"  title="Foto de clasificacion mini"/>
                        
                  px
                  <td></td>
                  <td></td>
                </tr>

            <tr>
              <td height="24"></td>
                  <td valign="top">Fabricantes</td>
                  <td>&nbsp;</td>
                  <td valign="top">                                                                                    
                    <input name="txt2faborisi" type="text" id="txt2faborisi" value="<?php echo $filpar["fabori"];?>" size="3" title="Foto de fabricante" />
                  <td>                
                  <td></td>
                  <td></td>
                </tr>
            
            
            <tr>
              <td height="25"></td>
                  <td valign="top">Regionales</td>
                  <td>&nbsp;</td>
                  <td valign="top">                              
                      
                    <input name="txt2sucfotorisi" type="text" id="txt2sucfotorisi" value="<?php echo $filpar["sucfotori"];?>" size="3" title="Foto de sucursal" />
px
                  <td valign="top">
                    <input name="txt2sucfotminsi" type="text" id="txt2sucfotminsi" value="<?php echo $filpar["sucfotmin"];?>" size="3" title="Foto de sucursal mini" />
                
                  px
                  <td></td>
                  <td></td>
                </tr>
            
            
            <tr>
              <td height="24"></td>
                  <td valign="top">Video</td>
                  <td>&nbsp;</td>
                  <td colspan="2" valign="top">                                    
                      
                    <input name="txt2vidorisi" type="text" id="txt2vidorisi" value="<?php echo $filpar["vidori"];?>" size="3" title="Foto de portada video"  />
px
                  <td valign="top"><input name="txt2vidtamsi" type="text" id="txt2vidtamsi" value="<?php echo $filpar["vidtam"];?>" size="3" title="tamaño archivo video"  />
                  MB</td>
                  <td></td>
                </tr>
            
            
            
            
            
            
            
            <tr>
              <td height="22"></td>
                  <td valign="top">Proyectos</td>
                  <td>&nbsp;</td>
                  <td valign="top">              
                    <input name="txt2proyecorisi" type="text" id="txt2proyecorisi" value="<?php echo $filpar["proyecori"];?>" size="3"  title="Foto de proyecto" />
px                            
                  <td valign="top">              
                    <input name="txt2proyecminsi" type="text" id="txt2proyecminsi" value="<?php echo $filpar["proyecmin"];?>" size="3"  title="Foto de proyecto mini"/>
                    
                  px
                  <td>            
                  <td></td>
                </tr>
 
            <tr>
              <td height="22"></td>
                  <td valign="top">Proyectos Logo </td>
                  <td>&nbsp;</td>
                  <td valign="top">                        <input name="txt2proyeclogorisi" type="text" id="txt2proyeclogorisi" value="<?php echo $filpar["proyeclogori"];?>" size="3"  title="Foto de logo proyecto" />
px              
                  <td valign="top">                        
                    <input name="txt2proyeclogminsi" type="text" id="txt2proyeclogminsi" value="<?php echo $filpar["proyeclogmin"];?>" size="3"  title="Foto de logo proyecto mini" />
                                
                  px
                  <td></td>
                  <td></td>
                </tr>
            
            <tr>
              <td height="22"></td>
                  <td valign="top" class="textonegro">Proyectos - Vistas </td>
                  <td>&nbsp;</td>
                  <td valign="top"><input name="txt2proyecvisorisi" type="text" id="txt2proyecvisorisi" value="<?php echo $filpar["proyecvisori"];?>" size="3" title="Fotos de proyecto" />
px
                  <td valign="top">
                    <input name="txt2proyecvisminsi" type="text" id="txt2proyecvisminsi" value="<?php echo $filpar["proyecvismin"];?>" size="3"  title="Fotos de proyecto mini" />
                    
                  px
                  <td></td>
                  <td></td>
                </tr>
            <tr>
              <td height="22"></td>
                  <td valign="top">Servicos</td>
                  <td></td>
                  <td valign="top"><input name="txt2serorisi" type="text" id="txt2serorisi" value="<?php echo $filpar["serori"];?>" size="3"  title="Foto de Servicio"/>
px            
                  <td valign="top">
                    <input name="txt2serminsi" type="text" id="txt2serminsi" value="<?php echo $filpar["sermin"];?>" size="3" title="Foto de Servicio mini" />
                                
                  px
                  <td></td>
                  <td></td>
                </tr>
            <tr>
              <td height="22"></td>
                  <td valign="top" class="textonegro">Enlaces</td>
                  <td></td>
                  <td valign="top"><input name="txt2enlorisi" type="text" id="txt2enlorisi" value="<?php echo $filpar["enlori"];?>" size="3"  title="Foto de Enlace"/>
px            
                  <td valign="top">
                    <input name="txt2enlminsi" type="text" id="txt2enlminsi" value="<?php echo $filpar["enlmin"];?>" size="3" title="Foto de Enlace min" />
                                
                  px
                  <td></td>
                  <td></td>
                </tr>
            <tr>
              <td height="12"></td>
                  <td></td>
                  <td></td>
                  <td>            
                  <td>            
                  <td></td>
                  <td></td>
                </tr>
            
          </table></td>
          </tr>
        <tr>
          <td height="19">&nbsp;</td>
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
