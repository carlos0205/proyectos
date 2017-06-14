<?php
header('Content-Type: text/html; charset=ISO-8859-1');

$current_site  = $_SERVER["SERVER_NAME"];
$url_link      = "www.elpais.com.co/elpais/";

$entorno_sitio = ( ( preg_match('/^www(.*)/i', $current_site) ) ? "produccion" : ( ( preg_match("/^contenidos(.*)/i", $current_site) ) ? "contenidos" : ( ( preg_match("/^staging(.*)/i", $current_site) ) ? "staging" :"desarrollo") ));

define('SERVER_ESPECIAL', $entorno_sitio); // produccion

require 'conexion.php';
include("paginador.php");

//2. Crear una consulta a la base de datos (node) que traiga la informacion basica de una nota interna (tipo nota_interna)
//si se hace por una sola nota
$idNota = 337292;


    if (isset($_POST['filtrar'])){

//echo "hola";

  $fecini = $_POST["txtfecini"];
	$fecfin = $_POST["txtfecfin"];

 $query_registros = "SELECT * forms where name_form ='educali-2017' ";

/*if ($fecini <> " " && $fecfin <> " "){
	 $query_registros .= " AND DATE(node.node_created) BETWEEN '$fecini' AND '$fecfin'";
	}*/

$query_registros .= " GROUP BY idforms ORDER BY idforms DESC";



$result = db_query($query_registros);

$_SESSION['qryfiltroclientes']=$query_registros;
}

if (isset($_SESSION['qryfiltroclientes'])){
$query_registros = $_SESSION['qryfiltroclientes'];
}
else{


$query_registros = "SELECT * forms where name_form ='educali-2017'";


}// fin de else*/

$_SESSION["consulta"] = $query_registros;


include("paginadorinferior.php");

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>XALOK XML</title>
<link rel="stylesheet" type="text/css" media="all" href="calendario_skin/aqua/theme.css" title="Aqua" />
<!-- import the calendar script -->
<script type="text/javascript" src="calendario/calendar.js"></script>
<!-- import the language module -->
<script type="text/javascript" src="calendario/calendar-sp.js"></script>
<script type="text/javascript">
<!--

    function do_this(){

        var checkboxes = document.getElementsByName('tercero[]');
        var button = document.getElementById('toggle');

        if(button.value == 'select'){
            for (var i in checkboxes){
                checkboxes[i].checked = 'FALSE';
            }
            button.value = 'deselect'
        }else{
            for (var i in checkboxes){
                checkboxes[i].checked = '';
            }
            button.value = 'select';
        }
    }

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
</head>

