<?php 
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'spamimp.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

function comprobar_email($email){ 
    $mail_correcto = 0; 
    //compruebo unas cosas primeras 
    if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){ 
       if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) { 
          //miro si tiene caracter . 
          if (substr_count($email,".")>= 1){ 
             //obtengo la terminacion del dominio 
             $term_dom = substr(strrchr ($email, '.'),1); 
             //compruebo que la terminación del dominio sea correcta 
             if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){ 
                //compruebo que lo de antes del dominio sea correcto 
                $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1); 
                $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1); 
                if ($caracter_ult != "@" && $caracter_ult != "."){ 
                   $mail_correcto = 1; 
                } 
             } 
          } 
       } 
    } 
    if ($mail_correcto) 
       return TRUE; 
    else 
       return FALSE; 
} 


?>


<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
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
	  <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="63" colspan="2" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="10" height="16"></td>
                  <td width="1078"></td>
                  <td width="92"></td>
                </tr>
            <tr>
              <td height="19"></td>
              <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td>&nbsp;</td>
              </tr>
            

            <tr>
              <td height="28" colspan="2" valign="top" class="textoerror"><div align="right">
  <?php
if (isset($_POST['enviar']))
{//if 1


	$continua = TRUE;
	  
	//Extensiones que permitimos
	$extensiones = array(".xls");

   $datosarch = $_FILES["file"];
   $file_type = $_FILES['file']['type'];
   $file_name = $_FILES['file']['name'];
   $file_size = $_FILES['file']['size'];
   $file_tmp = $_FILES['file']['tmp_name'];
	if ($file_name == "")
	{//if 2
	echo "¡Debe seleccionar un documento¡";
	  $continua = FALSE;
	}//fin 2
	
	if($continua)
	{//if 3
	   //Chequeamos la extension
	   $ext = strrchr($file_name,'.');
	   $ext = strtolower($ext);
	   if (!in_array($ext,$extensiones))
	   {//if 4
		"¡El tipo de archivo no es permitido! solo documentos xls (excel)";
		  $continua = FALSE;
	   }//fin 4

		if($continua){//if 5
		// validamos tamaño de archivo
		if  ($file_size > 1700000)
			/*Copia el archivo en una directorio específico del servidor*/
			{//if 6
				echo "¡El archivo debe ser inferior a 4MB!";
				 $continua = FALSE;
			}//fin 6
			
			if($continua){//if 7
			//Tomamos la extension
		   $getExt = explode ('.', $file_name);
		   $file_ext = $getExt[count($getExt)-1];


		   //Como en el nombre de la imagen no puede llevar espacios, le colocamos el numero del automunerico al nombre de la imagen
			$ruta = "cuentascorreo";
			$nombre_nuevoarc = "cuentas.".$file_ext;
			 
			//Subimos la imagen original
		
		  // copy($file_tmp,$ruta,$nombre_nuevoarc);
		   move_uploaded_file($file_tmp,"$ruta/$nombre_nuevoarc");
		   
		   echo "¡El archivo se cargo con exito!";
		   
		   flush();
		  ob_flush();
		   }//fin 7
		}//fin 5
	}//fin 3
}

