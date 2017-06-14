<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);
include("fckeditor/fckeditor.php") ;

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'comres.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

//capturo codigo de evento
$cod = $_GET["cod"];

//averiguo si usuario pertenece a grupo 9999 de responder contactos web

$qrycom = "SELECT cw.*, p.cn, d.nomdep, c.nomciu,  acd.nomarea, HOUR(TIMEDIFF(rcw.fecresweb, cw.fecconweb)) AS tieate, rcw.fecresweb, rcw.desresweb, u.nomusu , ttd.nomtipter
FROM conweb cw 
LEFT JOIN ciudad c ON cw.codciu = c.codciu
LEFT JOIN deppro AS d ON c.coddep = d.coddep
LEFT JOIN pais AS p ON d.ci = p.ci
LEFT JOIN resconweb AS rcw ON cw.codconweb = rcw.codconweb
LEFT JOIN areacon AS ac ON cw.codarea = ac.codarea
LEFT JOIN areacondet AS acd ON ac.codarea = acd.codarea AND acd.codidi = 1 
LEFT JOIN usuadm AS u ON rcw.codusuadm = u.codusuadm 
LEFT JOIN tipter AS tt ON cw.codtipter = tt.codtipter 
LEFT JOIN tipterdet AS ttd ON tt.codtipter = ttd.codtipter 
WHERE  cw.estcon = 'Respondido' AND rcw.codconweb = '$cod' ";

if ($_SESSION["grupo"] == 2){
		$qrycom = " AND u.codusuadm = ".$_SESSION["enlineaadm"]."";
	}
$rescom = mysql_query($qrycom, $enlace);

$filcom = mysql_fetch_assoc($rescom);

