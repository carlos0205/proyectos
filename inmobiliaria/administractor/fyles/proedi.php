<?php
session_start();
include("general/conexion.php") ;


//XAJAX

//incluímos la clase ajax 
require ('xajax/xajax_core/xajax.inc.php');
//instanciamos el objeto de la clase xajax 
$xajax = new xajax();

$xajax->configure('javascript URI', 'xajax/');

include("fckeditor/fckeditor.php") ;

// fucion validar permisos de acceso a programa
require 'general/permisos.php';
$prog = 'proedi.php';
$usu = $_SESSION["usuario"];
permisos($usu, $prog);

$enlace = enlace();
//capturo accion a realizar 1=editar 0=actualizar
$acc = $_GET["acc"];

//capturo codigo de producto
$cod = $_GET["cod"];

if ($acc == 0){
	//capturo estado de publicacion a actualiza 1=publica , 2=publica en inicio
	$pub = $_GET["pub"];
	$qrypub = "UPDATE pro SET pub='$pub' WHERE codpro='$cod'";
	$respub=mysql_query($qrypub,$enlace);
	
	echo '<script language = JavaScript>
	location = "pro.php";
	</script>';
}else{
	$qryregcla = "SELECT codlin, codsubgru, codcla, codsubcla FROM pro WHERE codpro = '$cod'";
	$resprocla = mysql_query($qryregcla, $enlace);
	$filregcla = mysql_fetch_assoc($resprocla);

	$qryreg = "SELECT p.*, pr.refpro, pd.nompro, pd.despro, ld.nomlin, sgd.nomsubgru, cld.nomcla, scd.nomsubcla, tut.nomtipusuter, f.nomfab, tp.nomtippro
FROM pro p
INNER JOIN prodet pd ON pd.codpro = p.codpro 
INNER JOIN linneg l ON p.codlin = l.codlin 
INNER JOIN linnegdet ld ON l.codlin = ld.codlin AND ld.codidi = 1
LEFT JOIN subgru sg ON p.codsubgru = sg.codsubgru 
LEFT JOIN subgrudet sgd ON sg.codsubgru = sgd.codsubgru AND sgd.codidi = 1
LEFT JOIN cla cl ON p.codcla = cl.codcla
LEFT JOIN cladet cld ON cl.codcla = cld.codcla AND cld.codidi = 1
LEFT JOIN subcla sc ON p.codsubcla = sc.codsubcla
LEFT JOIN subcladet scd ON sc.codsubcla = scd.codsubcla AND scd.codidi = 1
INNER JOIN tipusuter tut ON p.codtipusuter = tut.codtipusuter
INNER JOIN tblproductosreferencias pr ON p.codpro = pr.codpro AND pr.tipo = 'Principal'
LEFT JOIN fab f ON p.codfab = f.codfab
LEFT JOIN tippro tp ON p.codtippro = tp.codtippro
WHERE p.codpro = $cod";
	
	$respro = mysql_query($qryreg, $enlace);
	$filreg = mysql_fetch_assoc($respro);
}

//consulto numero de idiomas
$qrynumidi = "SELECT COUNT(codidi) AS total FROM idipub";
$resnumidi = mysql_query($qrynumidi, $enlace);
$filnumidi = mysql_fetch_assoc($resnumidi);

//consulto parametros del producto
$qrypar= "SELECT * FROM propar";
$respar = mysql_query($qrypar, $enlace);
$filpar = mysql_fetch_assoc($respar);

//consulto parametros de publicacion
$qrypub= "SELECT promin, proori FROM pubpar ";
$respub = mysql_query($qrypub, $enlace);
$filpub = mysql_fetch_assoc($respub);

//consulto formato de moneda defecto
$qrymon = "SELECT m.* FROM tblmonedas m WHERE m.mondefecto = '2' ";
$resmon = mysql_query($qrymon, $enlace);
$filmon = mysql_fetch_assoc($resmon);
	

function agregaimpuesto($form_entrada){
	global $enlace;
	global $cod;
	//averiguo si impuesto ya fue asignado a producto
	//verifico qie impuesto no este asociado 
	$qryexi = "SELECT codproimp FROM tblproductosimpuestos WHERE codpro = $cod AND ci = ".$form_entrada["cbo1codpaino"]." AND codimp = ".$form_entrada["cbo1codimpno"]."";
	$resexi = mysql_query($qryexi, $enlace);
	
	if(mysql_num_rows($resexi)==0){
		$qryimp ="INSERT INTO tblproductosimpuestos VALUES('0','$cod','".$form_entrada["cbo1codimpno"]."','".$form_entrada["cbo1codpaino"]."', '".$form_entrada["txt1valimpno"]."')";
		$resimp =mysql_query($qryimp, $enlace);
		return impuestosproducto(); 
	}
}

function eliminaimpuesto($codproimp){
	global $enlace;
	$qryimp ="DELETE FROM tblproductosimpuestos WHERE codproimp = $codproimp";
	$resimp =mysql_query($qryimp, $enlace);
	return impuestosproducto(); 
}

function impuestosproducto(){
	
	global $enlace;
	global $cod;
	$respuesta = new xajaxResponse();
	
	$qryval = "SELECT i.nomimp , pais.cn , pim.valimp, pim.codproimp
				FROM tblproductosimpuestos pim INNER JOIN tblimpuestos i  ON pim.codimp = i.codimp INNER JOIN pais ON pim.ci = pais.ci
				WHERE pim.codpro =$cod"; 
	$resval = mysql_query($qryval, $enlace);
	
	$salida = "<table class='textonegro' bgcolor='999999' width='100%' ><tr><th align='left'>Impuesto</th><th align='left'>Pais</th><th align='left'>valor</th><th align='left'>Editar</th><th>Eliminar</th></tr>";
	while($filval = mysql_fetch_assoc($resval)){
		$salida .= "<tr>";
		$salida .= "<td>".$filval["nomimp"]."</td>";
		$salida .= "<td>".$filval["cn"]."</td>";
		$salida .= "<td>".$filval["valimp"]."</td>";
		$salida .= "<td><input type='text' name='txt".$filval["codproimp"]."' id='txt".$filval["codproimp"]."' value='".$filval["valimp"]."' onKeyPress=onlyDigits(event,'decOK') size='4' maxlength='4'  onBlur='actualizaimpuesto(".$filval["codproimp"].",this.value)' class='textonegro'></td>";
		$salida .= "<td align='center'><img src='../images/eliminarp.png' width='16' height='16' border='0' onclick='eliminaimpuesto(".$filval["codproimp"].")' alt'Eliminar valor' class='pointer'></td></tr>";	
	}
	$salida.="</table>";
	
	$respuesta->assign("impuestosproducto","innerHTML",$salida); 
	
	return $respuesta;
}

function actualizaimpuesto($codproimp, $valor){
	global $enlace;
	$qryval ="UPDATE tblproductosimpuestos SET valimp = '$valor' WHERE codproimp = $codproimp";
	$resval =mysql_query($qryval, $enlace);
}

//PRECIOS

function agregaprecio($form_entrada){
	global $enlace;
	global $cod;
	
	$qrypre ="INSERT INTO tblproductosprecios VALUES('".$form_entrada["cbo1codlispreno"]."','$cod','".$form_entrada["txt1preprono"]."')";
	$respre =mysql_query($qrypre, $enlace);
	
	return preciosproducto(); 
}

function eliminaprecio($codlispre){
	global $enlace;
	global $cod;
	$qrypre ="DELETE FROM tblproductosprecios WHERE codpro = $cod AND codlispre = $codlispre";
	$respre =mysql_query($qrypre, $enlace);
	return preciosproducto(); 
}

