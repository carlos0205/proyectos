<?php
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php");
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'proofeedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();

$cod = $_GET["cod"];

if($_GET["acc"]==0){
$qryact = "UPDATE proofe SET pub = '".$_GET["pub"]."' WHERE codproofe = $cod";
$resact = mysql_query($qryact, $enlace);

?>
<script  language="javascript" type="text/javascript">
window.location="proofe.php";
</script>
<?php

}

$qryreg = "SELECT * FROM proofe WHERE codpro = '$cod'";
$resreg = mysql_query($qryreg, $enlace);
$filreg = mysql_fetch_assoc($resreg);

?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>Admin-Web</title>
<script type="text/javascript" language="JavaScript" src="general/validaform.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="calendario_skin/aqua/theme.css" title="Aqua" />
<!-- import the calendar script -->
<script type="text/javascript" src="calendario/calendar.js"></script>

<!-- import the language module -->
<script type="text/javascript" src="calendario/calendar-sp.js"></script>
<script language="JavaScript">
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
<!--

var oldLink = null;
// code to change the active stylesheet


// This function gets called when the end-user clicks on some date.
function selected(cal, date) {
  cal.sel.value = date; // just update the date in the input field.
  if (cal.dateClicked && (cal.sel.id == "sel1" || cal.sel.id == "sel3"))
    // if we add this call we close the calendar on single-click.
    // just to exemplify both cases, we are using this only for the 1st
    // and the 3rd field, while 2nd and 4th will still require double-click.
    cal.callCloseHandler();
}

// And this gets called when the end-user clicks on the _selected_ date,
// or clicks on the "Close" button.  It just hides the calendar without
// destroying it.
function closeHandler(cal) {
  cal.hide();                        // hide the calendar
//  cal.destroy();
  _dynarch_popupCalendar = null;
}

// This function shows the calendar under the element having the given id.
// It takes care of catching "mousedown" signals on document and hiding the
// calendar if the click was outside.
function showCalendar(id, format, showsTime, showsOtherMonths) {
  var el = document.getElementById(id);
  if (_dynarch_popupCalendar != null) {
    // we already have some calendar created
    _dynarch_popupCalendar.hide();                 // so we hide it first.
  } else {
    // first-time call, create the calendar.
    var cal = new Calendar(1, null, selected, closeHandler);
    // uncomment the following line to hide the week numbers
    // cal.weekNumbers = false;
    if (typeof showsTime == "string") {
      cal.showsTime = true;
      cal.time24 = (showsTime == "24");
    }
    if (showsOtherMonths) {
      cal.showsOtherMonths = true;
    }
    _dynarch_popupCalendar = cal;                  // remember it in the global var
    cal.setRange(1900, 2070);        // min/max year allowed.
    cal.create();
  }
  _dynarch_popupCalendar.setDateFormat(format);    // set the specified date format
  _dynarch_popupCalendar.parseDate(el.value);      // try to parse the text in field
  _dynarch_popupCalendar.sel = el;                 // inform it what input field we use

  // the reference element that we pass to showAtElement is the button that
  // triggers the calendar.  In this example we align the calendar bottom-right
  // to the button.
  _dynarch_popupCalendar.showAtElement(el.nextSibling, "Br");        // show the calendar

  return false;
}

var MINUTE = 60 * 1000;
var HOUR = 60 * MINUTE;
var DAY = 24 * HOUR;
var WEEK = 7 * DAY;

//or visit mt web site at http://home.rmci.net/gooftroop
function enviaproducto(producto){
location = "proofecre.php?pro="+producto;
}

function descuento(caja, dcto, decimales){
var decimales = parseFloat(decimales);
var decimales1 = parseFloat(decimales);
cajadcto = caja+"desno";

cajavalofe = caja+"ofeno";
a = eval("document.form1."+caja+ ".value" ) ;
b = a/100 ;
c = b*eval("document.form1."+dcto+ ".value" );

//inserto valor descontado
var cantidad = parseFloat(c);
decimales = (!decimales ? 2 : decimales);
c = Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
eval("document.form1." + cajadcto +".value="+c);

//inserto nuevo precio de oferta
d = a - c;
var cantidad1 = parseFloat(d);
decimales1 = (!decimales1 ? 2 : decimales1);
d = Math.round(cantidad1 * Math.pow(10, decimales1)) / Math.pow(10, decimales1);

eval("document.form1." + cajavalofe +".value="+d);
}

