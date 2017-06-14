<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php");
sesion(1);
include("fckeditor/fckeditor.php") ;

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'tippubedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//capturo codigo de evento
$cod = $_GET["cod"];

$qryreg = "SELECT * FROM tippub WHERE codtippub =$cod ";
$resreg = mysql_query($qryreg, $enlace);
$filreg = mysql_fetch_assoc($resreg);


?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>

<script type="text/javascript"  src="general/validaform.js"></script>
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


function creartipo(){
	if(document.form1.txt1nomtipter1no.value == "" || document.form1.cbo1codidino.value == 0){
		alert("Por favor ingrese nombre del estado e idioma")
		exit();
	}
	xajax_creartipo(xajax.getFormValues("form1"));
}

function tipos(){
	xajax_tipos();
}

function actualizatipo(codareadet, nomarea)
{
	xajax_actualizatipo(codareadet, nomarea);
}

function eliminatipo(codareadet)
{
	
	var entrar = confirm("¿Desea eliminar el registro?")
		if ( entrar ) 
		{
			xajax_eliminatipo(codareadet);
		}else{
			return false;
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
	<td width="916">&nbsp;</td>
	<td width="18"></td>
	<td width="69" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
	<td width="57" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"  onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
	<td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
	<td width="11"></td>
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
	if (isset($_POST['guardarno'])){
		actualizar("tippub",1,$cod,"codtippub","tippub.php");
	}
	//boton aplicar cambios
	if (isset($_POST['aplicarno'])){
		actualizar("tippub",2,$cod,"codtippub","tippubedi.php?cod=$cod");
	} 
	//boton cancelar cambios
	if (isset($_POST['cancelarno'])){
		echo '<script language = JavaScript>
		location = "tippub.php";
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
	<td width="973">&nbsp;</td>
	<td width="9">&nbsp;</td>
	</tr>
	<tr>
	<td height="52">&nbsp;</td>
	<td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
	<!--DWLayoutTable-->
	<tr>
	<td width="1390" height="52" valign="top" class="titulos"><img src="../images/tipopublicacion.png" width="48" height="48" align="absmiddle" />Tipo de publicaci&oacute;n [Edita] </td>
	</tr>
	</table></td>
	</tr>
	<tr>
	<td height="125">&nbsp;</td>
	<td valign="top"><table width="58%" height="62" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
	  <!--DWLayoutTable-->
	  <tr>
	    <td width="9" height="13"></td>
	      <td width="322"></td>
	      <td width="629"></td>
	      <td width="11"></td>
	  </tr>
	  <tr>
	    <td height="32"></td>
	      <td valign="top"><p>Tipo de Cliente <br>
	        <input name="txt2nomtippubsi" type="text" class="textonegro" id="txt2nomtippubsi" value = "<?php  echo $filreg["nomtippub"];?>" size="40"maxlength="100" title="Tipo de publicacion"/>
	        </p></td>
	      <td></td>
	      <td></td>
	  </tr>
	  <tr>
	    <td height="15"></td>
	        <td></td>
	        <td></td>
	        <td></td>
	  </tr>
	  <tr>
	    <td height="17"></td>
	    <td colspan="2" valign="top">Descripci&oacute;n</td>
	    <td></td>
	    </tr>
	  
	  <tr>
	    <td height="46"></td>
	    <td colspan="2" valign="top"><?php
				// Automatically calculates the editor base path based on the _samples directory.
				// This is usefull only for these samples. A real application should use something like this:
				// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
				
				$oFCKeditor = new FCKeditor('txt1destipsi') ;
				$oFCKeditor->BasePath = '../fyles/fckeditor/';
				$oFCKeditor->Value = html_entity_decode( $filreg["destip"] ) ;
				$oFCKeditor->Height	= '240';
				$oFCKeditor->Create() ;
				?></td>
	    <td>&nbsp;</td>
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