function preciosproducto(){
	
	global $enlace;
	global $cod;
	global $filmon;
	$respuesta = new xajaxResponse();

	$qryval = "SELECT  lp.nomlispre , pp.prepro , lp.codlispre
	FROM  tblproductosprecios pp INNER JOIN tbllistasdeprecio lp  ON pp.codlispre = lp.codlispre
	WHERE pp.codpro =$cod"; 
	$resval = mysql_query($qryval, $enlace);
	
	$salida = "<table class='textonegro' bgcolor='999999' width='100%' ><tr><th align='left'>Lista de precio</th><th align='left'>Valor</th><th align='left'>Editar</th><th align='center'>Eliminar</th></tr>";
	while($filval = mysql_fetch_assoc($resval)){
		$salida .= "<tr>";
		$salida .= "<td>".$filval["nomlispre"]."</td>";
		$salida .= "<td>".$filmon["symbolizq"]." ".number_format($filval["prepro"],$filmon["lugdec"],$filmon["pundec"],$filmon["punmil"])." ".$filmon["symbolder"]."</td>";
		$salida .= "<td><input type='text' name='txt".$filval["codlispre"]."' id='txt".$filval["codlispre"]."' value='".$filval["prepro"]."' size='10' maxlength='10'  onBlur='actualizaprecio(".$filval["codlispre"].",this.value)' class='textonegro' onKeyPress=onlyDigits(event,'decOK') ></td>";
		$salida .= "<td align='center'><img src='../images/eliminarp.png' width='16' height='16' border='0' onclick='eliminaprecio(".$filval["codlispre"].")' alt'Eliminar valor' class='pointer'></td>";
		$salida .= "</tr>";	
	}
	$salida.="</table>";
	
	$qrylis = "SELECT l.codlispre, l.nomlispre FROM tbllistasdeprecio l WHERE l.codlispre NOT IN (SELECT codlispre FROM tblproductosprecios WHERE codpro = $cod) ORDER BY l.nomlispre";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1codlispreno' id='cbo1codlispreno'  class='textonegro'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codlispre"]."'>".$fillis["nomlispre"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("listadeprecio","innerHTML",$lista); 
	$respuesta->assign("preciosproducto","innerHTML",$salida); 
	
	return $respuesta;
}

function actualizaprecio($lista, $valor){
	global $enlace;
	global $cod;
	$qryval ="UPDATE tblproductosprecios SET prepro = '$valor' WHERE codlispre = $lista AND codpro = $cod";
	$resval =mysql_query($qryval, $enlace);
	
	return preciosproducto();
}
///////REFERENCIAS

function agregareferencia($form_entrada){
	global $enlace;
	global $cod;
	$respuesta = new xajaxResponse();
	//valida que referencia no exista ya para el producto
	$qryexi = "SELECT pr.codproref FROM tblproductosreferencias pr WHERE pr.refpro = '".$form_entrada["txt1refprono"]."' AND pr.codpro = $cod";
	$resexi = mysql_query($qryexi, $enlace);
	if(mysql_num_rows($resexi) > 0){
		
		$respuesta->alert("La referencia ya existe para el producto");
		$respuesta->assign("txt1refprono","value","");
		return $respuesta; 
		
	}else{
		$qryref ="INSERT INTO tblproductosreferencias VALUES('0', '$cod', '".$form_entrada["txt1refprono"]."','".$form_entrada["txt1preciono"]."','".$form_entrada["cbo1accionno"]."','".$form_entrada["txt1existenciasno"]."','Adicional')";
		$resref =mysql_query($qryref, $enlace);
		return referenciasproducto(); 
		
	}
	
	
}

function eliminareferencia($codproref){
	global $enlace;
	$qryref ="DELETE FROM tblproductosreferencias WHERE codproref = $codproref";
	$resref =mysql_query($qryref, $enlace);
	return referenciasproducto(); 
}

function referenciasproducto(){
	
	global $enlace;
	global $cod;
	global $filmon;
	$respuesta = new xajaxResponse();

	$qryref = "SELECT  r.* FROM tblproductosreferencias r WHERE r.codpro =$cod"; 
	$resref = mysql_query($qryref, $enlace);
	
	$salida = "<table class='textonegro' bgcolor='999999' width='100%' ><tr><th align='left'>Referencia</th><th align='left'>Valor Adicional</th><th align='left'>Valor Adicional</th><th align='left'>Acción</th><th align='left'>Existencias</th><th align='left'>Opciones</th><th align='left'>Eliminar</th></tr>";
	while($filref = mysql_fetch_assoc($resref)){
		$salida .= "<tr>";
		$salida .= "<td><input type='text' name='txtr".$filref["codproref"]."' id='txtr".$filref["codproref"]."' value='".$filref["refpro"]."' size='16' maxlength='16'  onBlur='actualizareferencia(".$filref["codproref"].")' class='textonegro'></td>";
		$salida .= "<td>".$filmon["symbolizq"]." ".number_format($filref["precio"],$filmon["lugdec"],$filmon["pundec"],$filmon["punmil"])." ".$filmon["symbolder"]."</td>";
		if($filref["tipo"]=="Principal"){
		$salida .= "<td><input type='text' name='txtp".$filref["codproref"]."' id='txtp".$filref["codproref"]."' value='".$filref["precio"]."' size='10' maxlength='50'  onBlur='actualizareferencia(".$filref["codproref"].",this.value)' class='textonegro' onKeyPress=onlyDigits(event,'decOK') readonly=''></td>";
		}else{
		$salida .= "<td><input type='text' name='txtp".$filref["codproref"]."' id='txtp".$filref["codproref"]."' value='".$filref["precio"]."' size='10' maxlength='50'  onBlur='actualizareferencia(".$filref["codproref"].",this.value)' class='textonegro' onKeyPress=onlyDigits(event,'decOK') ></td>";
		}
		$salida .= "<td><input type='text' name='txta".$filref["codproref"]."' id='txta".$filref["codproref"]."' value='".$filref["accion"]."' size='2' maxlength='50'  onBlur='actualizareferencia(".$filref["codproref"].",this.value)' class='textonegro'></td>";
		$salida .= "<td><input type='text' name='txte".$filref["codproref"]."' id='txte".$filref["codproref"]."' value='".$filref["existencias"]."' size='5' maxlength='50'  onBlur='actualizareferencia(".$filref["codproref"].",this.value)' class='textonegro' onKeyPress=onlyDigits(event,'decOK') ></td>";
		$salida .= "<td align='center'><img src='../images/opciones.png' width='16' height='16' border='0' onclick=referencia(".$filref["codproref"].",'".$filref["refpro"]."') title='Opciones de la referencia ".$filref["refpro"]."' class='pointer'></td>";
		if($filref["tipo"]=='Principal'){
		$salida .= "<td align='center'>Ref. Principal</td>";
		}else{
		$salida .= "<td align='center'><img src='../images/eliminarp.png' width='16' height='16' border='0' onclick='eliminareferencia(".$filref["codproref"].")' title = 'Eliminar referencia' class='pointer'></td>";
		}
		$salida .= "</tr>";	
	}
	$salida.="</table>";
	
	$respuesta->assign("referenciasproducto","innerHTML",$salida); 
	
	return $respuesta;
}

function actualizareferencia($codproref, $form_entrada){
	global $enlace;
	global $cod;
	$respuesta = new xajaxResponse();
	//valida que referencia no exista ya para el producto
	$qryexi = "SELECT pr.codproref FROM tblproductosreferencias pr WHERE  pr.refpro = ".$form_entrada["txtr".$codproref]." AND pr.codproref <> $codproref";
	$resexi = mysql_query($qryexi, $enlace);
	
	if(mysql_num_rows($resexi) > 0){
	
		$respuesta->alert("La referecnia ya existe para el producto");
		$respuesta->assign("txtr".$codproref,"value","");
		return $respuesta;
		
	}else{
	
		$qryref ="UPDATE tblproductosreferencias SET refpro = '".$form_entrada["txtr".$codproref]."', precio = '".$form_entrada["txtp".$codproref]."', accion= '".$form_entrada["txta".$codproref]."', existencias = '".$form_entrada["txte".$codproref]."' WHERE codproref = $codproref";
		$resref = mysql_query($qryref, $enlace);
		return referenciasproducto();
	}
	
}