function perc1() {
 a = document.form1.a.value/100;
 b = a*document.form1.b.value;
 document.form1.total1.value = b
 }
function perc2() {
 a = document.form1.c.value;
 b = document.form1.d.value;
 c = a/b;
 d = c*100;
 document.form1.total2.value = d
 }
//-->
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
                  <td width="945">&nbsp;</td>
                  <td width="33">&nbsp;</td>
                  <td width="62" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro(); "><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
                  <td width="62" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro(); "><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
                  <td width="62" rowspan="3" align="center" valign="middle"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="16"></td>
            </tr>
            <tr>
              <td height="15"></td>
              <td valign="top">Usuario: <?php echo $_SESSION["logueado"]?></td>
                  <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="26" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php

					
					if (isset($_POST['guardarno'])){
						
						$qrylis = "SELECT COUNT(codpro) AS total FROM tblproductosprecios WHERE codpro = '$cod' ";
						$reslis = mysql_query($qrylis,$enlace);
						$fillis = mysql_fetch_assoc($reslis);
					
						if($fillis["total"] > 0){
							//consulto listas de precio
							$qrylis = "SELECT l.codlispre, l.nomlispre FROM tbllistasdeprecio l WHERE estlispre = 'Si'";
							$reslis = mysql_query($qrylis, $enlace);
							
							while($fillis=mysql_fetch_assoc($reslis)){//while 1
								$codlis = $fillis["codlispre"];
							
								//consulto precio de venta del producto para la lista de precio y la moneda
								$qryprecio = "SELECT prepro FROM tblproductosprecios WHERE codpro = '$cod' AND codlispre = '$codlis' ";
								$resprecio = mysql_query($qryprecio);
								$numpro = mysql_num_rows($resprecio);
										
								while($filprecio = mysql_fetch_assoc($resprecio)){ //while2	

									//averiguo si producto esta con la lista de precio si esta actualizo si no inserto
									$qryexi = "SELECT codproofedet FROM proofedet WHERE codproofe = (SELECT codproofe FROM proofe WHERE codpro =$cod ) AND codlispre = $codlis";
									$resexi = mysql_query($qryexi, $enlace);

									$precio = $_POST["txt1lp".$codlis."noofeno"];
									$dcto = $_POST["txt2des".$codlis."no"];
										
									if(mysql_num_rows($resexi)> 0){

										//actualizo precio de oferta de producto para lista
										$qryofedet = "UPDATE  proofedet od, proofe o SET od.dcto = '$dcto', od.prepro='$precio' WHERE o.codpro = '$cod' AND o.codproofe=od.codproofe AND od.codlispre = '$codlis'";
										$resofedet = mysql_query($qryofedet, $enlace);
									
									}else{
										//inserto precio de oferta de producto para lista
										$qryofedet = "INSERT INTO  proofedet VALUES('0','".$filoferta["codproofe"]."','$codlis','$dcto','$precio')";
										$resofedet = mysql_query($qryofedet, $enlace);
									}
								
								}//fin while 2
							}//fin while 1
							
							auditoria($_SESSION["enlineaadm"],'Productos Oferta',$cod,'4');
						
						actualizar("proofe",2,$cod,"codproofe","proofe.php");
						
						}else{
						echo "!No es posible crear la oferta¡ No existe lista de precio asociada al producto";	
						}
					}
					
					//boton aplicar cambios
					if (isset($_POST['aplicar']))
					{
						$qrylis = "SELECT COUNT(codpro) AS total FROM tblproductosprecios WHERE codpro = '$cod' ";
						$reslis = mysql_query($qrylis,$enlace);
						$fillis = mysql_fetch_assoc($reslis);
					
						if($fillis["total"] > 0){
							//consulto listas de precio
							$qrylis = "SELECT l.codlispre, l.nomlispre FROM tbllistasdeprecio l WHERE estlispre = 'Si'";
							$reslis = mysql_query($qrylis, $enlace);
							
							while($fillis=mysql_fetch_assoc($reslis)){//while 1
								$codlis = $fillis["codlispre"];
							
								//consulto precio de venta del producto para la lista de precio y la moneda
								$qryprecio = "SELECT prepro FROM tblproductosprecios WHERE codpro = '$cod' AND codlispre = '$codlis' ";
								$resprecio = mysql_query($qryprecio);
								$numpro = mysql_num_rows($resprecio);
										
								while($filprecio = mysql_fetch_assoc($resprecio)){ //while2	

									//averiguo si producto esta con la lista de precio si esta actualizo si no inserto
									$qryexi = "SELECT codproofedet FROM proofedet WHERE codproofe = (SELECT codproofe FROM proofe WHERE codpro =$cod ) AND codlispre = $codlis";
									$resexi = mysql_query($qryexi, $enlace);

									$precio = $_POST["txt1lp".$codlis."noofeno"];
									$dcto = $_POST["txt2des".$codlis."no"];
										
									if(mysql_num_rows($resexi)> 0){

										//actualizo precio de oferta de producto para lista
										$qryofedet = "UPDATE  proofedet od, proofe o SET od.dcto = '$dcto', od.prepro='$precio' WHERE o.codpro = '$cod' AND o.codproofe=od.codproofe AND od.codlispre = '$codlis'";
										$resofedet = mysql_query($qryofedet, $enlace);
									
									}else{
										//inserto precio de oferta de producto para lista
										$qryofedet = "INSERT INTO  proofedet VALUES('0','".$filoferta["codproofe"]."','$codlis','$dcto','$precio')";
										$resofedet = mysql_query($qryofedet, $enlace);
									}
								
								}//fin while 2
							}//fin while 1
						auditoria($_SESSION["enlineaadm"],'Productos Oferta',$cod,'4');
						actualizar("proofe",2,$cod,"codproofe","proofeedi.php?cod=$cod&acc=1");
						
						}else{
						echo "!No es posible crear la oferta¡ No existe lista de precio asociada al producto";	
						}
					}
					
					
					//boton cancelar cambios
					if (isset($_POST['cancelarno']))
					{
					?>
					<script language="javascript1.2" type="text/javascript">
								location = "proofe.php";
								</script>';
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
          <td width="11" height="25">&nbsp;</td>
          <td width="1089">&nbsp;</td>
          <td width="13">&nbsp;</td>
        </tr>
        <tr>
          <td height="52">&nbsp;</td>
          <td colspan="2" valign="top"><table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <!--DWLayoutTable-->
            <tr>
              <td width="1380" height="52" valign="top" class="titulos"><img src="../images/oferta.png" width="48" height="48" align="absmiddle" /> Producto en oferta [ Edita ]  <strong>
                  
                  </strong></td>
                </tr>
          </table></td>
          </tr>
        
        
        <tr>
          <td height="145">&nbsp;</td>
          <td valign="top"><table width="58%" height="204" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="6" height="14"></td>
                  <td width="1066"></td>
                  <td width="15"></td>
            </tr>
            <tr>
              <td height="23"></td>
              <td valign="top">Producto
                  
                  Incia Oferta
                 
                  <input name="txt2fecinisi" type="text"  id="txt2fecinisi" size="20" value ="<?php echo $filreg["fecini"] ?>" readonly=""
			  ><input name="reset" type="reset" 
			  onclick="return showCalendar('txt2fecinisi', '%Y-%m-%d');" value=" ... " title="Fecha inicio" >
                  Finaliza Oferta 
                  <input name="txt2fecfinsi" type="text"  id="txt2fecfinsi" size="20" value ="<?php  echo $filreg["fecfin"]  ?>" readonly=""
			  ><input name="reset2" type="reset" 
			  onclick="return showCalendar('txt2fecfinsi', '%Y-%m-%d');" value=" ... "  title="Fecha fin"> 
                  Publicar oferta
                  <label>
                  <select name="cbo2pubsi" id="cbo2pubsi" title="Publicar">
                    <?php
					  $qrypub="SELECT 'Si' AS publica
						UNION
						SELECT 'No' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['pub']."\">".$filreg['pub']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["pub"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
                  </select>
                  </label></td>
                  <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="22"></td>
              <td valign="top"><br>
                Porfavor ingrese el precio de  oferta del producto para las listas de Precio </td>
                  <td></td>
            </tr>
            <tr>
              <td height="13"></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="101"></td>
              <td valign="top"><?php 

				$qrylis = "SELECT l.codlispre, l.nomlispre FROM tbllistasdeprecio l WHERE estlispre = 'Si'  ORDER BY codlispre";
				$reslis = mysql_query($qrylis, $enlace);
				$numlis = mysql_num_rows($reslis);
				if($numlis > 0){ ?>
					<br>
					<span class="texnegronegrita">PRODUCTO					</span> <span class="textoerror">
					<?php 
					//consulto el nombre del producto seleccionado para mostrarlo
					$qrynom = "SELECT pd.nompro FROM prodet pd WHERE pd.codpro = '$cod' AND pd.codidi = '1'";
					$resnom = mysql_query($qrynom, $enlace);
					$filnom = mysql_fetch_assoc($resnom);
					echo $filnom["nompro"];
					?></span>
					<table width = 700 bgcolor="#FFFFFF" class=textonegro>
					  <tr bgcolor=\"#FFCC00\" >
					    <th bgcolor="#FFCC00" >Lista de Precios</th>
					  <th bgcolor="#FFCC00" >Moneda</th>
					  <th bgcolor="#FFCC00" >Precio de Venta </th>
					  <th bgcolor="#FFCC00" >% Descuento</th>
					  <th bgcolor="#FFCC00" >Total Descuento </th>
					  <th bgcolor="#FFCC00" >Precio de Oferta </th>
					  </tr>
					  <?php
					  //array para control de cajas de texto en javascript
					$listas = "";
					$contador = 1;
					while($fillis=mysql_fetch_assoc($reslis)){//while 1
					
					$codlis = $fillis["codlispre"];
					
					//consulto precio de venta del producto para la lista de precio y la moneda
					$qryprecio = "SELECT codlispre, prepro FROM tblproductosprecios WHERE codpro = '$cod' AND codlispre = '$codlis' ";
					
					$resprecio = mysql_query($qryprecio);
					$numpro = mysql_num_rows($resprecio);
					
					$numlis1 = ($numlis * $numpro);
					
					$qrymon = "SELECT m.* FROM tblmonedas  m WHERE mondefecto = 2 ";
					$resmon = mysql_query($qrymon, $enlace);
					$filmon = mysql_fetch_assoc($resmon);
					$mon = $filmon["codmon"];
						
					while($filprecio = mysql_fetch_assoc($resprecio)){ //while2
					
						

						//consulto datos de producto en oferta
						$qryofe = "SELECT od.dcto, od.prepro FROM proofe o, proofedet od WHERE o.codpro = '$cod' AND o.codproofe = od.codproofe AND od.codlispre = ".$filprecio["codlispre"]." ";
						
					
						$resofe = mysql_query($qryofe, $enlace);
						$filofe = mysql_fetch_assoc($resofe);
					
					?>
					  <tr>
					    <td><?php echo $fillis["nomlispre"] ?> </td>
					    <td><?php echo $filmon["nommon"] ?> </td>
					    <td align = center> <input name="txt1lp<?php echo $codlis ?>no" type="text" id="txt1lp<?php echo $codlis ?>no" size="10" maxlength="10"  readonly="readonly" value="<?PHP  echo $filprecio["prepro"]; ?>"/></td>
							  
						<td align = center> <input name="txt2des<?php echo $codlis ?>no" type="text" id="txt2des<?php echo $codlis ?>no" size="10" maxlength="2" value="<?php echo $filofe["dcto"]?>"onChange="descuento('txt1lp<?php echo $codlis?>no','txt2des<?php echo $codlis ?>no','<?php echo $filmon["lugdec"]?>')"  onKeyPress="onlyDigits(event,'nodec')" title="Descuento lista <?php echo $fillis["nomlispre"]; ?>"/></td>
							  
						<td align = center> <input name="txt1lp<?php echo $codlis?>nodesno" type="text" id="txt1lp<?php echo $codlis?>nodesno" size="10" maxlength="10"  readonly="readonly" value="<?php echo $filprecio["prepro"]-$filofe["prepro"];?>"/></td>
							  
						<td align = center> <input name="txt1lp<?php echo $codlis ?>noofeno" type="text" id="txt1lp<?php echo $codlis ?>noofeno" size="10" maxlength="10"  readonly="readonly" value="<?php echo $filofe["prepro"]?>"/></td>
				      </tr>
					  
					  <?php 
					//lleno variable para pasar a script de validaciond e seleccion de preguntas
	
						if($contador <  $numlis1){
						$listas .= "'txt1des".$codlis."no',";
						}else{
						$listas .= "'txt1des".$codlis."no'";
						}	
					
						$contador++;
					
						}//fin while 2
					}//fin while 1
					
					?>
				    </table>
					<?php
					}else{
					echo "Primero debe crear una lista de precios";
					}
				 ?>
				   </td>
                <td></td>
            </tr>
            <tr>
              <td height="29"></td>
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