<body>
<header>Formulario Descarga Notas Internas</header><br>
<form name="form1" id="form1" method="POST">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" style="width: 100%; height: 400px ">
  <!--DWLayoutTable-->
  <tr>
    <td width="300" height="49" valign="middle" bgcolor="#F5F5F">XALOK EXPORTACION XML</td>
    <td width="100%" valign="bottom" bgcolor="#F5F5F5" class="textogris"><div align="right"></div></td>
  </tr>
  <tr>
    <td height="19" colspan="2" valign="top" bgcolor="#3B83BD"></td>
  </tr>
  <tr>
    <td height="313" colspan="2" valign="top"><!-- InstanceBeginEditable name="contenido" -->
	  <form id="form1" name="form1" method="post" action="" enctype="multipart/form-data">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <!--DWLayoutTable-->
        <tr>
          <td height="3" colspan="3" valign="top" bgcolor="#FFFFFF"><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" class="textonegro">
            <!--DWLayoutTable-->



            <tr>
              <td height="28" colspan="2" valign="top" class="textoerror"><div align="right">
                <?php

				if (isset($_POST['exportar'])){

						function array_envia($codreg) {
							$tmp = serialize($codreg);
							$tmp = urlencode($tmp);
							return $tmp;
						}
						

             $query_registros1 = "SELECT * forms where name_form ='educali-2017' GROUP BY idforms ORDER BY idforms DESC LIMIT 10";

$resultado1 = db_query($query_registros1);

  foreach($resultado1 as $registro){

            $codreg1[] = $registro["idforms"]; 

            //$codreg=array_values($registro["nid"]);
           /*echo "<pre>";
           print($codreg);
           echo "</pre>";*/
            $codreg=array_values($codreg1);
						$codreg=array_envia($codreg);


}

						?>
               <script type="text/javascript" language="javascript1.2">
            var entrar = confirm("¿Desea Exportar los registros seleccionados? ")
            if ( entrar ){
            location = "exportar.php?codreg=<?php echo $codreg ?>";
            }else{
            location = "formulario.php";
            }
            </script>
                <?php
					/*}/*else{
						echo "Seleccione los registros que desea exportar";
					}*/
				}
			
				?>
              </div></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td width="4" height="19">&nbsp;</td>
          <td width="1196">&nbsp;</td>
          <td width="6">&nbsp;</td>
        </tr>
        <tr>
          <td height="40"></td>
          <td colspan="2" valign="top"><table border="0" cellpadding="0" cellspacing="0" class="textonegro" style="width: 100%">

            <tr>

              <td colspan="2" valign="top">Fecha de Incio <strong>
              <input name="txtfecini" type="text" class="textonegro"  id="txtfecini"  value ="<?php if (isset($_POST['txtfecini'])) echo $_POST['txtfecini']; ?>" size="20"
			  ><input name="reset"  type="reset" class="textonegro"
			  onclick="return showCalendar('txtfecini', '%Y-%m-%d');" value=" ... " >
              </strong>fecha fin <strong>
              <input name="txtfecfin" type="text" class="textonegro"  id="txtfecfin"  value ="<?php if (isset($_POST['txtfecfin'])) echo $_POST['txtfecfin']; ?>" size="20"
			  ><input name="reset"  type="reset" class="textonegro"
			  onclick="return showCalendar('txtfecfin', '%Y-%m-%d');" value=" ... "  >
              </strong></td>
              <td>Seleccionar Todo<br><input type="button" id="toggle" value="select" onClick="do_this()" /></td>
              <td><input name="filtrar" type="submit" id="filtrar" value="Filtrar"/></td>
              <td><div align="center"><button type="submit" id="exportar" name="exportar"><img src="file-xml.png" alt="Exportar Resultados a XML" width="32" height="32" border="0" /><br>
                  Exportar XML</button></div></td>
            </tr>
          </table></td>
          </tr>
        <tr>
          <td height="15"></td>
          <td></td>
          <td></td>
        </tr>

        <tr>
          <td height="111"></td>
          <td valign="top" ><table width="93%" border="0" cellpadding="0" cellspacing="0" bgcolor="#F3F3F3" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td height="8" colspan="2" valign="top" bgcolor="#000000"></td>
                  <td width="50" valign="top" bgcolor="#000000"></td>
                  <td width="150" valign="top" bgcolor="#000000"></td>
                  <td width="150" valign="top" bgcolor="#000000"></td>
                  <td width="150" valign="top" bgcolor="#000000"></td>
                  <td width="150" valign="top" bgcolor="#000000"></td>

                </tr>
            <tr>
              <td width="42" height="30" valign="middle" bgcolor="#FFFFFF" ><div align="center">item</div></td>
                  <td width="133" valign="middle" bgcolor="#FFFFFF" >Titulo Noticia</td>
                  <td align="center" valign="middle" bgcolor="#FFFFFF"  >Url Noticia</td>
                  <td align="center" valign="middle" bgcolor="#FFFFFF" >Autor
                  <td align="center" valign="middle" bgcolor="#FFFFFF" >Fecha</td>
                  <td align="center" valign="middle" bgcolor="#FFFFFF" >categoria</td>
                   <td align="center" valign="middle" bgcolor="#FFFFFF" >idcat</td>
                </tr>

              <?php  if($totalRows_registros > 0){
				$num=$startRow_registros;
				$numero = 0 ;
				do{
					if($numero == 1){
						$numero = 0;
						echo"<tr>" ;
						echo"<td></td>";
						echo"</tr>" ;
					}
				$codreg = $row_registros['nid'];
				$pid1    = 'node/'.$row_registros['nid'];

                $query = " SELECT url_alias.dst AS url , node.nid FROM node
                LEFT JOIN url_alias url_alias ON url_alias.src = '".$pid1."' ";
                $result_query = db_query($query);
                $filpar = mysqli_fetch_assoc($result_query);

		   ?>
              <tr onMouseOver="this.style.backgroundColor='#F5F5F5';" class="pointer" onMouseOut="this.style.backgroundColor='#F3F3F3'" title="Editar Cliente">
			  <td height="21" valign="top"><div align="center">
                <input type="checkbox" name="tercero[]" value="<?php echo $codreg; ?>"   />
                </div></td>
                  <td valign="top"><?php echo $row_registros['node_title']; ?></td>
                  <td align="center" valign="top" ><?php echo $url_link.$filpar['url']; ?></td>
                  <td valign="top" ><div align="center"><?php echo $row_registros['firma']; ?></div></td>
                  <td valign="top" ><div align="center"><?php echo date('Y-m-d\TH:i:s-05:00', $row_registros['node_created']); ?></div></td>
                  <td align="center" valign="top" ><?php echo $row_registros["term_data_name"] ?></td>
                   <td align="center" valign="top" ><?php echo $row_registros["id_categoria"] ?></td>
                 </tr>
                    <?php $numero++; } while ($row_registros = mysqli_fetch_assoc($consulta)); }?>


            <tr>
              <td height="28" colspan="10" align="center" valign="top" bgcolor="#FFFFFF" class="textonegro"><?php
						# variable declaration
						$prev_registros = "&laquo; Anterior";
						$next_registros = "Siguiente &raquo;";
						$separator = " | ";
						$max_links = 10;
						$pages_navigation_registros = paginador($pageNum_registros,$totalPages_registros,$prev_registros,$next_registros,$separator,$max_links,true);
						print $pages_navigation_registros[0];
						?>
                <?php print $pages_navigation_registros[1]; ?> <?php print $pages_navigation_registros[2]; ?></td>
            </tr>
          </table></td>
          <td></td>
        </tr>
        <tr>
          <td height="24"></td>
          <td valign="top" class="textonegro"><div align="center">Ver #
            <select name="selnumreg" id="selnumreg" >
              <option value="10">10</option>
              <option value="20">20</option>
              <option value="30">30</option>
              <option value="40">40</option>
              <option value="50">50</option>
              <option value="60">60</option>
              </select>
            <input name="ver" type="submit" id="ver" value="ver" />
            Resultados <?php echo $totalRows_registros?></div></td>
          <td></td>
        </tr>
        <tr>
          <td height="18"></td>
          <td></td>
          <td></td>
        </tr>
      </table>
	  </form>
    <!-- InstanceEndEditable --></td>
  </tr>


</table>
</form>

</body>




</html>
<?php
mysqli_free_result($consulta);
?>