///OPCIOONES DE REFERENCIA

function referencia($codproref, $referencia){

	$respuesta = new xajaxResponse();
	
	$respuesta->assign("txt1codprorefno","value",$codproref);
	$respuesta->assign("txt1referenciano","value",$referencia);
	$respuesta->script("opcionesreferencia()");
	
	return $respuesta;

}

function valores($opcion){
	
	global $enlace;
	$respuesta = new xajaxResponse();

	$qrylis = "SELECT v.codval, vd.nomval FROM tblproductosvalores v INNER JOIN tblproductosvaloresdetalle vd ON v.codval = vd.codval WHERE v.codopt = $opcion AND vd.codidi = 1 ORDER BY vd.nomval";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1codvalno' id='cbo1codvalno'  class='textonegro'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codval"]."'>".$fillis["nomval"]."</option>/n";
	}
	$lista.= "</select>";

	$respuesta->assign("cbovalores","innerHTML",$lista); 
	
	return $respuesta;
}


function agregaopcion($form_entrada){

	global $enlace;
	$qryval = "INSERT INTO tblproductosreferenciasopcionvalor VALUES ('0','".$form_entrada["txt1codprorefno"]."','".$form_entrada["cbo1codvalno"]."')";
	$resval = mysql_query($qryval, $enlace);
	
	return opcionesreferencia($form_entrada);
}

function eliminaopcion($codprorefval, $form_entrada){

	global $enlace;
	$qryval = "DELETE FROM tblproductosreferenciasopcionvalor WHERE codprorefval = $codprorefval";
	$resval = mysql_query($qryval, $enlace);
	
	return opcionesreferencia($form_entrada);
}

function opcionesreferencia($form_entrada){
	global $enlace;
	$respuesta = new xajaxResponse();
	
	//consulto las opciones pendientes por asociar a la referencia.
	$qrylis = "SELECT po.codopt, pod.nomopt 
FROM tblproductosopciones AS po 
	INNER JOIN tblproductosopcionesdetalle AS pod
		ON po.codopt = pod.codopt
WHERE pod.codidi = 1 AND po.codopt NOT IN (
						SELECT pod.codopt 
						FROM	tblproductosopcionesdetalle AS pod	INNER JOIN tblproductosopciones AS po	ON pod.codopt = po.codopt
							INNER JOIN tblproductosvalores AS pv
								ON po.codopt = pv.codopt
							INNER JOIN tblproductosreferenciasopcionvalor AS prov
								ON pv.codval = prov.codval
							INNER JOIN tblproductosreferencias AS pr
								ON prov.codproref = pr.codproref
							INNER JOIN tblproductosvaloresdetalle AS pvd
								ON pv.codval = pvd.codval
						WHERE pr.codproref =".$form_entrada["txt1codprorefno"]." AND pvd.codidi =1 AND pod.codidi =1
					)";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1codoptno' id='cbo1codoptno'  class='textonegro' onChange='valores(this.value)'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codopt"]."'>".$fillis["nomopt"]."</option>/n";
	}
	$lista.= "</select>";

	$qryoptval = "SELECT  prov.codprorefval , pod.nomopt , pvd.nomval , pvd.codidi , pod.codidi 
FROM	tblproductosopcionesdetalle AS pod	INNER JOIN tblproductosopciones AS po	ON pod.codopt = po.codopt
	INNER JOIN tblproductosvalores AS pv
		ON po.codopt = pv.codopt
	INNER JOIN tblproductosreferenciasopcionvalor AS prov
		ON pv.codval = prov.codval
	INNER JOIN tblproductosreferencias AS pr
		ON prov.codproref = pr.codproref
	INNER JOIN tblproductosvaloresdetalle AS pvd
		ON pv.codval = pvd.codval
WHERE pr.codproref =".$form_entrada["txt1codprorefno"]."    AND pvd.codidi =1    AND pod.codidi =1";
	$resoptval = mysql_query($qryoptval, $enlace);
	
	//consulto referencia de producto
	
	$salida = "<table class='textonegro' bgcolor='999999' width='100%' >";
	$salida.="<tr><th align='left'>Opciones de referencia ".$form_entrada["txt1referenciano"]."</th></tr>";
	$salida.= "<tr><th align='left'>Opción</th><th align='left'>Valor </th><th align='center'>Eliminar</th></tr>";
	while($filoptval = mysql_fetch_assoc($resoptval)){
		$salida .= "<tr>";
		$salida .= "<td>".$filoptval["nomopt"]."</td>";
		$salida .= "<td>".$filoptval["nomval"]."</td>";
		$salida .= "<td align='center'><img src='../images/eliminarp.png' width='16' height='16' border='0' onclick='eliminaopcion(".$filoptval["codprorefval"].")' title = 'Eliminar opción' class='pointer'></td>";
		$salida .="</tr>";
		
		}
	$salida .="</table>";
	
	$listaval = "<select name='cbo1codvalno' id='cbo1codvalno'  class='textonegro' >/n";
	$listaval.= "<option value='0'>Elige</option>";
	$listaval.= "</select>";
	
	$respuesta->assign("cboopciones","innerHTML",$lista); 
	$respuesta->assign("cbovalores","innerHTML",$listaval); 
	$respuesta->assign("opcionesreferencia","innerHTML",$salida); 
	return $respuesta;

}


function subgrupo($lin){
	global $enlace;
	$respuesta = new xajaxResponse();
	
	$qrylis ="SELECT sd.codsubgru, sd.nomsubgru FROM subgru AS s 
INNER JOIN subgrudet AS sd ON s.codsubgru = sd.codsubgru AND sd.codidi =1
WHERE s.codlin = $lin ";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1codsubgrusi' id='cbo1codsubgrusi'  class='textonegro' onChange='xajax_clase(this.value)' title='Subgrupo'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codsubgru"]."'>".$fillis["nomsubgru"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("subgrupo","innerHTML",$lista); 
	
	return $respuesta;
}
function clase($subgru){
	global $enlace;
	$respuesta = new xajaxResponse();
	
	$qrylis ="SELECT cd.codcla, cd.nomcla FROM cla AS c 
INNER JOIN cladet AS cd ON c.codcla = cd.codcla AND cd.codidi =1
WHERE c.codsubgru = $subgru ";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1codclasi' id='cbo1codclasi'  class='textonegro' onChange='xajax_subclase(this.value)' title='Clase'>/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codcla"]."'>".$fillis["nomcla"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("clase","innerHTML",$lista); 
	
	return $respuesta;
}
function subclase($clase){
	global $enlace;
	$respuesta = new xajaxResponse();
	
	$qrylis ="SELECT sd.codsubcla, sd.nomsubcla FROM subcla AS s 
INNER JOIN subcladet AS sd ON s.codsubcla = sd.codsubcla AND sd.codidi =1
WHERE s.codcla = $clase ";
	$reslis = mysql_query($qrylis, $enlace);
	$lista = "<select name='cbo1codsubclasi' id='cbo1codsubclasi'  class='textonegro' title='Sub-Clase' >/n";
	$lista.= "<option value='0'>Elige</option>";
	while ($fillis = mysql_fetch_array($reslis)){
		$lista.= "<option value='".$fillis["codsubcla"]."'>".$fillis["nomsubcla"]."</option>/n";
	}
	$lista.= "</select>";
	
	$respuesta->assign("subclase","innerHTML",$lista); 
	
	return $respuesta;
}

