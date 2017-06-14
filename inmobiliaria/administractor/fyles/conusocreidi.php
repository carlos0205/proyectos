<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);
include("fckeditor/fckeditor.php") ;

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'conusocre.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();
//capturo codigo de PQRS
$cod = $_GET["cod"];
?>
<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<script type="text/javascript">
function quitar() 
{ 
//alert("No funciona"); 
return false; 
} 
document.oncontextmenu = quitar;
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
	<td width="920">&nbsp;</td>
	<td width="15"></td>
	<td width="69" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick=""><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
	<td width="57" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"  onClick=""><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
	<td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
	<td width="10"></td>
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
	function actualizar($accion){
		global $enlace;
		
		$qryconidi = mysql_query("SELECT codcondet FROM condicionesdet WHERE codidi = '".$_POST["selidi"]."' AND codcon= '".$_GET["cod"]."' AND nomcon='".$_POST["txtnom"]."'");
		$numtip = mysql_num_rows($qryconidi);
		
		if($numtip > 0){
			echo "Ya exite el Termino en el idioma seleccionado";
		}else{
			$des = $_POST["txtdes"];
			if(get_magic_quotes_gpc()){
				$des = htmlspecialchars(stripslashes($des));
			}else{
				$des = htmlspecialchars($des);
			}	
			//inserto detalle pqrs
			$qrytipinsdet="INSERT INTO condicionesdet VALUES ('0', '".$_GET["cod"]."', '".$_POST["txtnom"]."', '$des', '".$_POST["selidi"]."')";							
			$restipinsdet=mysql_query($qrytipinsdet,$enlace);
			
			if($accion==1){
				?>				
				<script language="javascript1.2" type="text/javascript">
				location = "conusoedi.php?cod=<?php echo $_GET["cod"] ?>";
				</script>
				<?php
			}else{
				?>
				<script language="javascript1.2" type="text/javascript">
				location = "conusoediidi.php?cod=<?php echo $_GET["cod"] ?>&codidi=<?php echo $_POST["selidi"] ?>";
				</script>
				<?php
			}
		}
	}
	//boton guardar cambios
	if (isset($_POST['guardarno'])){
		actualizar(1);
	}
	//boton aplicar cambios
	if (isset($_POST['aplicarno'])){
		actualizar(2);
	}
	//boton cancelar cambios
	if (isset($_POST['cancelarno'])){
	?>
	            <script language="javascript1.2" type="text/javascript">
	location = "conusoedi.php?cod=<?php echo $cod ?>";
	</script>
	<?php
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
	<td width="10">&nbsp;</td>
	<td width="1168">&nbsp;</td>
	</tr>
	<tr>
	<td height="52">&nbsp;</td>
	<td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
	<!--DWLayoutTable-->
	<tr>
	<td width="1390" height="52" valign="top" class="titulos"><img src="../images/conusu.png" width="48" height="48" align="absmiddle" />Termino o condici&oacute;n - idioma [Crea] </td>
	</tr>
	</table></td>
	</tr>
	<tr>
	<td height="329">&nbsp;</td>
	<td>&nbsp;</td>
	<td valign="top"><table width="58%" height="329" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
	<!--DWLayoutTable-->
	<tr>
	<td width="15" height="13"></td>
	<td width="1055"></td>
	<td width="17"></td>
	</tr>
	<tr>
	<td height="24"></td>
	<td valign="top" ><p>NombreTermino
	<input name="txtnom" type="text" id="txtnom" size="40" value = "<?php if (isset($_POST['txtnom'])) echo $_POST['txtnom']; ?>"maxlength="100" />
	</p></td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td height="16"></td>
	<td></td>
	<td></td>
	</tr>
	<tr>
	<td height="23"></td>
	<td valign="top" >Idioma <span class="textonegro">
	<select name="selidi" id="selidi">
	<?
	if (isset($_POST['selidi'])){
		$idi=$_POST['selidi'];
		$qryidi = "SELECT codidi, nomidi FROM idipub WHERE codidi <> '$idi' AND codidi <> '1' ORDER BY nomidi ";
		$qryidi1 = "SELECT codidi, nomidi FROM idipub WHERE codidi = '$idi' ";
		$residi1 = mysql_query($qryidi1,$enlace);
		$filidi1 = mysql_fetch_array($residi1);
		echo "<option selected value=\"".$filidi1['codidi']."\">".$filidi1['nomidi']."</option>\n";
		mysql_free_result($residi1);
	}else{
		$qryidi = "SELECT codidi, nomidi FROM idipub WHERE codidi <> '1' ORDER BY nomidi ";
	}
	$residi = mysql_query($qryidi, $enlace);
	while ($filidi = mysql_fetch_array($residi))
	echo "<option value=\"".$filidi["codidi"]."\">".$filidi["nomidi"]."</option>\n";
	mysql_free_result($residi);
	?>
	</select>
	</span></td>
	<td></td>
	</tr>
	<tr>
	<td height="16"></td>
	<td></td>
	<td></td>
	</tr>
	<tr>
	<td height="18"></td>
	<td valign="top" >Descripci&oacute;n</td>
	<td></td>
	</tr>
	<tr>
	<td height="17"></td>
	<td></td>
	<td></td>
	</tr>
	<tr>
	<td height="181"></td>
	<td valign="top"><?php
	// Automatically calculates the editor base path based on the _samples directory.
	// This is usefull only for these samples. A real application should use something like this:
	// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
	$oFCKeditor = new FCKeditor('txtdes') ;
	$oFCKeditor->BasePath = '../fyles/fckeditor/';
	if(isset($_POST['txtdes'])){
		$oFCKeditor->Value = $_POST['txtdes'] ;
	}else{
		$oFCKeditor->Value = "" ;
	}
	$oFCKeditor->Create() ;
	?></td>
	<td></td>
	</tr>
	<tr>
	<td height="19"></td>
	<td>&nbsp;</td>
	<td></td>
	</tr>
	</table></td>
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