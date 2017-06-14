<?php 

function buildNavigation($pageNum_Recordset1,$totalPages_Recordset1,$prev_Recordset1,$next_Recordset1,$separator=" | ",$max_links=10, $show_page=true){
	GLOBAL $maxRows_registros,$totalRows_registros;
	$pagesArray = ""; $firstArray = ""; $lastArray = "";
	if($max_links<2)$max_links=2;
		if($pageNum_Recordset1<=$totalPages_Recordset1 && $pageNum_Recordset1>=0){
			if ($pageNum_Recordset1 > ceil($max_links/2)){
				$fgp = $pageNum_Recordset1 - ceil($max_links/2) > 0 ? $pageNum_Recordset1 - ceil($max_links/2) : 1;
				$egp = $pageNum_Recordset1 + ceil($max_links/2);
			if ($egp >= $totalPages_Recordset1){
				$egp = $totalPages_Recordset1+1;
				$fgp = $totalPages_Recordset1 - ($max_links-1) > 0 ? $totalPages_Recordset1  - ($max_links-1) : 1;
			}
		}else {
			$fgp = 0;
			$egp = $totalPages_Recordset1 >= $max_links ? $max_links : $totalPages_Recordset1+1;
		}
		if($totalPages_Recordset1 >= 1) {
			#	------------------------
			#	Searching for $_GET vars
			#	------------------------
			$_get_vars = '';			
			if(!empty($_GET) || !empty($_GET)){
				$_GET = empty($_GET) ? $_GET : $_GET;
				foreach ($_GET as $_get_name => $_get_value) {
					if ($_get_name != "pageNum_registros") {
						$_get_vars .= "&$_get_name=$_get_value";
					}
				}
			}
			$successivo = $pageNum_Recordset1+1;
			$precedente = $pageNum_Recordset1-1;
			$firstArray = ($pageNum_Recordset1 > 0) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_registros=$precedente$_get_vars\">$prev_Recordset1</a>" :  "$prev_Recordset1";
			# ----------------------
			# page numbers
			# ----------------------
			for($a = $fgp+1; $a <= $egp; $a++){
				$theNext = $a-1;
				if($show_page){
					$textLink = $a;
				}else{
					$min_l = (($a-1)*$maxRows_registros) + 1;
					$max_l = ($a*$maxRows_registros >= $totalRows_registros) ? $totalRows_registros : ($a*$maxRows_registros);
					$textLink = "$min_l - $max_l";
				}
				$_ss_k = floor($theNext/26);
				if ($theNext != $pageNum_Recordset1){
					$pagesArray .= "<a href=\"$_SERVER[PHP_SELF]?pageNum_registros=$theNext$_get_vars\">";
					$pagesArray .= "$textLink</a>" . ($theNext < $egp-1 ? $separator : "");
				}else{
					$pagesArray .= "$textLink"  . ($theNext < $egp-1 ? $separator : "");
				}
			}
			$theNext = $pageNum_Recordset1+1;
			$offset_end = $totalPages_Recordset1;
			$lastArray = ($pageNum_Recordset1 < $totalPages_Recordset1) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_registros=$successivo$_get_vars\">$next_Recordset1</a>" : "$next_Recordset1";
		}
	}
	return array($firstArray,$pagesArray,$lastArray);
}
session_start();
include("general/conexion.php") ;
include("general/sesion.php");
include("general/operaciones.php") ;
sesion(1);

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'usuariosauditoria.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();


///consulta de productos
if (session_is_registered("numreg")){
$maxRows_registros = $_SESSION["numreg"];
}
else{
$maxRows_registros = 10;
}


