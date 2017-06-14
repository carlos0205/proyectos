<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'depprocre.php';
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
<script type="text/javascript"  src="general/validaform.js"></script>
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
	  <form id="form1" name="form1" method="post" action=""  onSubmit="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="61" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="6" height="20"></td>
                  <td width="862">&nbsp;</td>
                  <td width="34">&nbsp;</td>
				  <td width="69" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
<td width="57" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"   onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
	<td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>                  <td width="14">&nbsp;</td>
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
				$siguiente = guardar(substr(basename($_SERVER['PHP_SELF']),0,-7),1,"coddep",2);
				auditoria($_SESSION["enlineaadm"],'Departamentos',$siguiente,'3');
					echo '<script language = JavaScript>
				location = "deppro.php";
				</script>';
			}
			
			//boton aplicar cambios
			if (isset($_POST['aplicarno'])){
				$siguiente = guardar(substr(basename($_SERVER['PHP_SELF']),0,-7),2,"coddep",2);
				auditoria($_SESSION["enlineaadm"],'Departamentos',$siguiente,'3');
				?><script language = JavaScript>
				location = "depproedi.php?cod=<?php echo $siguiente ?>&acc=1";
				</script>
				<?php
			}
			//boton cancelar cambios
			if (isset($_POST['cancelarno'])){
				echo '<script language = JavaScript>
				location = "deppro.php";
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
          <td width="1087">&nbsp;</td>
          <td width="11">&nbsp;</td>
        </tr>
        <tr>
          <td height="52">&nbsp;</td>
          <td valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1087" height="52" valign="top" class="titulos"><img src="../images/div.png" width="48" height="48" align="absmiddle" /> Departamentos [ Crea ] <strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
                </strong></td>
                </tr>
          </table></td>
          <td>&nbsp;</td>
        </tr>
        
        <tr>
          <td height="197">&nbsp;</td>
          <td valign="top"><table width="58%" height="172" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="9" height="13"></td>
                  <td width="390"></td>
                  <td width="354"></td>
                  <td width="217"></td>
                </tr>
            <tr>
              <td height="24"></td>
                  <td valign="top" ><p>Nombre del Departamento
                      <br>
                      <input name="txt2nomdepsi" type="text" id="txt2nomdepsi" size="40" onChange="javascript:this.value=this.value.toUpperCase();"maxlength="100" title="Departamento" />
                  </p></td>
                  <td valign="top" ><!--DWLayoutEmptyCell-->&nbsp;</td>
                  <td></td>
                </tr>
            <tr>
              <td height="26"></td>
                  <td>&nbsp;</td>
                  <td></td>
                  <td></td>
                </tr>
            <tr>
              <td height="19"></td>
                  <td colspan="2" valign="top" >Pa&iacute;s al que pertenence<br> 
                    <select name="cbo2cisi" id="cbo2cisi" title="Pais">
                      <option value="0">Elige</option>
                      <?
					if (isset($_POST['selpai'])){
						$pai=$_POST['selpai'];
						$qrypai = "SELECT p.ci, p.cn FROM pais p WHERE pd.ci <> '$pai' ORDER BY p.cn ";
						$qrypai1 = "SELECT p.ci, p.cn FROM pais p  WHERE p.ci ='$pai' ";
						$respai1 = mysql_query($qrypai1,$enlace);
						$filpai1 = mysql_fetch_array($respai1);
						echo "<option selected value=\"".$filpai1['ci']."\">".$filpai1['cn']."</option>\n";
						mysql_free_result($respai1);
					}else{
						$qrypai = "SELECT p.ci, p.cn FROM pais p  ORDER BY p.cn";
					}
					$respai = mysql_query($qrypai, $enlace);
					while ($filpai = mysql_fetch_array($respai))
						echo "<option value=\"".$filpai["ci"]."\">".$filpai["cn"]."</option>\n";
						mysql_free_result($respai);
				?>
                    </select></td>
                  <td></td>
                </tr>
            <tr>
              <td height="86"></td>
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