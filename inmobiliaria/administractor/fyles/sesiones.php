<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'sesiones.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();


//capturo codigo de grupo


if ($_SESSION["grupo"] <> 1){

echo '<script language = JavaScript>
		location = "usugru.php";
		</script>';
}

?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<style type="text/css">

#programas{
	position:relative;
	left: 0px;
	width: 100%;
	height: 150;
	top: 0px;
	overflow: scroll;
	overflow-x:hidden; 
	
}


</style>
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
	  <form id="form1" name="form1" method="post" action=""  onsubmit="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="61" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="6" height="20"></td>
                  <td width="1068">&nbsp;</td>
                  <td width="25"></td>
            </tr>
            <tr>
              <td height="15"></td>
              <td valign="top" >Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
            </tr>
            
            <tr>
              <td height="26" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php
 

//boton cancelar cambios
if (isset($_POST['cancelar']))
{
echo '<script language = JavaScript>
location = "usugru.php";
</script>';
}
 ?>                
              </div></td>
                  <td>&nbsp;</td>
            </tr>
            
              </table></td>
        </tr>
        <tr>
          <td width="4" height="25">&nbsp;</td>
          <td width="1101">&nbsp;</td>
          <td width="10">&nbsp;</td>
        </tr>
        
        <tr>
          <td height="52">&nbsp;</td>
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/sesiones.png" width="48" height="48" align="absmiddle" /> Sesiones Activas   <strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
                [ Lista ] </strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="228">&nbsp;</td>
          <td valign="top"><table width="58%" height="234" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="10" height="35"></td>
                  <td width="934"></td>
                  <td width="145"></td>
                  <td width="10"></td>
              </tr>
            
            
            
            <tr>
              <td height="26"></td>
                <td align="right" valign="top" class="textoerror"><?php
if (isset($_POST['eliminar']))		
{		
				
	if(!empty($_POST['sesion'])) {
	
	function array_envia($codusu) { 

    $tmp = serialize($codusu); 
    $tmp = urlencode($tmp); 

    return $tmp; 
	} 
	
	$codusu=array_values($_POST['sesion']); 
	$codusu=array_envia($codusu); 

?>
            <script type="text/javascript" language="javascript1.2">
			var entrar = confirm("&iquest;Desea Eliminar los registros seleccionados?")
			if ( entrar ) 
			{
			location = "sesionesbor.php?codusu=<?php echo $codusu?>"	
			}
			</script>
                  <?php
	
	}
	else
	{
	echo "Seleccione los programas que desea eliminar";
	}
	
}
?></td>
                <td align="right" valign="top"><span class="textonegro">
                  <input name="eliminar" type="submit" id="eliminar" value="Eliminar" />
                  </span></td>
                <td></td>
              </tr>
            <tr>
              <td height="156"></td>
                <td colspan="2" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="marcotabla">
                  <!--DWLayoutTable-->
                  <tr>
                    <td width="100%" height="79" valign="top"><div id="programas"><?php
				 
					
					$qryses1 = "SELECT u.logusu, u.nomusu, s.codusu FROM usuadm u, sesiones s WHERE s.invitado = 1 AND s.codusu = u.codusuadm ";
					
					$resses1 = mysql_query($qryses1, $enlace);
					$numses1 = mysql_num_rows($resses1);
					if($numses1 > 0){
						echo "<table class=textonegro width = 100%>";
						echo "<tr>";
						while ($filses1 = mysql_fetch_array($resses1)){
						echo"<tr>";
						echo "<td width = 10><input type='checkbox' name='sesion[]' value=".$filses1['codusu']." /></td>";
						echo "<td width = 250>".$filses1["nomusu"]."</td>";
						echo "<td>administractor</td>";
						echo "</tr>";
						}
					echo "</tr>";
					echo "</table>";
					}
					
				  ?></div></td>
                      </tr>
                  
                  </table></td>
                <td></td>
              </tr>
            <tr>
              <td height="15"></td>
                <td></td>
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