$pageNum_registros = 0;
if (isset($_GET['pageNum_registros'])) {
  $pageNum_registros = $_GET['pageNum_registros'];
}
$startRow_registros = $pageNum_registros * $maxRows_registros;

		
if (isset($_POST['filtrar']))
{
	$codemp = $_POST["cbo1codempno"];
$tab = $_POST["cbotab"];
$acc = $_POST["cboacc"];
$fecini = $_POST["txtfecini"];
$fecfin = $_POST["txtfecfin"];


	
$query_registros = "SELECT e.nomusu AS empleado, a.fecacc, a.tabafe, a.regafe, c.nomacc 
			FROM tblusuariosauditoria AS a 
			INNER JOIN tblusuariosacc AS c 
			ON c.codacc = a.codacc 
			INNER JOIN usuadm AS e 
			ON e.codusuadm = a.codusuadm
			WHERE a.codaud > 0 ";

	if ($codemp <> 0){
		$query_registros .= "AND e.codusuadm = $codemp ";
	}	
	if ($tab <> "0"){
		$query_registros .= "AND a.tabafe LIKE '".$tab."' ";
	}	
	if ($acc <> 0){
		$query_registros .= "AND a.codacc = $acc ";
	}	
	if ($fecini <> ""){
		$query_registros .= "AND date(a.fecacc) >= '$fecini' ";
	}	
	if ($fecfin <> ""){
		$query_registros .= "AND date(a.fecacc) <= '$fecfin' ";
	}
	$query_registros .= "ORDER BY a.fecacc DESC";
	
	$_SESSION['qryfilaud']=$query_registros;
	
}

if (isset($_SESSION['qryfilaud'])){ 
$query_registros= $_SESSION['qryfilaud'];
}
else{
$query_registros= "SELECT tiempo FROM sesionest WHERE tiempo = -1";
}

destruyesesiones("qryfilaud");
$_SESSION["consulta"] = $query_registros;

$query_limit_registros = sprintf("%s LIMIT %d, %d", $query_registros, $startRow_registros, $maxRows_registros);
$registros = mysql_query($query_limit_registros, $enlace) or die(mysql_error());
$row_registros = mysql_fetch_assoc($registros);

if (isset($_GET['totalRows_registros'])) {
  $totalRows_registros = $_GET['totalRows_registros'];
} else {
  $all_registros = mysql_query($query_registros);
  $totalRows_registros = mysql_num_rows($all_registros);
}
$totalPages_registros = ceil($totalRows_registros/$maxRows_registros)-1;


?>

<html><!-- InstanceBegin template="/Templates/admcon.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
<script type="text/javascript" language="JavaScript1.2" src="../js/stm31.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>administractor de contenido</title>

<script type="text/javascript" src="general/selectproyectos.js"></script>

<link rel="stylesheet" type="text/css" media="all" href="calendario_skin/aqua/theme.css" title="Aqua" />
<!-- import the calendar script -->
<script type="text/javascript" src="calendario/calendar.js"></script>
<!-- import the language module -->
<script type="text/javascript" src="calendario/calendar-sp.js"></script>
<!-- other languages might be available in the lang directory; please check
your distribution archive. -->
<script type="text/javascript">
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

// If this handler returns true then the "date" given as
// parameter will be disabled.  In this example we enable
// only days within a range of 10 days from the current
// date.
// You can use the functions date.getFullYear() -- returns the year
// as 4 digit number, date.getMonth() -- returns the month as 0..11,
// and date.getDate() -- returns the date of the month as 1..31, to
// make heavy calculations here.  However, beware that this function
// should be very fast, as it is called for each day in a month when
// the calendar is (re)constructed.
//-->
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
	 <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
	 
        <!--DWLayoutTable-->
        <tr>
          <td height="63" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->
            <tr>
              <td width="9" height="16"></td>
                <td width="1249"></td>
                <td width="12"></td>
                <td width="75"></td>
                <td width="13"></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td valign="top"><span >Usuario: <?php echo $_SESSION["logueado"]?></span></td>
                <td>&nbsp;</td>
                <td width="62" rowspan="3" align="center" valign="top"><a href="general/exportar.php"><img src="../images/exportexcel.png" alt="Exportar Resultados a Excel" width="32" height="32" border="0" /></a><br/>