/////////////////////////////////////////////////////


		  
?>
              </div></td>
                  <td>&nbsp;</td>
                </tr>
          </table></td>
        </tr>
        <tr>
          <td width="4" height="25">&nbsp;</td>
          <td width="1095">&nbsp;</td>
          </tr>
        
        
        <tr>
          <td height="61">&nbsp;</td>
          <td valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1177" height="36" valign="top" class="titulos"><img src="../images/importacorreo.png" width="48" height="48" align="absmiddle" />Importaci&oacute;n cuentas de correo  <strong>
                <script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
              </strong></td>
                </tr>
            <tr>
              <td height="9"></td>
            </tr>
            
              </table></td>
          </tr>
        <tr>
          <td height="195">&nbsp;</td>
          <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla">
            <!--DWLayoutTable-->
            <tr>
              <td width="12" height="19">&nbsp;</td>
              <td width="591">&nbsp;</td>
              <td width="191">&nbsp;</td>
              <td width="316">&nbsp;</td>
              <td width="11">&nbsp;</td>
            </tr>
            <tr>
              <td height="19">&nbsp;</td>
              <td valign="top">Paso 1: Cargar el archivo de cuentas al servidor por medio del bot&oacute;n enviar </td>
              <td rowspan="3" align="center" valign="top" >Ver errores de ultima importacion <br>
                  <a href="spamimperror.php"><img src="../images/generic.png" width="48" height="48" border="0"></a></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="19">&nbsp;</td>
              <td valign="top">El formato del archivo a cargar debe ser xls (Excel) 2003 o inferiror </td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="40">&nbsp;</td>
              <td valign="top"><input type="file" name="file">
                <input name="enviar" type="submit" id="enviar" value="Cargar Archivo"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="20">&nbsp;</td>
              <td colspan="3" valign="top" >Nota: El formato del archivo debe ser para las columnas:A = Nombre,B = e-mail, C=c&oacute;digo perfil </td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="35">&nbsp;</td>
              <td colspan="3" valign="top">Paso 2: Una vez cargado el archivo especifique el n&uacute;mero de registros que este tiene y de clic sobre el bot&oacute;n iniciar  para crear las cuentas en el Sitio Web</td>
              <td>&nbsp;</td>
            </tr>
            
            <tr>
              <td height="29">&nbsp;</td>
              <td colspan="3" valign="top"><span class="Estilo6">N&uacute;mero de registros en documento
                  <input name="txtfilas" type="text" id="txtfilas" onKeyPress="onlyDigits(event,'noDec')" size="5" maxlength="5">
                  <input name="iniciar" type="submit" id="iniciar" value="Iniciar Creaci&oacute;n">
              </span></td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="29">&nbsp;</td>
              <td colspan="3" valign="top" class="textoerror"><?php
			  if (isset($_POST['iniciar']))
{
set_time_limit (900);

$continua = TRUE;

$filas = $_POST["txtfilas"];
if ($filas == "")
{
echo"¡Debe especificar el número de registros";
$continua = FALSE;
}
if($continua){
		
	//elimino errores de importaciones anteriores
	$qryeli = "DELETE FROM errorimpema";
	$reseli = mysql_query($qryeli);
		
	if ($filas < 65000)
	{
		require_once 'general/leer_excel/oleread.php';
		require_once 'general/leer_excel/reader.php';
		
		$feccre = date("Y-n-j H:i:s ");
		
		//Crear una instancia de la clase
		$data = new Spreadsheet_Excel_Reader();
		//definir codificacion
		$data->setOutputEncoding('CP1251');
		//leer archivo
		$data->read('cuentascorreo/cuentas.xls');
		//La siguiente línea debería ser opcional porque inician el reporte de errores con el valor por defecto del php.ini. Si no entienden, no importa.
		error_reporting(E_ALL ^ E_NOTICE);
		//Leer la primera celda de la primera pestaña, o sea, la celda A1, esa que dice Java Excel API Modify Test
		
		echo "<table border = 1 cellspacing=0 cellpadding=2 align = center>\n";
		
		//ORDEN CAMPOS ARCHIVO EXCEL nitter, nomter, emater, telter, movter, dirter, conter, codtipter, codtipusuter, envpro, ci, coddep, codciu, codidi, feccre, ultmod
		$procesados = 0;
		$creados = 0;
		$fallidos = 0;
		
		$cumple = TRUE;
		
		for ( $i = 1 ; $i <= $filas ; $i ++) 
		{
			$procesados = $procesados +1;
			$nom = $data->sheets[0]['cells'][$i][1];
			$ema = $data->sheets[0]['cells'][$i][2];
			$tipter = $data->sheets[0]['cells'][$i][3];
			
			$feccre = date("Y-n-j H:i:s");
			
			$val1="";
			$val2="";
			$val3="";
			
			//valido formato e-mail valido
			if(!comprobar_email($ema))
			{
				$cumple = FALSE;
				$val1 = $ema;
			}
			
			//valido  que e-mail no exista
			$qryema1 = "SELECT codspam FROM spam WHERE emaspam = '$ema'";
			$resema1 = mysql_query($qryema1, $enlace);
			$numema1 = mysql_num_rows($resema1);
			
			if ($numema1 > 0)
			{
				$cumple = FALSE;
				$val2 = $ema;
			}
			
			//valido que exite codigo de tipo de cliente
			$qrytip = "SELECT codtipter FROM tipter WHERE codtipter = '$tipter'";
			$restip = mysql_query($qrytip, $enlace);
			$numtip = mysql_num_rows($restip);
			
			if ($numtip < 1)
			{
				$cumple = FALSE;
				$val3 = $tipter;
			}
			
			//valido si pasa todas las validaciones
			if($cumple){
				
				$creados = $creados + 1;
				
				//inserto cliente
				$qrytipcliins="INSERT INTO spam VALUES ('0', '$nom', '$ema', '$tipter') ";
				$restipcliins=mysql_query($qrytipcliins,$enlace);
	
			}
			else
			{//INSERTO ERROR
			$fallidos = $fallidos + 1;
			
			$qrynocumple = "INSERT INTO errorimpema VALUES ('$nom', '$val1', '$val2', '$val3','$procesados', '$feccre' )";
			$resnocumple = mysql_query($qrynocumple, $enlace);
					
			}	
					
		$cumple = TRUE;
		
		flush();
		ob_flush();
		
		}
		echo "</table>\n";
		echo "<br>";
		echo "Se crearon: ".$creados." cuentas de manera exitosa"."<br>";
		echo "No se crearon: ".$fallidos." cuentas <br>";
		echo "Registro Procesados: ".$procesados ;
	}
		
	else
	{
	  echo "¡El numero de registros debe ser inferior a 65000!";	
	}
}
//Recorrer todas las pestañas
//foreach($data->sheets as $x => $y){   echo "$x = $y<br>";}
//Esto hará que la variable $y se muestre como Array, así que viene lo sgte.
//Leer el nombre de cada pestaña
//foreach($data->sheets as $x => $y){   echo "$x = {$data->boundsheets[$x]['name']}<br>";}
}
			  ?></td>
              <td>&nbsp;</td>
            </tr>
            
            
            
          </table>
          </td>
          </tr>
        <tr>
          <td height="27">&nbsp;</td>
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
