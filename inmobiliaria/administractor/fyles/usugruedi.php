<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);


// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'usugruedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();


//capturo codigo de grupo
$cod = $_GET["cod"];

if (($cod ==1) and ($_SESSION["grupo"] == 1)){

$qrygru = "SELECT * FROM gruusu WHERE codgru = '$cod'";
$resgru = mysql_query($qrygru, $enlace);
$filgru = mysql_fetch_assoc($resgru);

}elseif($cod <> 1){

$qrygru = "SELECT * FROM gruusu WHERE codgru = '$cod'";
$resgru = mysql_query($qrygru, $enlace);
$filgru = mysql_fetch_assoc($resgru);

}else{
echo '<script language = JavaScript>
		location = "usugru.php";
		</script>';
}

if($cod < 4 && $_SESSION["grupo"] <> 1){
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
</script>
<style type="text/css">

#programas{
	position:relative;
	left: 0px;
	width: 100%;
	height: 200;
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
              <td width="5" height="20">&nbsp;</td>
                  <td width="811">&nbsp;</td>
                  <td width="19">&nbsp;</td>
                  <td width="69" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
                  
                 <td width="57" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"  onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
                  <td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="13">&nbsp;</td>
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
	
	$nom = $_POST["txtnom"];
	
	$qrygru = "SELECT codgru FROM gruusu WHERE nomgru = '$nom' AND codgru <> '$cod'";
	$resgru = mysql_query($qrygru);
	$numgru= mysql_num_rows($resgru);
	if ($numgru > 0){
	echo "El nombre de grupo ya existe el la base de datos";
	}else{
	//actualizo grupo de usuarios
	$qrygruact="UPDATE gruusu SET nomgru='$nom' WHERE codgru = '$cod' ";
	$resgruact=mysql_query($qrygruact,$enlace);
	
	auditoria($_SESSION["enlineaadm"],'Grupos de Usuario',$cod,'4');
	//refresco contenido
	echo '<script language = JavaScript>
		location = "usugru.php";
		</script>';
	
	}

}

//boton aplicar cambios
if (isset($_POST['aplicarno']))
{
	$nom = $_POST["txtnom"];
	
	$qrygru = "SELECT codgru FROM gruusu WHERE nomgru = '$nom' AND codgru <> '$cod'";
	$resgru = mysql_query($qrygru);
	$numgru= mysql_num_rows($resgru);
	if ($numgru > 0){
	echo "El nombre de grupo ya existe el la base de datos";
	}else{
	//actualizo grupo de usuarios
	$qrygruact="UPDATE gruusu SET nomgru='$nom' WHERE codgru = '$cod' ";
	$resgruact=mysql_query($qrygruact,$enlace);
	
	auditoria($_SESSION["enlineaadm"],'Grupos de Usuario',$cod,'4');
	//refresco contenido
		  
?>
	   <script language="JavaScript1.2" type="text/javascript">
		location = "usugruedi.php?cod=<?php echo $cod ?>";
		</script>';
<?php
	}
 } 
 