function validareferencia($ref){
	global $enlace;
	global $cod;
	$respuesta = new xajaxResponse();
	$qrypro = "SELECT pd.codpro, pd.nompro FROM prodet AS pd 
	INNER JOIN tblproductosreferencias AS pr ON pd.codpro = pr.codpro AND pd.codidi = 1
	 WHERE pr.refpro = '$ref' AND pd.codpro <> $cod";
	$respro = mysql_query($qrypro);

	if(mysql_num_rows($respro) > 0){
		$filpro = mysql_fetch_assoc($respro);
		$respuesta->alert("La referencia ya esta asociada al producto: ".$filpro["nompro"]);
		$respuesta->asign("txt2refprosi","value","");
		return $respuesta;
	}
	
}

$xajax->registerFunction("agregaimpuesto");
$xajax->registerFunction("eliminaimpuesto");
$xajax->registerFunction("impuestosproducto");
$xajax->registerFunction("actualizaimpuesto");
$xajax->registerFunction("agregaprecio");
$xajax->registerFunction("eliminaprecio");
$xajax->registerFunction("preciosproducto");
$xajax->registerFunction("actualizaprecio");
$xajax->registerFunction("agregareferencia");
$xajax->registerFunction("eliminareferencia");
$xajax->registerFunction("referenciasproducto");
$xajax->registerFunction("actualizareferencia");
$xajax->registerFunction("referencia");
$xajax->registerFunction("valores");
$xajax->registerFunction("agregaopcion");
$xajax->registerFunction("eliminaopcion");
$xajax->registerFunction("opcionesreferencia");
$xajax->registerFunction("subgrupo");
$xajax->registerFunction("clase");
$xajax->registerFunction("subclase");
$xajax->registerFunction("validareferencia");
//El objeto xajax tiene que procesar cualquier petición 
$xajax->processRequest(); 

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
<script type="text/javascript" src="general/selectdependiente2.js"></script>
<script type="text/javascript"  src="general/validaform.js"></script>
<script  language="javascript" type="text/javascript">
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

function eliminaidioma(idioma){
var entrar = confirm("¿Desea eliminar el idioma del producto?")
		if ( entrar ) 
		{
			location = "proeliidi.php?cod=<?php echo $cod ?>&codidi="+idioma;
		}else{
			return false;
		}

}

function nombrefoto()
{	
var filename = document.form1.img1fileno.value ;
filename = filename.substr(filename.lastIndexOf('\\')+1);
var extenciones = new Array("jpg","jpeg","png","gif");
var tipo = filename.substr(filename.lastIndexOf('.')+1);

	for(i=0; i<extenciones.length; i++)
	   {
	   if(extenciones[i] == tipo){
	   	   pasa = true;
		   break;
			
		}else{
			pasa = false;
		}
	} 
	
	if(pasa){
		document.form1.hid1imgprosi.value ="<?php echo $cod?>."+tipo;
	}else{
		alert("El tipo de archivo no es permitido");
		document.form1.img1fileno.value="";
		document.form1.hid1imgprosi.value ="<?php echo $filreg["imgpro"]?>";
	}
}
//IMPUESTOS PRODUCTO
function agregaimpuesto()
{
	if(document.form1.cbo1codimpno.value==0){
	alert("Debe seleccionar el impuesto a asociar")
	document.form1.cbo1codimpno.focus()
	exit();
	}
	
	if(document.form1.cbo1codpaino.value==0){
	alert("Debe seleccionar el pais sobre el cual aplica el impuesto")
	document.form1.cbo1codpaino.focus()
	exit();
	}
	
	if(document.form1.txt1valimpno.value==""){
	alert("Debe ingresar el valor del impuesto")
	document.form1.txt1valimpno.focus()
	exit();
	}
	
	xajax_agregaimpuesto(xajax.getFormValues("form1"));
}

function eliminaimpuesto(codproimp)
{
xajax_eliminaimpuesto(codproimp);
}

function impuestosproducto()
{
xajax_impuestosproducto();
}

function actualizaimpuesto(impuesto, valor ){
	xajax_actualizaimpuesto(impuesto, valor);
}

//PRECIOS PRODUCTO
function agregaprecio()
{

	if(document.form1.cbo1codlispreno.value==0){
	alert("Debe seleccionar la lista de precio del producto")
	document.form1.cbo1codlispreno.focus()
	exit();
	}
	
	if(document.form1.txt1preprono.value==""){
	alert("Debe ingresar el precio del producto")
	document.form1.txt1preprono.focus()
	exit();
	}
	
	xajax_agregaprecio(xajax.getFormValues("form1"));
}

function eliminaprecio(codlispre)
{
	xajax_eliminaprecio(codlispre);
}

function preciosproducto()
{
	xajax_preciosproducto();
}

function actualizaprecio(lista, precio ){
	xajax_actualizaprecio(lista, precio);
}

//REFERENCIAS PRODUCTO
function agregareferencia()
{
	
	if(document.form1.txt1refprono.value==""){
	alert("Debe ingresar la referencia del producto")
	document.form1.txt1refprono.focus()
	return false;
	exit();
	}
	
	var invalid = " ";
	if (document.form1.txt1refprono.value.indexOf(invalid) > -1) {
			alert("No se permiten espacios en la referencia.");
			document.form1.txt1refprono.focus();
			return false;
			exit();
	}
	
	if(document.form1.txt1preciono.value==""){
	alert("Debe ingresar el valor adicional del producto")
	document.form1.txt1preciono.focus()
	return false;
	exit();
	}
	
	if(document.form1.cbo1accionno.value==0){
	alert("Debe ingresar la accion para el precio del producto")
	document.form1.cbo1accionno.focus()
	return false;
	exit();
	}
	
	if(document.form1.txt1existenciasno.value==""){
	alert("Debe ingresar las existencias del producto")
	document.form1.txt1existenciasno.focus()
	return false;
	exit();
	}
	
	xajax_agregareferencia(xajax.getFormValues("form1"));
}

function eliminareferencia(codref)
{
	xajax_eliminareferencia(codref);
}

function referenciasproducto()
{
	xajax_referenciasproducto();
}

function actualizareferencia(referencia ){
	xajax_actualizareferencia(referencia, xajax.getFormValues("form1"));
}

////OPCIONNES VALORES PRODUCTO

function referencia(codproref, referencia){
	xajax_referencia(codproref, referencia);
}

function valores(opcion){
	xajax_valores(opcion);
}

function agregaopcion(){
	if(document.form1.cbo1codoptno.value==0){
	alert("Debe seleccionar la opcion para la referencia")
	document.form1.cbo1codoptno.focus()
	exit();
	}
	
	if(document.form1.cbo1codvalno.value==0){
	alert("Debe seleccionar el valor para la opción")
	document.form1.cbo1codvalno.focus()
	exit();
	}
	
	xajax_agregaopcion(xajax.getFormValues("form1"));
}

function eliminaopcion(codprorefval){
	xajax_eliminaopcion(codprorefval, xajax.getFormValues("form1"));
}