//consulto parametros comentario
$qrypar= "SELECT * FROM compar";
$respar = mysql_query($qrypar, $enlace);
$filpar = mysql_fetch_assoc($respar);
?>
<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
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
          <td colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="5" height="20"></td>
                  <td width="1041">&nbsp;</td>
                  <td width="29">&nbsp;</td>
                  <td width="75" rowspan="3" align="center" valign="middle" class="textonegro"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="10">&nbsp;</td>
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
				//boton cancelar cambios
				if (isset($_POST['cancelarno']))
				{
				echo '<script language = JavaScript>
				location = "comreslis.php";
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
          <td width="4">&nbsp;</td>
          <td width="1379">&nbsp;</td>
          <td width="11">&nbsp;</td>
        </tr>
        
        <tr>
          <td>&nbsp;</td>
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/contacto.png" width="48" height="48" align="absmiddle" /> Contacto Web  Antendidos   <strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
                [ Consulta ]</strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td valign="top"><table width="58%" height="393" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="10" height="13"></td>
                <td width="125"></td>
                <td width="316"></td>
                <td width="7"></td>
                <td width="31"></td>
                <td width="97"></td>
                <td width="153"></td>
                <td width="331"></td>
                <td width="32"></td>
            </tr>
            <tr>
              <td height="21"></td>
              <td valign="top" >Fecha de Contacto </td>
                <td valign="top" class="textonegro"><?php echo $filcom["fecconweb"];?>&nbsp;</td>
                <td></td>
                <td colspan="2" valign="top" <?php if($filpar["dir"] == 1){?> style=" visibility:hidden" <?php }?> >Direcci&oacute;n</td>
              <td colspan="2" valign="top" class="textonegro" <?php if($filpar["dir"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["dirconweb"];?></td>
              <td></td>
            </tr>
            
            
            <tr>
              <td height="21"></td>
              <td valign="top" ><p>Remitente</p></td>
                  <td valign="top" class="textonegro"><?php echo $filcom["nomconweb"];?></td>
                  <td></td>
                  <td colspan="2" valign="top"  <?php if($filpar["tel"] == 1){?> style=" visibility:hidden" <?php }?>>Tel&eacute;fono</td>
                  <td colspan="2" valign="top" class="textonegro"<?php if($filpar["tel"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["telconweb"];?></td>
              <td></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top"><span <?php if($filpar["ced"] == 1){?> style=" visibility:hidden" <?php }?>>Nit/C&eacute;dula</span></td>
                <td valign="top"><?php echo $filcom["nitconweb"];?></td>
                <td></td>
                <td colspan="2" valign="top"><span <?php if($filpar["mov"] == 1){?> style=" visibility:hidden" <?php }?>>Movil</span></td>
              <td colspan="2" valign="top"><?php echo $filcom["movconweb"];?></td>
              <td></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top" >e-mail</td>
              <td valign="top" class="textonegro"><?php echo $filcom["emaconweb"];?></td>
              <td></td>
              <td colspan="2" rowspan="2" valign="top"<?php if($filpar["percon"] == 1){?> style=" visibility:hidden" <?php }?>><span <?php if($filpar["percon"] == 1){?> style=" visibility:hidden" <?php }?>>Persona de Contacto</span></td>
                <td colspan="2" rowspan="2" valign="top"<?php if($filpar["percon"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["conweb"];?></td>
                <td></td>
            </tr>
            
            <tr>
              <td height="2"></td>
              <td rowspan="2" valign="top"><span <?php if($filpar["tipcli"] == 1){?> style=" visibility:hidden" <?php }?>>Tipo de Cliente </span></td>
                  <td rowspan="2" valign="top"><?php echo $filcom["nomtipter"];?></td>
                  <td></td>
                  <td></td>
            </tr>
            <tr>
              <td height="20"></td>
              <td></td>
                  <td colspan="2" valign="top" <?php if($filpar["are"] == 1){?> style=" visibility:hidden" <?php }?> >Area Contactada </td>
              <td colspan="2" valign="top"><?php echo $filcom["nomtipter"];?></td>
              <td></td>
            </tr>
            <tr>
              <td height="20"></td>
              <td valign="top"<?php if($filpar["emp"] == 1){?> style=" visibility:hidden" <?php }?>>Empresa</td>
              <td valign="top"<?php if($filpar["emp"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["empconweb"];?></td>
              <td></td>
              <td colspan="2" valign="top"   <?php if($filpar["pai"] == 1){?> style=" visibility:hidden" <?php }?>>Pa&iacute;s</td>
              <td colspan="2" valign="top" class="textonegro" <?php if($filpar["pai"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["cn"];?></td>
              <td></td>
            </tr>
            <tr>
              <td height="18"></td>
              <td rowspan="3" valign="top"<?php if($filpar["car"] == 1){?> style=" visibility:hidden" <?php }?>>Cargo</td>
              <td rowspan="3" valign="top"<?php if($filpar["car"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["carconweb"];?></td>
              <td></td>
              <td colspan="2" valign="top"  <?php if($filpar["estpro"] == 1){?> style=" visibility:hidden" <?php }?>>Estado/Provincia</td>
              <td colspan="2" rowspan="2" valign="top" class="textonegro" <?php if($filpar["estpro"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["nomdep"];?></td>
              <td></td>
            </tr>
            <tr>
              <td height="1"></td>
              <td></td>
              <td colspan="2" rowspan="3" valign="top"  <?php if($filpar["ciu"] == 1){?> style=" visibility:hidden" <?php }?>>Ciudad</td>
                  <td></td>
            </tr>
            <tr>
              <td height="1"></td>
              <td></td>
              <td colspan="2" rowspan="2" valign="top" class="textonegro" <?php if($filpar["ciu"] == 1){?> style=" visibility:hidden" <?php }?>><?php echo $filcom["nomciu"];?></td>
              <td></td>
            </tr>
            <tr>
              <td height="18"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="1"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td colspan="2" valign="top" >Comentarios</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            
            <tr>
              <td height="42"></td>
              <td colspan="2" valign="top"><?php echo html_entity_decode( $filcom["desconweb"] );?></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
            </tr>
            
            <tr>
              <td height="15"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="19"></td>
              <td valign="top" class="titmenu"><strong>Respuesta</strong></td>
              <td colspan="3" valign="top"><span class="titmenu"><strong>Fecha Respuesta:</strong></span> <?php echo $filcom["fecresweb"];?></td>
              <td colspan="2" valign="top"><span class="titmenu"><strong>Tiempo de Atenci&oacute;n:</strong></span> <?php echo $filcom["tieate"];?> Horas </td>
              <td valign="top"><span class="titmenu"><strong>Usuario que Responde:</strong></span> <?php echo $filcom["nomusu"];?></td>
              <td></td>
            </tr>
            <tr>
              <td height="16"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td height="87"></td>
              <td colspan="7" valign="top"><?php echo html_entity_decode( $filcom["desresweb"] ) ;?></td>
              <td></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td>&nbsp;</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
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