//boton cancelar cambios
if (isset($_POST['cancelarno']))
{
echo '<script language = JavaScript>
location = "usugru.php";
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
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/grupousuarios.png" width="48" height="48" align="absmiddle" /> Grupos de Usuario [ Edita ] <strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
                </strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="344">&nbsp;</td>
          <td valign="top"><table width="58%" height="339" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="8" height="13"></td>
                  <td width="290"></td>
                  <td width="165"></td>
                  <td width="366"></td>
                  <td width="131"></td>
                  <td width="11"></td>
            </tr>
            <tr>
              <td height="22"></td>
              <td colspan="2" valign="top" ><p>Grupo de Usuario  
                <input name="txt2nomgrusi" type="text" id="txt2nomgrusi" size="40" value = "<?php  echo $filgru["nomgru"];?>"maxlength="50" title="Nombre grupo" />
                <br>
                <br>
              </p></td>
                  <td colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td></td>
            </tr>
            
            
            <tr>
              <td height="26"></td>
              <td valign="top" >Autorizaci&oacute;n a Programas</td>
              <td colspan="2" align="right" valign="top" class="textoerror"><?php
if (isset($_POST['asignar']))		
{		
				
	if(!empty($_POST['permitir'])) {
	
	function array_envia($codprog) { 

    $tmp = serialize($codprog); 
    $tmp = urlencode($tmp); 

    return $tmp; 
	} 
	
	$codprog=array_values($_POST['permitir']); 
	$codprog=array_envia($codprog); 

?>
	<script type="text/javascript" language="javascript1.2">
	location = "usugruproasi.php?codprog=<?php echo $codprog?>&cod=<?php echo $cod?>"	
	</script>
 <?php
	
	}
	else
	{
	echo "Seleccione los programas que desea asignar";
	}
	
}
?>			  </td>
              <td align="right" valign="top"><span class="textonegro">
                <input name="asignar" type="submit" id="asignar" value="Asignar" />
              </span></td>
              <td></td>
            </tr>
            <tr>
              <td height="202"></td>
              <td colspan="4" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="marcotabla">
                <!--DWLayoutTable-->
                <tr>
                  <td width="100%" height="79" valign="top"><div id="programas"><?php
	if($_SESSION["grupo"] == 1){
	$qrypro = "SELECT * FROM progweb WHERE codprog NOT IN (SELECT codprog FROM gruprog WHERE codgru = '$cod' ) ORDER BY desprog ";
	}else{
	$qrypro = "SELECT * FROM progweb WHERE codprog NOT IN (SELECT codprog FROM gruprog WHERE codgru = '$cod' ) AND tipo='Cliente' ORDER BY desprog ";
	}
	
					
					$respro = mysql_query($qrypro, $enlace);
					$numpro = mysql_num_rows($respro);
					if($numpro > 0){
						echo "<table class=textonegro width = 100%>";
						$contador=0;
						echo "<tr>";
						while ($filpro = mysql_fetch_array($respro)){
						if($contador == 5){
						$contador=0;
						echo"<tr>" ;
						echo"<td height = 10></td>";
						echo"</tr>" ;
						}
						//echo"<tr>";
						echo "<td><input type='checkbox' name='permitir[]' value=".$filpro['codprog']." /></td>";
						echo "<td>".$filpro["desprog"]."</td>";
						//echo "</tr>";
						$contador++;
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
              <td height="20"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="29"></td>
              <td valign="top" >Programas Autorizados</td>
              <td colspan="2" align="right" valign="top" class="textoerror">
<?php
if (isset($_POST['eliminar']))		
{		
				
	if(!empty($_POST['permitidos'])) {
	
	function array_envia($codpro1) { 

    $tmp = serialize($codpro1); 
    $tmp = urlencode($tmp); 

    return $tmp; 
	} 
	
	$codpro1=array_values($_POST['permitidos']); 
	$codpro1=array_envia($codpro1); 

?>
            <script type="text/javascript" language="javascript1.2">
			var entrar = confirm("¿Desea Eliminar los registros seleccionados?")
			if ( entrar ) 
			{
			location = "usugruprobor.php?codprog=<?php echo $codpro1?>&cod=<?php echo $cod?>"	
			}
			</script>
                <?php
	
	}
	else
	{
	echo "Seleccione los programas que desea eliminar";
	}
	
}
?>			  </td>
              <td align="right" valign="top"><span class="textonegro">
                <input name="eliminar" type="submit" id="eliminar" value="Eliminar" onClick="if (valida_texto(form1.txtnom.value,'el campo nombre del grupo')==false) {return false}"/>
              </span></td>
              <td></td>
            </tr>
            
            
            
            <tr>
              <td height="202"></td>
              <td colspan="4" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="marcotabla">
                <!--DWLayoutTable-->
                <tr>
                  <td width="100%" height="145" valign="top" class="textonegro"><div id="programas" ><?php
			  $qrypro = "SELECT p.codprog, p.desprog FROM progweb p, gruprog gp WHERE gp.codgru = '$cod' AND gp.codprog = p.codprog ORDER BY p.desprog";
			  $respro = mysql_query($qrypro, $enlace);
			  $numpro = mysql_num_rows($respro);
			  if ($numpro > 0)
				{	/*Recorrido de cada campo de la consulta*/
					echo "<table class=textonegro>\n";
			  $contador=0;	
			  while($filpro=mysql_fetch_assoc($respro)){
			 if($contador == 5){
				$contador=0;
				echo"<tr>" ;
				echo"<td height = 10></td>";
				echo"</tr>" ;
				}
				echo "<td><input type='checkbox' name='permitidos[]' value=".$filpro['codprog']." /></td>";
				echo "<td >".$filpro["desprog"]."</td>\n";
				
				$contador++;
			  
			  	}
				echo "</table>\n";
			  }else{
			  echo "No tiene programas asociados";
			  }
			  ?></div></td>
                      </tr>
                
                
              </table></td>
              <td></td>
            </tr>
            <tr>
              <td height="47"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
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