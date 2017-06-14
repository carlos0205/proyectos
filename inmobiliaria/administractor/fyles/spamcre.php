<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php");
sesion(1);
include("fckeditor/fckeditor.php") ;


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'spamcre.php';
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
<script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
<script language="javascript" type="text/javascript">
function crearregistro(){
var pasa = validaenvia()

	if(pasa == false ){
		return false;
	}else{
		
			
		var entrar = confirm("¿Desea crear el registro?")
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
	  <form action="" method="post" enctype="multipart/form-data" name="form1" class="textonegro" id="form1"  onSubmit="">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="textomedio">
        <!--DWLayoutTable-->
        <tr>
          <td height="61" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="6" height="20">&nbsp;</td>
                  <td width="851">&nbsp;</td>
                  <td width="52">&nbsp;</td>
                  <td width="72" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
	<td width="60" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
	<td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="60">&nbsp;</td>
            </tr>
            <tr>
              <td height="19">&nbsp;</td>
              <td valign="top" >Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            
            <tr>
              <td height="26" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php


//boton guardar cambios
if (isset($_POST['guardarno']))
{
	$ema = $_POST["txt2emaspamsi"];
	
	$qryema = "SELECT codspam FROM spam WHERE emaspam = '$ema' ";
	$resema = mysql_query($qryema);
	$numema = mysql_num_rows($resema);
	if ($numema > 0){
			echo "El e-mail ya existe el la base de datos";
	}else{
	
	$siguiente=guardar("spam",1,"codspam",2);

	//refresco contenido
	echo '<script language = JavaScript>
		location = "spam.php";
		</script>';
	
	}

}

//boton aplicar cambios
if (isset($_POST['aplicarno']))
{
	
	$ema = $_POST["txt2emaspamsi"];
	
	$qryema = "SELECT codspam FROM spam WHERE emaspam = '$ema' ";
	$resema = mysql_query($qryema);
	$numema = mysql_num_rows($resema);
	if ($numema > 0){
			echo "El e-mail ya existe el la base de datos";
	}else{
	//inserto tipo de cliente
	$siguiente=guardar("spam",1,"codspam",2);
		  
?>
                <script language="javascript1.2" type="text/javascript">
		location = "spamedi.php?cod=<?php echo $siguiente ?>";
		</script>
                <?php
	}
 } 
 
 
//boton cancelar cambios
if (isset($_POST['cancelarno']))
{
echo '<script language = JavaScript>
location = "spam.php";
</script>';
}
 ?>                
              </div>
			  
			  </td>
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
          <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="textonegro" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/pqrs.png" width="48" height="48" align="absmiddle" /> Cuenta de Correo Para Mensajes Masivos <strong>
                
                [Crea]</strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="122">&nbsp;</td>
          <td valign="top"><table width="58%" height="120" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="40" height="13"></td>
                  <td width="1101"></td>
                  <td width="18"></td>
              </tr>
            <tr>
              <td height="22"></td>
              <td valign="top" class="textonegro" ><p>Nombre de Cuenta 
                <input name="txt2nomspamsi" type="text" id="txt2nomspamsi" size="60"maxlength="100" onChange="javascript:this.value=this.value.toUpperCase();" title="nombre"/>
                email 
                
                <input name="txt2emaspamsi" type="text" id="txt2emaspamsi" size="60"maxlength="100" title="e-mail" />
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
                  <select name="cbo2codtiptersi" id="cbo2codtiptersi" title="Tipo de cliente">
                    <option value="0">Elige</option>
                    <?
	
	
	if (isset($_POST['seltip'])){
		$tip=$_POST['seltip'];
		$qrytip = "SELECT * FROM tipterdet WHERE codtipter <> '$tip' AND codidi = 1 ORDER BY nomtipter ";
		$qrytip1 = "SELECT * FROM tipterdet WHERE codtipter= '$tip' AND codidi = 1";
		$restip1 = mysql_query($qrytip1,$enlace);
		$filtip1 = mysql_fetch_array($restip1);
		echo "<option selected value=\"".$filtip1['codtipter']."\">".$filtip1['nomtipter']."</option>\n";
		mysql_free_result($restip1);
	}
	else
	{
		$qrytip= "SELECT * FROM tipterdet WHERE codidi = 1 ORDER BY nomtipter ";
	}
	$restip = mysql_query($qrytip, $enlace);
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