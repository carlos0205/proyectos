<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'pagsiteedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//capturo codigo de evento
$cod = $_GET["cod"];

$qrypagina = "SELECT * FROM pagsite WHERE codpag = '$cod' ";
$respagina = mysql_query($qrypagina, $enlace);
$filpagina = mysql_fetch_assoc($respagina);

?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<script type="text/javascript"  src="general/validaform.js"></script>
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
              <td width="8" height="20"></td>
                  <td width="278">&nbsp;</td>
                  <td width="807"></td>
                  <td width="10">&nbsp;</td>
                  <td width="62" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="marcotabla">
                      <!--DWLayoutTable-->
                      <tr>
                        <td width="60" height="57" align="center" valign="middle" class="textonegro" onMouseOver="dentro(this)" onMouseOut="fuera(this)"><img src="../images/save_f2.png" width="32" height="32" /><br/>
                          <input name="guardar" type="submit" class="boton" id="guardar" value="Guardar" onClick="if (valida_texto(form1.txtnom.value,'el campo nombre del enlace')==false) {return false}"/>               </td>
                      </tr>
                  </table></td>
                  <td width="27">&nbsp;</td>
                  <td width="62" rowspan="3" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="marcotabla">
                      <!--DWLayoutTable-->
                      <tr>
                        <td width="60" height="57" align="center" valign="middle" class="textonegro" onMouseOver="dentro(this)" onMouseOut="fuera(this)"><img src="../images/publish_f2.png" width="32" height="32" /><br/>
                          <input name="aplicar" type="submit" class="boton" id="aplicar" value="Aplicar"  onclick="if (valida_texto(form1.txtnom.value,'el campo nombre del enlace')==false) {return false}"/>               </td>
                      </tr>
                  </table></td>
                  <td width="14">&nbsp;</td>
                  <td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="14">&nbsp;</td>
            </tr>
            <tr>
              <td height="15"></td>
                  <td valign="top" >Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
            </tr>
            <tr>
              <td height="26" colspan="3" valign="top" class="textoerror"><div align="right">
                <?php


//boton guardar cambios
if (isset($_POST['guardar']))
{
	
	$nom = $_POST["txtnom"];
	
	$qrypagina = "SELECT codpag FROM pagsite WHERE nompag = '$nom' AND codpag <> '$cod'";
	$respagina = mysql_query($qrypagina);
	$numpagina = mysql_num_rows($respagina);
	if ($numpagina > 0){
	echo "El pagina ya existe el la base de datos";
	}else{
	//actualizo tipo de cliente
	$qrypaginaact="UPDATE pagsite  SET nompag='$nom' WHERE codpag = '$cod' ";
	
	$respaginaact=mysql_query($qrypaginaact,$enlace);
	//refresco contenido
	echo '<script language = JavaScript>
		location = "pagsite.php";
		</script>';
	
	}

}

//boton aplicar cambios
if (isset($_POST['aplicar']))
{
	$nom = $_POST["txtnom"];
	
	$qrypagina = "SELECT codpag FROM pagsite WHERE nompag = '$nom' AND codpag <> '$cod'";
	$respagina = mysql_query($qrypagina);
	$numpagina = mysql_num_rows($respagina);
	if ($numpagina > 0){
	echo "El pagina ya existe el la base de datos";
	}else{
	//actualizo tipo de cliente
	$qrypaginaact="UPDATE pagsite  SET nompag='$nom' WHERE codpag = '$cod' ";
	
	$respaginaact=mysql_query($qrypaginaact,$enlace);

		  
?>
	   <script language="javascript1.2" type="text/javascript">
		location = "pagsiteedi.php?cod=<?php echo $cod ?>";
		</script>';
<?php
	}
 } 
 
 
//boton cancelar cambios
if (isset($_POST['cancelar']))
{
echo '<script language = JavaScript>
location = "pagsite.php";
</script>';
}
 ?>                
              </div></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
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
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/paginassitio.png" width="48" height="48" align="absmiddle" />EDICION  PAGINA DEL SITIO WEB <strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
                </strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="96">&nbsp;</td>
          <td valign="top"><table width="58%" height="114" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="11" height="15"></td>
                <td width="798"></td>
                <td width="244"></td>
            </tr>
            <tr>
              <td height="26"></td>
              <td valign="top" ><p>Pagina 
                <input name="txtnom" type="text" id="txtnom" size="40" value = "<?php  echo $filpagina["nompag"];?>"maxlength="50" />
              </p></td>
                  <td></td>
            </tr>
            <tr>
              <td height="71"></td>
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