Exportar</td>
                <td>&nbsp;</td>
            </tr>
            
            
            

            <tr>
              <td height="28" colspan="2" valign="top" class="textoerror"><div align="right">
  <?php			  
	if (isset($_POST['ver']))		
	{
	$_SESSION["numreg"]=$_POST["selnumreg"];	
	echo "<META HTTP-EQUIV='refresh' CONTENT='0'>";		
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
          <td width="971">&nbsp;</td>
          <td width="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="72">&nbsp;</td>
          <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="135" rowspan="4" valign="top" class="titulos"><img src="../images/auditoria.png" width="48" height="48" align="absmiddle" />Auditoria </td>
                  <td height="14" colspan="2" valign="top">
                  Usuario</td>
                  <td width="375" valign="top">Acci&oacute;n realizada </td>
                  <td width="72" rowspan="4" align="right" valign="bottom">                    <input name="filtrar" type="submit" id="filtrar" value="filtrar"/>                    </td>
                  <td width="12"></td>
              </tr>
            <tr>
              <td height="22" colspan="2" valign="top">                  <select name="cbo1codempno" class="textonegro" id="cbo1codempno">
                <option value="0">Elige</option>
                <?
	
	

	$qryemp= "SELECT u.codusuadm, u.nomusu FROM usuadm AS u ORDER BY u.nomusu ";
	$resemp = mysql_query($qryemp, $enlace);
	while ($filemp = mysql_fetch_array($resemp))
	echo "<option value=\"".$filemp["codusuadm"]."\">".$filemp["nomusu"]."</option>\n";
	mysql_free_result($resemp);
?>
                </select>                </td>
                  <td valign="top">                  <select name="cboacc" class="textonegro" id="cboacc">
                    <option value="0">Elige</option>
                    <?
	
	

	$qryacc= "SELECT DISTINCT(ac.codacc), ac.nomacc FROM tblusuariosauditoria AS a JOIN tblusuariosacc AS ac ON ac.codacc = a.codacc ORDER BY ac.nomacc ";
	$resacc = mysql_query($qryacc, $enlace);
	while ($filacc = mysql_fetch_array($resacc))
	echo "<option value=\"".$filacc["codacc"]."\">".$filacc["nomacc"]."</option>\n";
	mysql_free_result($resacc);
?>
                    </select>                </td>
                  <td>&nbsp;</td>
              </tr>
            
              <tr>
                <td width="286" height="14" valign="top">Proceso</td>
                  <td colspan="2" rowspan="2" valign="bottom">Fecha de Incio<span > <strong>
                    <input name="txtfecini" type="text" class="textonegro"  id="txtfecini"  value ="<?php if (isset($_POST['txtfecini'])) echo $_POST['txtfecini']; ?>" size="20"readonly=""
			  ><input name="reset"  type="reset" class="textonegro"
			  onclick="return showCalendar('txtfecini', '%Y-%m-%d');" value=" ... " >
                    </strong></span>fecha fin <span ><strong>
                    <input name="txtfecfin" type="text" class="textonegro"  id="txtfecfin"  value ="<?php if (isset($_POST['txtfecfin'])) echo $_POST['txtfecfin']; ?>" size="20"readonly=""
			  ><input name="reset"  type="reset" class="textonegro"
			  onclick="return showCalendar('txtfecfin', '%Y-%m-%d');" value=" ... "  >
                  </strong></span></td>
                  <td></td>
              </tr>
            <tr>
              <td height="22" valign="top">                  <select name="cbotab" class="textonegro" id="cbotab">
                <option value="0">Elige</option>
                <?
	
	

	$qrytab= "SELECT DISTINCT(tabafe) FROM tblusuariosauditoria ORDER BY tabafe ";
	$restab = mysql_query($qrytab, $enlace);
	while ($filtab = mysql_fetch_array($restab))
	echo "<option value='".$filtab["tabafe"]."'>".$filtab["tabafe"]."</option>\n";
	mysql_free_result($restab);
?>
                </select>                </td>
                  <td>&nbsp;</td>
              </tr>
            <tr>
              <td height="1"></td>
                <td></td>
                <td width="96"></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
            
            
            
            
            
            
            
            
            
            
            
            
            
            </table></td>
          </tr>
        <tr>
          <td height="19">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        
        <tr>
          <td height="122"></td>
          <td valign="top"><table width="93%" border="0" cellpadding="0" cellspacing="0" bgcolor="#DEEFDC" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td height="8" colspan="2" valign="top" bgcolor="#000000"></td>
                  <td width="133" valign="top" bgcolor="#000000"></td>
                  <td width="194" valign="top" bgcolor="#000000"></td>
                  <td width="86" valign="top" bgcolor="#000000"></td>
                  <td width="334" valign="top" bgcolor="#000000"></td>
                </tr>
            <tr>
              <td width="11" height="30" valign="middle" bgcolor="#FFFFFF" ><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td width="211" valign="middle" bgcolor="#FFFFFF" >Empleado</td>
                  <td valign="middle" bgcolor="#FFFFFF"><span >Fecha</span></td>
                  <td valign="middle" bgcolor="#FFFFFF" >Proceso Involucrado </td>
                  <td valign="middle" bgcolor="#FFFFFF"><span >Consecutivo</span></td>
                  <td valign="middle" bgcolor="#FFFFFF"><span >Acci&oacute;n</span></td>
                </tr>
            
           
              <?php if($totalRows_registros > 0){
			   $num= $startRow_registros ;
						$numero = 0 ;
				do { 
					if($numero == 1){
					$numero = 0;
					echo"<tr>" ;
					echo"<td ></td>";
					echo"</tr>" ;
					}
		   ?> <tr onMouseOver="this.style.backgroundColor='#BAD0FC';" class="pointer" onMouseOut="this.style.backgroundColor='#F3F3F3'">
              <td height="24">&nbsp;</td>
                    <td valign="top" class="textonegro"><?php echo $row_registros['empleado']; ?></td>
                    <td valign="top" class="textonegro"><?php echo $row_registros['fecacc']; ?></td>
				    <td valign="top" class="textonegro"><?php echo $row_registros['tabafe']; ?></td>
                    <td valign="top"><span class="textonegro"><?php echo $row_registros['regafe']; ?></span></td>
                    <td valign="top"><span class="textonegro"><?php echo $row_registros['nomacc']; ?></span></td>
					</tr>
                    <?php $numero++; } while ($row_registros = mysql_fetch_assoc($registros)); }?>
              
            <tr>
              <td height="30"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                </tr>
            
            <tr>
              <td height="28" colspan="6" align="center" valign="top" bgcolor="#FFFFFF" class="textonegro"><?php 
						# variable declaration
						$prev_registros = "&laquo; Anterior";
						$next_registros = "Siguiente &raquo;";
						$separator = " | ";
						$max_links = 10;
						$pages_navigation_registros = buildNavigation($pageNum_registros,$totalPages_registros,$prev_registros,$next_registros,$separator,$max_links,true); 
						print $pages_navigation_registros[0]; 
						?>
                <?php print $pages_navigation_registros[1]; ?> <?php print $pages_navigation_registros[2]; ?></td>
            </tr>
            
            
            
            
          </table></td>
          <td></td>
        </tr>
        <tr>
          <td height="13"></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td height="24"></td>
          <td valign="top" class="textonegro"><div align="center">Ver #
              <select name="selnumreg" id="selnumreg" >
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="25">25</option>
                <option value="30">30</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
              <input name="ver" type="submit" id="ver" value="ver" />
Resultados <span ><?php echo $totalRows_registros?></span></div></td>
          <td></td>
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
