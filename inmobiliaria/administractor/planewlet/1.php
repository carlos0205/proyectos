<?php
//consulto banner o imagenn de seccion
$qryimg = "SELECT imgpla, tipimg, manvin, url FROM planewlet WHERE codpla = 1";
$resimg = mysql_query($qryimg, $enlace);
$filimg = mysql_fetch_assoc($resimg);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr>
    <td height="166" colspan="3" valign="top"><div align="center">
              <?php 
			  //averiguo extension de imagen
			   // $ext = strrchr($filban["imgindex"],'.');
		   		//$ext = strtolower($ext); if ($ext == ".swf"){
				$datos = GetImageSize("http://".$filema["url"] ."/administractor/planewlet/images/".$filimg["imgpla"]); 
				$x = $datos[0]; 
				$y = $datos[1]; 
				else{  if ($filimg["manvin"]==1){ echo "<a href=http://".$filimg["url"]."  target=".$filimg["abre"]."><img src=\"../planewlet/images/".$filimg["imgpag"]."\" border=0 width=".$x." height=".$y." ></a>";}else{ echo "<img src=\"../planewlet/images/".$filimg["imgpla"]."\"  width=".$x." height=".$y." >"; } ?>
    </div></td>
  </tr>
  <tr>
    <td width="12" height="13"></td>
    <td width="770"></td>
    <td width="18"></td>
  </tr>
  <tr>
    <td height="227"></td>
    <td valign="top"><?php
	$fecha = date("Y-n-j H:i:s");
	//consulto publicaciones
	$qrypub= "SELECT p.codpub, p.nompub, p.texcorpub, p.imgpub, p.feccrepub FROM pubcon p WHERE p.pub = 2 AND p.fecinipub <= '$fecha' AND p.fecfinpub > '$fecha' AND p.pubini = 2 AND p.codidi = '$idioma' AND p.codtipusuter <= '$tipusuter' ORDER BY p.codpub DESC"; 
	$respub = mysql_query($qrypub, $enlace);

		echo "<table  class=textonegro  cellPadding=0  >\n";
		while ($filpub = mysql_fetch_assoc($respub))
			{
				$imgpub = $filpub["imgpub"];
		         echo" <tr>";
                 echo"<td rowspan=4 valign=top  class=marcoimagen><img src=http://".$filaema["url"]."administractor/publicaciones/mini/$imgpub width=114 /></td>";
                 echo"<td rowspan=4 valign=top><!--DWLayoutEmptyCell-->&nbsp;</td>";
                 echo"<td height=19 valign=top><font size=1>".$filpub["feccrepub"]."</font></td>";
                 echo"</tr>";
                 echo"<tr>";
                 echo"<td height=19 valign=top><strong>".$filpub["nompub"]."</strong></td>";
                 echo"</tr>";
                 echo" <tr>";
                 echo" <td height=45 valign=top>".html_entity_decode( $filpub["texcorpub"] )."</td>";
                 echo"</tr>";
                 echo"<tr>";
                 echo"<td height=19 valign=top align = right ><a href=http://".$filaema["url"]."/script/lonuevo/pub.php?idi=$idioma&pub=".$filpub["codpub"].">Leer mas ... </a></td>";
                 echo" </tr>";
		
			}		 		
echo "</table>\n";
		?>			</td>
		<?php 
				echo "<td width= 2%></td>";
				echo "<td valign=top>";
				
				$qryban = "SELECT b.* FROM banner b, bannerpag bp WHERE b.feciniban <= '$fecha' AND b.fecfinban > '$fecha' AND b.pub = 2 AND b.codidi = '$idioma' AND bp.codpos = 2 AND bp.codpag = $link AND b.codban = bp.codban";
				$resban = mysql_query($qryban, $enlace);
				$numban = mysql_num_rows($resban);
				if($numban > 0){
				echo "<table>";
						
				while($filban=mysql_fetch_assoc($resban)){
				echo "<tr><td valign=top>";
				//actualizo carga de banner
				$ban = $filban["codban"];
				$qryactban = "UPDATE banner SET impban = impban + 1 WHERE codban = $ban";
				$resactban = mysql_query($qryactban, $enlace);
				//
				$datos = GetImageSize('../../banner/'.$filban["imgban"].''); 
				$x = $datos[0]; 
				$y = $datos[1]; 
				if($filban["tipimg"]==1){

				?>
                  <script type="text/javascript">
		//var flashvars = {url: "www.google.com"};
		var params = {menu: "false", wmode: "transparent", loop: "false" };
		var attributes = {};
		swfobject.embedSWF("../../banner/<?php echo $filban["imgban"] ?>", "contenido<?php echo $filban["codban"]?>", "<?php echo $x?>", "<?php echo $y?>", "9.0.0", "../../javascripts/expressInstall.swf", "", params, attributes);
		        </script>
                  <div id="contenido<?php echo $filban["codban"];?>">
                    <h1 class="texto">Requiere Flash</h1>
		            <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></p>
                  </div>
		          <?php } else{  if ($filban["manvin"]==1){ echo"  <span onClick=contador(".$filban["codban"].")>" ;
       echo "<a href=http://".$filban["url"]."  target=".$filban["abre"]."><img src=\"../../banner/".$filban["imgban"]."\" border=0 width=".$x." height=".$y." ></a></span>";}else{ echo "<img src=\"../../banner/".$filban["imgban"]."\"  width=".$x." height=".$y." >"; } }

					 
echo" </td>";
echo "</tr>";
							 
}//fin while
  echo "</table>" ;   
		 
			  } 
			  echo "</td>";
				?>
    <td></td>
  </tr>
  <tr>
    <td height="27"></td>
    <td>&nbsp;</td>
    <td></td>
  </tr>
</table>
</body>
</html>