function opcionesreferencia(){
	xajax_opcionesreferencia(xajax.getFormValues("form1"));
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
                  <td width="915">&nbsp;</td>
                  <td width="33">&nbsp;</td>
                  <td width="63" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="guardarno" type="submit" value="guardarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro(); "><img width="32" src="../images/guardar.png"  /><br>
                  Guardar</button></td>
                  <td width="63" rowspan="3" align="center" valign="middle"><button class="textonegro"   name="aplicarno" type="submit" value="aplicarno" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;" onClick="return crearregistro()"><img width="32" src="../images/aplicar.png"  /><br>
                  Aplicar</button></td>
                  <td width="62" rowspan="3" align="center" valign="middle"><button class="textonegro" name="cancelarno" type="submit" value="cancelar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer;"><img src="../images/cancelar.png" width="32" height="32"  /><br>
                  Cancelar</button></td>
                  <td width="14"></td>
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
				
				function cargarimagen(){
	global $enlace;
	global $filpub;
	global $filreg;
	global $cod;
	
	$continua = TRUE;

	//Verifico si se inserta imagen de la publicación
	$file_name = $_FILES['img1fileno']['name'];
	if( $file_name <> ""){ //if 3
		
		$continua = TRUE; 

		//Ruta donde guardamos las imágenes
		$ruta_miniaturas = "../productos/mini";
		$ruta_original = "../productos";
								
		//El ancho de la miniatura
		$ancho_miniatura = $filpub["promin"];
		$ancho_original = $filpub["proori"]; 
		
		//Extensiones permitidas
		$extensiones = array(".gif",".jpg",".png",".jpeg");
		$datosarch = $_FILES["img1fileno"];
		$file_type = $_FILES['img1fileno']['type'];
		$file_size = $_FILES['img1fileno']['size'];
		$file_tmp = $_FILES['img1fileno']['tmp_name'];
		
		//validar la extension
		$ext = strrchr($file_name,'.');
		$ext = strtolower($ext);
		if (!in_array($ext,$extensiones)) {	 //if 5	   
			echo "¡El tipo de archivo no es permitido!";
			$continua = FALSE;			  
		} // fin if 5
		if($continua){  //if
			// validar tamaño de archivo	   
			if  ($file_size > 8368308) //bytes = 8368308 = 8392kb = 8Mb
			/*Copia el archivo en una directorio específico del servidor*/
			{ //if 7
				echo "¡El archivo debe ser inferior a 8MB!";						
				$continua = FALSE;				
			} //fin if 7
			if ($continua){ //if 
				//Tomamos la extension
				
							if(file_exists($ruta_original."/".$filreg["imgpro"])){
									//eliminamos la imagen original
									unlink($ruta_original."/".$filreg["imgpro"]);
									unlink($ruta_miniaturas."/".$filreg["imgpro"]);
								}
								
							$getExt = explode ('.', $file_name);
							$file_ext = $getExt[count($getExt)-1];  
							$ThumbWidth = $ancho_miniatura;
							$ThumbWidth1 = $ancho_original;
							   
							//buscamos la funcion segun la imagen
							if($file_size){
								if($file_type == "image/pjpeg" || $file_type == "image/jpeg"){
									$nueva_imagen = imagecreatefromjpeg($file_tmp);
								}elseif($file_type == "image/x-png" || $file_type == "image/png"){
								   $nueva_imagen = imagecreatefrompng($file_tmp);
								}elseif($file_type == "image/gif"){
								   $nueva_imagen = imagecreatefromgif($file_tmp);
								}
								//Chequeamos el ancho y el alto para mantener la relacion de aspecto
								list($width, $height) = getimagesize($file_tmp);
								$imgratio=$width/$height;
									   
								if ($imgratio>1){
									$nuevo_ancho = $ThumbWidth;
									$nuevo_alto = $ThumbWidth/$imgratio;
									$nuevo_ancho1 = $ThumbWidth1;
									$nuevo_alto1 = $ThumbWidth1/$imgratio;
								}else{
									$nuevo_alto = $ThumbWidth;
									$nuevo_ancho = $ThumbWidth*$imgratio;
									$nuevo_alto1 = $ThumbWidth1;
									$nuevo_ancho1 = $ThumbWidth1*$imgratio;
								}
								$redimensionada = imagecreatetruecolor($nuevo_ancho,$nuevo_alto);
								$redimensionada1 = imagecreatetruecolor($nuevo_ancho1,$nuevo_alto1);
								
								imagecopyresized($redimensionada, $nueva_imagen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $width, $height);
								imagecopyresized($redimensionada1, $nueva_imagen, 0, 0, 0, 0, $nuevo_ancho1, $nuevo_alto1, $width, $height);
								
								$nombre_nuevaimg = $cod.".".$file_ext; 
				
								//guardamos la imagen
								ImageJpeg ($redimensionada,"$ruta_miniaturas/$nombre_nuevaimg", $filpub["promin"]);
								ImageDestroy ($redimensionada);
								
							}
							//Subimos la imagen original
							ImageJpeg ($redimensionada1,"$ruta_original/$nombre_nuevaimg", $filpub["proori"]);
							
							//move_uploaded_file ($redimensionada1, "$ruta_original/$nombre_nuevaimg");
							ImageDestroy ($redimensionada1);
							ImageDestroy ($nueva_imagen);
							return($continua);			

				} //fin if 
	
				return($continua);
			}// fin if 
	}else{
		return($continua);
	}//fin if 3
}
								
						
	if (isset($_POST['guardarno'])){
		$continua = cargarimagen();
		
		if($continua){
			$qryregdetact = "UPDATE prodet SET nompro = '".$_POST["txt2nomprono"]."', despro = '".$_POST["txt1desprono"]."' WHERE codpro = '$cod' AND codidi = '1'";
			$resprodetact = mysql_query($qryregdetact, $enlace);
			auditoria($_SESSION["enlineaadm"],'Productos',$cod,'4');
			actualizar("pro",2,$cod,"codpro","pro.php");
		}
	}
	if (isset($_POST['aplicarno'])){
		$continua = cargarimagen();
		
		if($continua){
			$qryregdetact = "UPDATE prodet SET nompro = '".$_POST["txt2nomprono"]."', despro = '".$_POST["txt1desprono"]."' WHERE codpro = '$cod' AND codidi = '1'";
			$resprodetact = mysql_query($qryregdetact, $enlace);
			auditoria($_SESSION["enlineaadm"],'Productos',$cod,'4');
			actualizar("pro",2,$cod,"codpro","proedi.php?cod=$cod&acc=1");
		}			
	}
	//boton cancelar cambios
	if (isset($_POST['cancelarno'])){
		echo '<script language = JavaScript>
		location = "pro.php";
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
              <td width="1390" height="52" valign="top" class="titulos"><img src="../images/carrito.png" width="48" height="48" align="absmiddle" />Productos [Edita] <strong></strong></td>
                </tr>
              </table></td>
          </tr>
        <tr>
          <td height="344">&nbsp;</td>
          <td valign="top"><table width="58%" height="668" border="0" cellpadding="0" cellspacing="0" bgcolor="#F5F5F5" class="marcotabla" style="width: 100%">
            <!--DWLayoutTable-->
            <tr>
              <td width="5" height="13"></td>
                  <td width="171"></td>
                  <td width="117"></td>
                  <td width="315"></td>
                  <td width="18"></td>
                  <td width="152"></td>
                  <td width="100"></td>
                  <td width="31"></td>
                  <td width="86"></td>
                  <td width="62"></td>
                  <td width="18"></td>
                  <td width="62"></td>
                  <td width="34"></td>
                </tr>
            <tr>
              <td height="39"></td>
              <td colspan="3" valign="top"><p>
                Nombre 
                <br>
                    <input name="txt2nomprono" type="text" class="textonegro" id="txt2nomprono" value="<?php echo $filreg["nompro"]; ?>" size="50" maxlength="100"/>
              </p></td>
                  <td>&nbsp;</td>
              <td colspan="7" valign="top">Nivel de Acceso<br>
                    <select name="cbo2codtipusutersi" class="textonegro" id="cbo2codtipusutersi" title="Nivel de acceso">
                      <?
						$ter =  $filreg["codtipusuter"];
						$qryter = "SELECT * FROM tipusuter WHERE codtipusuter <> '$ter' AND codtipusuter < 3 ORDER BY codtipusuter DESC ";
						$rester = mysql_query($qryter, $enlace);
						echo "<option selected value=\"".$filreg["codtipusuter"]."\">".$filreg["nomtipusuter"]."</option>\n";
						while ($filter = mysql_fetch_array($rester))
						echo "<option value=\"".$filter["codtipusuter"]."\">".$filter["nomtipusuter"]."</option>\n";
						mysql_free_result($rester);
					?>
                        </select></td>
              <td></td>
              </tr>
            
            <tr>
              <td height="1"></td>
              <td></td>
              <td colspan="2" rowspan="3" valign="top"  <?php if($filpar["fab"] == 1){?>style=" visibility:hidden" <?php }?>>Fabricante<br>
                <select name="cbo1codfabsi" class="textonegro" id="cbo1codfabsi">
                  <?
				$fab=  $filreg["codfab"];
				$qryfab = "SELECT codfab, nomfab FROM fab WHERE codfab <> '$fab' AND estfab ='Activo' ORDER BY nomfab ";
				$resfab = mysql_query($qryfab, $enlace);
				echo "<option selected value=\"".$filreg["codfab"]."\">".$filreg["nomfab"]."</option>\n";
				while ($filfab = mysql_fetch_array($resfab))
					echo "<option value=\"".$filfab["codfab"]."\">".$filfab["nomfab"]."</option>\n";
					mysql_free_result($resfab);
			?>
                </select>              </td>
              <td></td>
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
              <td height="4"></td>
              <td rowspan="3" valign="top" <?php if($filpar["tp"] == 1){?>style=" visibility:hidden" <?php }?>> Tipo de Producto                
                <br>                <select name="cbo1codtipprosi" class="textonegro" id="cbo1codtipprosi" title="Tipo de producto">
                  <?
				$tip =  $filreg["codtippro"];
				$qrytip = "SELECT * FROM tippro WHERE codtippro <> '$tip' ORDER BY nomtippro ";
				$restip = mysql_query($qrytip, $enlace);
				echo "<option selected value=\"".$filreg["codtippro"]."\">".$filreg["nomtippro"]."</option>\n";
				while ($filtip = mysql_fetch_array($restip))
					if($filreg["codtippro"]<3){
						echo "<option value=\"".$filtip["codtippro"]."\">".$filtip["nomtippro"]."</option>\n";
					}
				mysql_free_result($restip);
			?>
                </select></td>
              <td></td>
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
              <td height="27"></td>
              <td></td>
              <td colspan="7" rowspan="9" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro">
                <!--DWLayoutTable-->
                <tr>
                  <td width="156" height="49" align="center" valign="middle">IMAGEN PRODUCTO </td>
                        <td width="11" rowspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                        <td width="67" valign="middle"<?php if($filpar["manvis"] == 1){?> style=" visibility:hidden" <?php }?>><div align="center">VISTAS</div></td>
                        <td width="17" rowspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                        <td width="71" valign="middle"<?php if($filpar["carcol"] == 1){?> style=" visibility:hidden" <?php }?>><div align="center">CARTA DE COLORES </div></td>
                        <td width="15" rowspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                        <td width="72" valign="middle" <?php if($filpar["proman"] == 1){?> style=" visibility:hidden" <?php }?>><div align="center">MANUALES TECNICOS </div></td>
                        <td width="15" rowspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                        <td width="71" valign="middle"><div align="center" <?php if($filpar["manpin"] == 1){?> style=" visibility:hidden" <?php }?>>PINTAS</div></td>
                      </tr>
                <tr>
                  <td height="114" align="center" valign="middle" bgcolor="#FFFF99"><img src="../productos/mini/<?php echo $filreg["imgpro"]; ?>" width="102" /></td>
                        <td valign="middle" class="marcotabla"   bgcolor="#FFFFFF" <?php if($filpar["manvis"] == 1){?> style=" visibility:hidden" <?php }?>><div align="center"><a href="provis.php?cod=<?php echo $cod;?>"><img src="../images/vistas.png" width="48" height="48" border="0" /></a></div></td>
                        <td valign="middle" class="marcotabla"   bgcolor="#FFFFFF" <?php if($filpar["carcol"] == 1){?> style=" visibility:hidden" <?php }?>><div align="center"><a href="procol.php?cod=<?php echo $cod;?>"><img src="../images/carcol.png" width="48" height="48" border="0" /></a></div></td>
                        <td valign="middle" class="marcotabla"   bgcolor="#FFFFFF"  <?php if($filpar["proman"] == 1){?> style=" visibility:hidden" <?php }?>><div align="center"><a href="proman.php?cod=<?php echo $cod;?>"><img src="../images/manuales.png" width="48" height="48" border="0" /></a></div></td>
                        <td valign="middle" class="marcotabla"   bgcolor="#FFFFFF"  <?php if($filpar["manpin"] == 1){?> style=" visibility:hidden" <?php }?>><div align="center"><a href="propin.php?cod=<?php echo $cod;?>"><img src="../images/pintas.png" width="48" height="48" border="0" /></a></div></td>
                      </tr>
                <tr>
                  <td height="2"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
              </table></td>
              <td></td>
              </tr>
            <tr>
              <td height="1"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="6"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            
            
            <tr>
              <td height="25"></td>
              <td valign="top" class="textonegro">Publicar                  <br>
                <select name="cbo2pubsi" class="textonegro" id="cbo2pubsi" title="Publicar ">
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
                                </select></td>
              <td valign="top"  <?php if($filpar["manpin"] == 1){?>style=" visibility:hidden" <?php }?>>Mostrar Pintas                  <br>
                <select name="cbo1mospinsi" class="textonegro" id="cbo1mospinsi" title="Publicar alb&uacute;m">
                  <?php
					  $qrypub="SELECT 'Si' AS publica
						UNION
						SELECT 'No' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['mospin']."\">".$filreg['mospin']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["mospin"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
                                                </select></td>
              <td valign="top" class="textonegro"  <?php if($filpar["carcol"] == 1){?>style=" visibility:hidden" <?php }?>>  Mostrar Colores                  <br>
                <select name="cbo1moscolsi" class="textonegro" id="cbo1moscolsi" title="Publicar alb&uacute;m">
                  <?php
					  $qrypub="SELECT 'Si' AS publica
						UNION
						SELECT 'No' AS publica";
						$respub = mysql_query($qrypub, $enlace);
						echo "<option selected value=\"".$filreg['moscol']."\">".$filreg['moscol']."</option>\n";
						while ($filpub = mysql_fetch_array($respub)){
							if($filpub["publica"] <> $filreg["moscol"]){
								echo "<option value=\"".$filpub["publica"]."\">".$filpub["publica"]."</option>\n";
							}
						}
					 ?>
                                                </select></td>
              <td></td>
              <td></td>
            </tr>
            
            <tr>
              <td height="12"></td>
              <td></td>
              <td ></td>
              <td ></td>
              <td ></td>
              <td></td>
            </tr>
            
            
            
            
            
            <tr>
              <td height="32"></td>
              <td colspan="2" valign="top" <?php if($filpar["codniv"] == 5){?>style="visibility:hidden" <?php }?> >L&iacute;nea: <br>
                <select name="cbo2codlinsi" class="textonegro" id="cbo2codlinsi" title="L&iacute;nea" onChange="xajax_subgrupo(this.value)">
                  <option value="<?php echo $filreg["codlin"]?>"><?php echo $filreg["nomlin"]?></option>
                  <?
					
					$qrylin= "SELECT l.codlin, ld.nomlin FROM linneg AS l 
					INNER JOIN linnegdet AS ld ON l.codlin = ld.codlin AND ld.codidi = 1 
					WHERE l.codlin <> ".$filreg["codlin"]."";
					$reslin = mysql_query($qrylin, $enlace);
					while ($fillin = mysql_fetch_array($reslin))
					echo "<option value=\"".$fillin["codlin"]."\">".$fillin["nomlin"]."</option>\n";
					mysql_free_result($reslin);
				?>
                </select></td>
              <td valign="top"   <?php if($filpar["codniv"] == 5 || $filpar["codniv"] < 2){?>style="visibility:hidden"<?php }?> >Subgrupo:<br>
			  
                <select name="cbo1codsubgrusi" class="textonegro" id="cbo1codsubgrusi" title="Subgrupo" onChange="xajax_clase(this.value)" >
				<?php if($filreg["codsubgru"]==0){?>
                  <option value="0">Elige</option>
				  <?php }else{
				  echo'<option value="'.$filreg["codsubgru"].'">'.$filreg["nomsubgru"].'</option>';
				  $qrysubgru= "SELECT sd.codsubgru, sd.nomsubgru FROM subgru AS s 
INNER JOIN subgrudet AS sd ON s.codsubgru = sd.codsubgru AND sd.codidi =1
WHERE s.codsubgru <> ".$filreg["codsubgru"]."";
					$ressubgru = mysql_query($qrysubgru, $enlace);
					while ($filsubgru = mysql_fetch_array($ressubgru))
					echo "<option value=\"".$filsubgru["codsubgru"]."\">".$filsubgru["nomsubgru"]."</option>\n";
					mysql_free_result($ressubgru);
				 } ?>
                </select>				</td>
              <td >&nbsp;</td>
              <td></td>
            </tr>
            
            
            <tr>
              <td height="32"></td>
              <td colspan="2" valign="top"    <?php if($filpar["codniv"] == 5 || $filpar["codniv"] < 3){?>style="visibility:hidden"<?php }?>>Clase:                <br>
                <select name="cbo1codclasi" class="textonegro" id="cbo1codclasi" title="Clase" onChange="xajax_subclase(this.value)" >
                  <?php if($filreg["codsubgru"]==0){?>
                  <option value="0">Elige</option>
                  <?php }else{
				  echo'<option value="'.$filreg["codcla"].'">'.$filreg["nomcla"].'</option>';
				  $qrycla= "SELECT cd.codcla, cd.nomcla FROM cla AS c 
INNER JOIN cladet AS cd ON c.codcla = cd.codcla AND cd.codidi =1
WHERE c.codcla <> ".$filreg["codcla"]."";
					$rescla = mysql_query($qrycla, $enlace);
					while ($filcla = mysql_fetch_array($rescla))
					echo "<option value=\"".$filcla["codcla"]."\">".$filcla["nomcla"]."</option>\n";
					mysql_free_result($rescla);
				 } ?>
                </select></td>
              <td valign="top"   <?php if($filpar["codniv"] == 5 || $filpar["codniv"] < 4){?>style="visibility:hidden" <?php }?>>SubClase:                  <br>
                <select name="cbo1codsubclasi" class="textonegro" id="cbo1codsubclasi" title="Sub-Clase"  >
                  <?php if($filreg["codsubgru"]==0){?>
                  <option value="0">Elige</option>
                  <?php }else{
				  echo'<option value="'.$filreg["codsubcla"].'">'.$filreg["nomsubcla"].'</option>';
				  $qrysubcla= "SELECT cd.codsubcla, cd.nomsubcla FROM subcla AS c 
INNER JOIN subcladet AS cd ON c.codsubcla = cd.codsubcla AND cd.codidi =1
WHERE c.codsubcla <> ".$filreg["codsubcla"]."";
					$ressubcla = mysql_query($qrysubcla, $enlace);
					while ($filsubcla = mysql_fetch_array($ressubcla))
					echo "<option value=\"".$filsubcla["codsubcla"]."\">".$filsubcla["nomsubcla"]."</option>\n";
					mysql_free_result($ressubcla);
				 } ?>
                </select></td>
              <td></td>
              <td></td>
              </tr>
            <tr>
              <td height="16"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              </tr>
            <tr>
              <td height="27"></td>
              <td colspan="3" valign="top">Imagen Producto (Ancho: <?php echo $filpub["proori"]; ?> px) 
                    <input name="img1fileno" type="file" id="img1fileno" onChange="nombrefoto()"  title="Imagen producto"/>
                    <input name="hid1imgprosi" type="hidden" id="hid1imgprosi" title="Nombre del alb&uacute;m" value="<?php echo $filreg["imgpro"] ?>" size="10"maxlength="100" /></td>
                  <td></td>
              <td></td>
            </tr>
            
            
            
            
            
            
            <tr>
              <td height="21"></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td></td>
              <td>&nbsp;</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              </tr>
            <tr>
              <td height="16"></td>
              <td colspan="3" valign="top">Descripci&oacute;n del producto </td>
                  <td></td>
              <td colspan="7" align="center" valign="middle" bgcolor="#006699" class="textoblanco">IMPUESTOS DEL PRODUCTO </td>
              <td></td>
              </tr>
            
            <tr>
              <td height="16"></td>
              <td colspan="3" rowspan="14" valign="top"><?php
				// Automatically calculates the editor base path based on the _samples directory.
				// This is usefull only for these samples. A real application should use something like this:
				// $oFCKeditor->BasePath = '/fckeditor/' ;	// '/fckeditor/' is the default value.
				
				$oFCKeditor = new FCKeditor('txt1desprono') ;
				$oFCKeditor->BasePath = '../fyles/fckeditor/';
				$oFCKeditor->Value = html_entity_decode( $filreg["despro"] ) ;
				$oFCKeditor->Height	= '340';
				$oFCKeditor->Create() ;
				?></td>
                  <td></td>
                  <td valign="top">Impuestos</td>
              <td colspan="3" valign="top">Pais</td>
              <td colspan="2" valign="top">Valor</td>
              <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td></td>
                </tr>
            <tr>
              <td height="5"></td>
              <td></td>
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
              <td height="21"></td>
              <td></td>
              <td valign="top"><select name="cbo1codimpno" class="textonegro" id="cbo1codimpno" title="Opci&oacute;n de producto"  >
                      <option value="0">Elige</option>
                      <?
				$qryimp= "SELECT i.codimp, i.nomimp FROM tblimpuestos i ORDER BY i.nomimp ";
				$resimp = mysql_query($qryimp, $enlace);
				while ($filimp = mysql_fetch_array($resimp))
				echo "<option value=\"".$filimp["codimp"]."\">".$filimp["nomimp"]."</option>\n";
				mysql_free_result($resimp);
				?>
                                                                      </select></td>
              <td colspan="3" valign="top"><select name="cbo1codpaino" class="textonegro" id="cbo1codpaino" title="Opci&oacute;n de producto" >
                      <option value="0">Elige</option>
                      <?
				$qrypais= "SELECT p.ci, p.cn FROM pais p ORDER BY p.cn ";
				$respais = mysql_query($qrypais, $enlace);
				while ($filpais = mysql_fetch_array($respais))
				echo "<option value='".$filpais["ci"]."'>".$filpais["cn"]."</option>\n";
				mysql_free_result($respais);
				?>
                                                                      </select></td>
              <td colspan="2" valign="top"><input name="txt1valimpno" type="text" class="textonegro" id="txt1valimpno" size="5"maxlength="4" onKeyPress="onlyDigits(event,'decOK')"/>
                %</td>
              <td align="right" valign="top"><input name="agregarno" type="button" class="textonegro" id="agregarno" onClick="agregaimpuesto()" value="Agregar"></td>
              <td></td>
              </tr>
            <tr>
              <td height="29"></td>
              <td></td>
              <td colspan="7" valign="top" id="impuestosproducto"> <script language="javascript" type="text/javascript">
				impuestosproducto();
				</script></td>
                  <td></td>
              </tr>
            <tr>
              <td height="8"></td>
              <td></td>
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
              <td height="17"></td>
              <td></td>
              <td colspan="7" align="center" valign="middle" bgcolor="#006699" class="textoblanco">PRECIOS DE PRODUCTO  </td>
              <td></td>
              </tr>
            
            
            <tr>
              <td height="13"></td>
              <td></td>
              <td valign="top">Lista de precio </td>
              <td valign="top">Precio</td>
              <td colspan="3" valign="top">Moneda defecto</td>
              <td colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td></td>
              </tr>
            
            <tr>
              <td height="21"></td>
              <td></td>
              <td valign="top" id="listadeprecio"><select name="cbo1codlispreno" class="textonegro" id="cbo1codlispreno" title="Opci&oacute;n de producto"  >
                  <option value="0">Elige</option>
                  <?
				$qrylis= "SELECT l.codlispre, l.nomlispre FROM tbllistasdeprecio l WHERE l.codlispre NOT IN (SELECT codlispre FROM tblproductosprecios WHERE codpro = $cod) ORDER BY l.nomlispre ";
				$reslis = mysql_query($qrylis, $enlace);
				while ($fillis = mysql_fetch_array($reslis))
				echo "<option value=\"".$fillis["codlispre"]."\">".$fillis["nomlispre"]."</option>\n";
				mysql_free_result($reslis);
				?>
                            </select></td>
              <td valign="top"><input name="txt1preprono" type="text" class="textonegro" id="txt1preprono" size="10"maxlength="10" onKeyPress="onlyDigits(event,'decOK')"/></td>
              <td colspan="3" valign="top"><?php echo $filmon["nommon"] ?></td>
              <td colspan="2" align="right" valign="top"><input name="agregarpreciono" type="button" class="textonegro" id="agregarpreciono" onClick="agregaprecio()" value="Agregar"></td>
              <td></td>
              </tr>
            <tr>
              <td height="13"></td>
              <td></td>
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
              <td height="30"></td>
              <td></td>
              <td colspan="7" valign="top" id="preciosproducto"><script language="javascript"> preciosproducto()</script></td>
              <td></td>
              </tr>
            <tr>
              <td height="12"></td>
              <td></td>
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
              <td height="18"></td>
              <td></td>
              <td colspan="7" align="center" valign="middle" bgcolor="#006699" class="textoblanco">REFERENCIAS DE PRODUCTO </td>
              <td></td>
              </tr>
            <tr>
              <td height="15"></td>
              <td></td>
              <td valign="top">Referencia</td>
              <td colspan="2" valign="top">Valor adicional </td>
              <td valign="top">Acci&oacute;n</td>
              <td colspan="2" valign="top">Existencias</td>
              <td valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td></td>
              </tr>
            <tr>
              <td height="21"></td>
              <td></td>
              <td valign="top"><input name="txt1refprono" type="text" class="textonegro" id="txt1refprono" size="20"maxlength="16" /></td>
              <td colspan="2" valign="top"><input name="txt1preciono" type="text" class="textonegro" id="txt1preciono" size="10"maxlength="10" onKeyPress="onlyDigits(event,'decOK')" /></td>
              <td valign="top"><select name="cbo1accionno" class="textonegro" id="cbo1accionno" title="Opci&oacute;n de producto"  >
                <option value="0">Elige</option>
                <option value="+">+</option>
                <option value="-">-</option>
              
              </select></td>
              <td colspan="2" valign="top"><input name="txt1existenciasno" type="text" class="textonegro" id="txt1existenciasno" size="10"maxlength="10" onKeyPress="onlyDigits(event,'decOK')"/></td>
              <td valign="top"><input name="agregarrefno" type="button" class="textonegro" id="agregarrefno" onClick="agregareferencia()" value="Agregar"></td>
              <td></td>
              </tr>
            <tr>
              <td height="7"></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
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
              <td colspan="3" rowspan="7" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro" <?php if($filnumidi["total"] < 2){?>style=" visibility:hidden"  <?php }?>>
                <!--DWLayoutTable-->
                
                <tr>
                  <td width="580" height="26" valign="top" ><img src="../images/idilin.png" width="24" height="24" align="absmiddle" /> Otros idiomas para el producto </td>
                      </tr>
                
                
                <tr>
                  <td height="61" valign="top" bgcolor="#FFFF99" class="marcotabla" ><?php 
						$qryregidi = "SELECT pd.codprodet, pd.nompro, i.nomidi, i.codidi FROM idipub i, prodet pd WHERE pd.codpro = '$cod' AND pd.codidi <> '1' AND pd.codidi = i.codidi";
					 	$resproidi = mysql_query($qryregidi, $enlace);
					  	$numfil = mysql_num_rows($resproidi);
						echo "<table width=100% class=textonegro>\n";
						if ($numfil>0){
							echo "<tr bgcolor=\"#FFCC00\" >\n";
							echo "<th >Editar</th><th >Idioma</th><th >Nombre</th><th>Eliminar</th>\n";
							echo "</tr>\n";
						  	while ($filregidi=mysql_fetch_array($resproidi)){
						  		echo "<tr>";
						  		echo "<td  align = center><a href=proediidi.php?cod=$cod&codidi=".$filregidi['codidi']."><img src=\"../images/publish_g.png\" alt=\"Editar idioma de producto \" border =\"0\"></a></td>\n";
						  		echo "<td>".$filregidi['nomidi']."</td>";
								echo "<td>".$filregidi['nompro']."</td>";
						  		echo "<td   align = center><font  size=\"2\" ><img src=\"../images/publish_x.png\" title=\"Eliminar idioma de producto\" onClick='eliminaidioma(".$filregidi['codidi'].")' border =\"0\" class='pointer'></td>\n";
						  		echo "</tr>";
						  	}
					  	}else{
					  		echo"<td>";
					  		echo "no se ha creado el producto en otros idiomas";
					  		echo"</td>";
					  	}
					   	echo "</table>";
					  ?></td>
                      </tr>
                
                <tr>
                  <td height="24" valign="top"><span class="texnegronegrita"><a href="procreidi.php?cod=<?php echo $cod;?>"><img src="../images/idilincre.png" width="24" height="24" border="0" align="absmiddle" /></a></span>Agregar Idioma </td>
                      </tr>
              </table></td>
              <td></td>
              <td colspan="7" valign="top" id="referenciasproducto"><script  language="javascript"> referenciasproducto() </script></td>
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
              <td></td>
            </tr>
            <tr>
              <td height="18"></td>
              <td></td>
              <td colspan="7" align="center" valign="middle" bgcolor="#006699" class="textoblanco">OPCIONES DE PRODUCTO </td>
              <td></td>
            </tr>
            <tr>
              <td height="19"></td>
              <td></td>
              <td valign="top">Opci&oacute;n</td>
              <td colspan="5" valign="top">Valor</td>
              <td valign="top"><input name="txt1codprorefno" type="hidden" id="txt1codprorefno" value="0">
                <input name="txt1referenciano" type="hidden" id="txt1referenciano" value="0"></td>
              <td></td>
            </tr>
            <tr>
              <td height="21"></td>
              <td></td>
              <td valign="top" id="cboopciones"><select name="cbo1codoptno" class="textonegro" id="cbo1codoptno" title="Opci&oacute;n de producto"  >
                <option value="0">Elige</option>
               
              </select></td>
              <td colspan="5" valign="top" id="cbovalores"><select name="cbo1codvalno" class="textonegro" id="cbo1codvalno" title="Opci&oacute;n de producto" onBlur="validaregistro()" >
                <option value="0">Elige</option>
              </select></td>
              <td valign="top"><input name="agregaropcionno" type="button" class="textonegro" id="agregaropcionno" onClick="agregaopcion()" value="Agregar"></td>
              <td></td>
            </tr>
            <tr>
              <td height="5"></td>
              <td></td>
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
              <td height="22"></td>
              <td></td>
              <td colspan="7" valign="top" id="opcionesreferencia"><!--DWLayoutEmptyCell-->&nbsp;</td>
              <td></td>
            </tr>
            
            
            
            <tr>
              <td height="42"></td>
              <td>&nbsp;</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
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