<?php
session_start();
include("general/conexion.php") ;


//XAJAX

//incluímos la clase ajax 
require ('xajax/xajax_core/xajax.inc.php');
//instanciamos el objeto de la clase xajax 
$xajax = new xajax();

$xajax->configure('javascript URI', 'xajax/');



// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'ciuedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

function verdepartamentos($ci){
	global $enlace;
	$respuesta = new xajaxResponse();
	
	$qrydep = "SELECT coddep, nomdep FROM deppro WHERE ci = $ci";
	$resdep = mysql_query($qrydep, $enlace);
	
	$lis="<select  name='cbo2coddepsi' class='textonegro' id='cbo2coddepsi'  title='Departamento' onChange='xajax_verciudades(this.value)'>";
	$lis.="<option value=0>Elige</option>";
		while($fildep=mysql_fetch_assoc($resdep))
		{
			$lis.="<option value='".$fildep["coddep"]."'>".$fildep["nomdep"]."</option>";
		}
  	$lis.="</select>";
	
	$respuesta->assign("departamento","innerHTML",$lis);
	return $respuesta;
}


//El objeto xajax tiene que procesar cualquier petición  
$xajax->registerFunction("verdepartamentos"); 
//El objeto xajax tiene que procesar cualquier petición 
$xajax->processRequest(); 


//capturo codigo de paiea
$cod = $_GET["cod"];

//consulto informacion de subgrupo
$qryreg = "SELECT c.*, d.nomdep, p.* FROM ciudad c, deppro d, pais p WHERE c.codciu = '$cod' AND c.coddep = d.coddep AND d.ci = p.ci";
$resreg = mysql_query($qryreg,$enlace);
$filreg = mysql_fetch_array($resreg);

include("general/sesion.php");
include("general/operaciones.php");
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

<script type="text/javascript">
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
<!-- InstanceEndEditable --><!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
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
                  <td width="900">&nbsp;</td>
                  <td width="29">&nbsp;</td>
				  <td width="69" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
                  
                  <td width="57" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
                  <td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="16">&nbsp;</td>
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
					auditoria($_SESSION["enlineaadm"],'Ciudad',$siguiente,'4');
					actualizar("ciudad",2,$cod,"codciu","ciu.php");
				}
				//boton aplicar cambios
				if (isset($_POST['aplicarno'])){
					auditoria($_SESSION["enlineaadm"],'Ciudad',$siguiente,'4');
					actualizar("ciudad",2,$cod,"codciu","ciuedi.php?cod=$cod");
				}
				//boton cancelar cambios
				if (isset($_POST['cancelarno'])){
					echo '<script language = JavaScript>
					location = "ciu.php";
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
          <td width="1037">&nbsp;</td>
          <td width="11">&nbsp;</td>
        </tr>
        
        <tr>
          <td height="52">&nbsp;</td>
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/div.png" width="48" height="48" align="absmiddle" />Ciudad [Edita]  <strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
                </strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="187">&nbsp;</td>
          <td valign="top"><table width="58%" height="159" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="6" height="13"></td>
                  <td width="410"></td>
                  <td width="407"></td>
                  <td width="146"></td>
            </tr>
            <tr>
              <td height="35"></td>
              <td colspan="2" valign="top" ><p>Nombre de Ciudad
                      <br>
                      <input name="txt2nomciusi" type="text" id="txt2nomciusi" size="40" onChange="javascript:this.value=this.value.toUpperCase();" value = "<?php echo $filreg["nomciu"]; ?>"maxlength="100" title="Ciudad" />
                  </p></td>
                  <td></td>
            </tr>
            <tr>
              <td height="32"></td>
              <td>&nbsp;</td>
              <td></td>
              <td></td>
            </tr>
            
            

            <tr>
              <td height="22"></td>
                  <td valign="top">Pa&iacute;s</span></td>
                  <td valign="top">Departamento</td>
                  <td></td>
            </tr>
            <tr>
              <td height="22"></td>
                  <td valign="top" ><select  name="cbo1cino" class="textonegro" id="cbo1cino" title="Pa&iacute;s" onChange="xajax_verdepartamentos(this.value)">
                    <?php 
	  $qrypais = "SELECT p.ci, p.cn FROM pais AS p WHERE ci <> ".$filreg["ci"]." ORDER BY p.cn";
	  $respais = mysql_query($qrypais, $enlace);
	 	
		echo'<option value="'.$filreg["ci"].'">'.$filreg["cn"].'</option>';

		while($filpais=mysql_fetch_assoc($respais))
		{
			echo "<option value='".$filpais["ci"]."'>".$filpais["cn"]."</option>";
		}
	  ?>
                  </select></td>
                  <td valign="top" id="departamento"><span class="textonegro">
                    <select  name="cbo2coddepsi" class="textonegro" id="cbo2coddepsi" title="Departamento" onChange="xajax_verciudades(this.value)" >
                      <?php
	if($filreg["codciu"]==0){
		echo'<option value="0">Elige</option>';
	}else{
		$qrydep = "SELECT * FROM deppro AS d WHERE ci = ".$filreg["ci"]." AND coddep <> ".$filreg["coddep"]." ORDER BY nomdep";
		$resdep= mysql_query($qrydep, $enlace);
		echo'<option value="'.$filreg["coddep"].'">'.$filreg["nomdep"].'</option>';
		while($fildep=mysql_fetch_assoc($resdep))
		{
			echo "<option value='".$fildep["coddep"]."'>".$fildep["nomdep"]."</option>";
		}
	}
	 
	?>
                    </select>
                  </span></td>
                  <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="33"></td>
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