<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php");
sesion(1);
include("fckeditor/fckeditor.php") ;


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'spamedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

$cod = $_GET["cod"];

$qryreg = "SELECT sp.*,td.nomtipter FROM spam sp 
INNER JOIN tipter AS t 
ON sp.codtipter = t.codtipter
INNER JOIN tipterdet AS td 
ON t.codtipter = td.codtipter AND td.codidi = 1
WHERE sp.codspam = '$cod' ";

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
          <td height="61" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
            <!--DWLayoutTable-->
            <tr>
              <td width="6" height="20">&nbsp;</td>
                  <td width="803">&nbsp;</td>
                  <td width="145">&nbsp;</td>
                  <td width="69" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
	<td width="65" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
	<td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="25">&nbsp;</td>
            </tr>
            <tr>
              <td height="19">&nbsp;</td>
              <td valign="top" class="textonegro" >Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
            </tr>
            
            <tr>
              <td height="26" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php


//boton guardar cambios
if (isset($_POST['guardarno']))
{
	actualizar("spam",2,$cod,"codspam","spam.php");
}

//boton aplicar cambios
if (isset($_POST['aplicarno']))
{
 	actualizar("spam",2,$cod,"codspam","spamedi.php?cod=$cod&acc=1");
} 
 

//boton cancelar cambios
if (isset($_POST['cancelarno']))
{
echo '<script language = JavaScript>
location = "spam.php";
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
          <td width="1114">&nbsp;</td>
          <td width="10">&nbsp;</td>
        </tr>
        
        <tr>
          <td height="52">&nbsp;</td>
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/pqrs.png" width="48" height="48" align="absmiddle" />  Cuenta de Correo Para Mensajes Masivos <strong>
           
                [Edita]
              </strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="122">&nbsp;</td>
          <td valign="top"><table width="58%" height="120" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="16" height="13"></td>
                  <td width="1080"></td>
                  <td width="16"></td>
              </tr>
            <tr>
              <td height="22"></td>
                <td valign="top" ><p>Nombre de Cuenta
                    <input name="txt2nomspamsi" type="text" id="txt2nomspamsi" size="60" value = "<?php echo $filreg["nomspam"] ?>"maxlength="100" onChange="javascript:this.value=this.value.toUpperCase();" title="Nombre"/>
                  email 
                  <input name="txt2emaspamsi" type="text" id="txt2emaspamsi" size="60" value = "<?php echo $filreg["emaspam"] ?>"maxlength="100"  title="e-mail"/>
                  </p></td>
                  <td></td>
              </tr>
            <tr>
              <td height="22"></td>
                <td>&nbsp;</td>
                <td></td>
              </tr>
            <tr>
              <td height="28"></td>
                <td valign="top" >Perfil<span class="textonegro">
                  <select name="cbo2codtiptersi" id="cbo2codtiptersi">
                    <?
	$tip =  $filreg["codtipter"];
	$qrytip = "SELECT * FROM tipterdet WHERE codtipter <> $tip AND codidi='1' ORDER BY nomtipter ";
	$restip = mysql_query($qrytip, $enlace);
	echo "<option selected value=\"".$filreg["codtipter"]."\">".$filreg["nomtipter"]."</option>\n";
	while ($filtip = mysql_fetch_array($restip))
	echo "<option value=\"".$filtip["codtipter"]."\">".$filtip["nomtipter"]."</option>\n";
	mysql_free_result($restip);
?>
                  </select>
                </span></td>
                <td></td>
              </tr>
            <tr>
              <td height="35"></td>
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