<?php

include("administractor/fyles/general/conexion.php") ;
require 'administractor/fyles/general/useronline.php';	
include("paginador/paginador.php");	
$enlace=enlace();


//incluímos la clase ajax 
require ('javascripts/xajax/xajax_core/xajax.inc.php');

//instanciamos el objeto de la clase xajax 
$xajax = new xajax();
$xajax->configure('javascript URI', 'javascripts/xajax/');

function contadorimg($codban){
	global $enlace;
	$qryupd = "UPDATE banner SET clicks = clicks +1 WHERE codban = '$codban'";
	$resupd =mysql_query ($qryupd, $enlace);
}


function registro($form_entrada){

	global $enlace;
	$respuesta = new xajaxResponse();
	//averiguo si email ya existe
	$qryexi = "SELECT codspam FROM spam WHERE emaspam ='".$form_entrada["txtema"]."'";
	$resexi = mysql_query($qryexi, $enlace);
	if(mysql_num_rows($resexi)>0){
		$respuesta->alert("La cuenta de correo ya existe en nuestra base de datos");
		$respuesta->assign("txtema","value","");
	}else{
		$qry="INSERT INTO spam VALUES('0','".$form_entrada["txtnom"]."','".$form_entrada["txtema"]."','1')";
		$res=mysql_query($qry, $enlace);
		$respuesta->alert("Su registro ha sido exitoso");
		$respuesta->assign("txtema","value","");
		$respuesta->assign("txtnom","value","");
	}
	return $respuesta;
}
//El objeto xajax tiene que procesar cualquier petición 
$xajax->registerFunction("contadorimg"); 
$xajax->registerFunction("registro"); 
$xajax->processRequest();

$fecha = date("Y-n-j H:i:s");
$link = "1";
$ip = $_SERVER['REMOTE_ADDR']; 

include("administractor/fyles/geoip.inc.php");

$sigpai = getCCfromIP($ip);
$insertSQL = sprintf("INSERT INTO vis (fecvis, linkvis, ipvis, sigpai) VALUES ('%s', '%s', '%s', '%s')",
$fecha,
$link,
$ip,
$sigpai);
$Result1 = mysql_query($insertSQL, $enlace) or die(mysql_error());

//valido si usuario en linea ha iniciado sesion
if (!isset($_SESSION['enlinea'])){ 
	$tipusuter=1;
	$codlispre = 1;
	$session = session_id();
}else{
	$qryter = "SELECT tc.nomter, tc.codtipusuter, utc.codusucli, tc.codlispre FROM tercli tc, usutercli utc WHERE tc.codter = '".$_SESSION['enlinea']."' AND tc.codter = utc.codter";
	$rester = mysql_query($qryter, $enlace);
	$filter = mysql_fetch_assoc($rester);
	$tipusuter=$filter["codtipusuter"];
	$codlispre = $filter["codlispre"];
	$session = $_SESSION["enlinea"];
}

//valido si selecciona idioma
if(!isset($_GET['idi'])){
	$idioma=1;
}else{
	$idioma = $_GET['idi'];

	//valido introduccion de idioma valido
	if ($idioma < 1 || $idioma > 2){
		$idioma = 1;
	}
}


//consulto banner o imagenn de seccion
$qryimg = "SELECT pin.*,ps.animspeed, ps.slices,ptr.nomtrascin FROM pagsiteint as pin 
INNER JOIN pagsite as ps ON ps.codpag=pin.codpag
INNER JOIN pagsitetransiciones as ptr ON ptr.codtrasc=ps.codtrasc
WHERE pin.codidi = '$idioma' AND pin.codpag = '$link'";
$resimg = mysql_query($qryimg, $enlace);
$filimg = mysql_fetch_assoc($resimg);


$qryinfemp = "SELECT telemp, diremp, faxemp, telofiemp, imgfonreq, imgfon, imgfonx, imgfony, colfon, fondofijo, url  FROM licusu";
$resinfemp = mysql_query($qryinfemp, $enlace);
$filinfemp = mysql_fetch_assoc($resinfemp);

//averiguo si existe imagen de seccion diaria
$qryimgdiaria = "SELECT *  FROM pagsiteimgdiaria WHERE codidi = '$idioma' AND codpag = '$link' AND coddiasemana = ".date("N")."";
$resimgdiaria = mysql_query($qryimgdiaria, $enlace);


//averiguo si existe imagen de FONDO diaria
$qryimgfondo = "SELECT *  FROM pagsitefondodiario WHERE codidi = '$idioma' AND coddiasemana = ".date("N")."";
$resimgfondo = mysql_query($qryimgfondo, $enlace);

//valido si selecciona idioma
if(!isset($_GET['idi'])){
	$idioma=1;
}else{
	$idioma = $_GET['idi'];
	//valido introduccion de idioma valido
	if ($idioma < 1 || $idioma > 2){
		$idioma = 1;
	}
}


	
$tipoinmueble = $_POST["cbo1codinmueblesi"];
$pais = $_POST["cbo1cino"];
$departamento = $_POST["cbo1coddepsi"];
$ciudad = $_POST["cbo1codciusi"];
$Zona = $_POST["cbo1zonasi"];
$barrio = $_POST["txt2barriosi"];
$numerohabt = $_POST["txt2numerohabsi"];
$valorini = $_POST["txt2valorinisi2"];
$valorfin = $_POST["txt2valorfinsi2"];
$tiporesponsable = $_POST["cbo1tiporesponsablesi"];

$maxRows_registros = 3;

	$query_registros = "SELECT
      inmuebles.codinmueble
    , inmuebles.nominmueble
    , inmuebles.areainmueble
    , inmuebles.numerohab
	, inmuebles.imginmueble
	, inmuebletipo.nomtipinmueble
	, inmuebles.tiporesponsable
    , deppro.nomdep
    , ciudad.nomciu
	, pais.ci
    , barrio.nombar
    , zona.nomzona
	,inmuebles.pub
	,inmuebles.pubini
	,inmuebles.valor
	,u.nomusu,
	inmuebles.clicks,
	inmuebles.paraq
	,inmuebles.codigo
FROM
    inmuebles 
    LEFT JOIN barrio
     ON (inmuebles.codbar = barrio.codbar)
    LEFT JOIN ciudad 
        ON (inmuebles.codciu = ciudad.codciu)
    LEFT JOIN deppro 
        ON (ciudad.coddep = deppro.coddep)
	LEFT JOIN pais 
        ON (deppro.ci = pais.ci)	
    LEFT JOIN inmuebletipo 
        ON (inmuebles.codtipinmueble = inmuebletipo.codtipinmueble) 
    LEFT JOIN zona 
        ON (inmuebles.codzona = zona.codzona)
    LEFT JOIN usuadm AS u ON inmuebles.codusuadm = u.codusuadm
     WHERE inmuebles.codinmueble > 0 AND inmuebles.pubini='Si' ";


if($tipoinmueble<>0){
$query_registros .= " AND inmuebles.codtipinmueble= $tipoinmueble ";
}


if ($pais<>0){
$query_registros .= " AND pais.ci= $pais ";
}

if ($departamento<>0){
$query_registros .= " AND deppro.coddep= $departamento ";
}

if($ciudad<>0){
$query_registros .= " AND inmuebles.codciu= $ciudad ";
}

if($Zona<>0){
$query_registros .= " AND inmuebles.codzona= $Zona ";
}

if($barrio<>0){
$query_registros .= " AND inmuebles.codbar= $barrio ";
}


if($tiporesponsable<>0){
$query_registros .= " AND inmuebles.tiporesponsable= $tiporesponsable ";

}

if($numerohabt<>0){
$query_registros .= " AND inmuebles.numerohab= $numerohabt ";
}

if($valorini <> "" && $valorfin <> ""){
 $query_registros .= " AND (inmuebles.valor) BETWEEN '$valorini' AND '$valorfin'";

}
 $query_registros .= "ORDER BY deppro.nomdep, zona.nomzona ";

include("paginador/paginadorinferior.php") ;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">


<html lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>ESTABLECER INMOBILIARIA S.A.S - Venta de finca raiz colombia</title>
<?php 
//En el <head> indicamos al objeto xajax se encargue de generar el javascript necesario 
$xajax->printJavascript(); 

//include("administractor/fyles/general/metatags.php") ;
include("script/base/menu.php") ;
?>

<script type="text/javascript" src="script/publicaciones/js/mootools.js"></script>
<script type="text/javascript" src="script/publicaciones/js/efxMooSer.js"></script>

<link rel="stylesheet" href="javascripts/jquery.nyroModal/styles/nyroModal.css" type="text/css" media="screen" />
<script type="text/javascript" src="javascripts/jquery.nyroModal/js/jquery.min.js"></script>
<script type="text/javascript" src="javascripts/jquery.nyroModal/js/jquery.nyroModal.custom.js"></script>
<link href='http://fonts.googleapis.com/css?family=Boogaloo|Dosis:400,500|Chela+One|Racing+Sans+One|Simonetta' rel='stylesheet' type='text/css'>


<script type="text/javascript" src="javascripts/menu/scripts.js"></script>
<script type="text/javascript" src="javascripts/menu/jquery.effects.core.js"></script>
<link rel="stylesheet" href="javascripts/menu/style.css" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=PT+Sans+Caption| Anton' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Squada+One' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="javascripts/menuitems.js"></script>

<script type="text/javascript">
function mostrar(nombreCapa){ 
	if(document.getElementById(nombreCapa).style.visibility=="visible"){
	document.getElementById(nombreCapa).style.display="none"; 
	document.getElementById(nombreCapa).style.visibility="hidden"; 
	}else{
	document.getElementById(nombreCapa).style.display="block"; 
	document.getElementById(nombreCapa).style.visibility="visible"; 
	}
} 


function contadorimg(codban){
	xajax_contadorimg(codban);
}

function verbannermodal(){

$(function () {
  $('.nyroModal').nyroModal().nmCall();
});
}


function registro(){
	
	if(document.form1.txtnom.valu=="" || document.form1.txtema.value==""){
		alert("por favor ingrese su nombre y email");
		exit();
		return false;
	}
	var b=/^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/      
	//devuelve verdadero si validacion OK, y falso en caso contrario
	if (b.test(document.form1.txtema.value)==false)
	{
	alert("El e-mail tiene un formato invalido")
	exit();
	return false;
	}
	
	xajax_registro(xajax.getFormValues("form1"));

}

 
// <![CDATA[
function udm_(a){var b="comScore=",c=document,d=c.cookie,e="",f="indexOf",g="substring",h="length",i=2048,j,k="&ns_",l="&",m,n,o,p,q=window,r=q.encodeURIComponent||escape;if(d[f](b)+1)for(o=0,n=d.split(";"),p=n[h];o<p;o++)m=n[o][f](b),m+1&&(e=l+unescape(n[o][g](m+b[h])));a+=k+"_t="+ +(new Date)+k+"c="+(c.characterSet||c.defaultCharset||"")+"&c8="+r(c.title)+e+"&c7="+r(c.URL)+"&c9="+r(c.referrer),a[h]>i&&a[f](l)>0&&(j=a[g](0,i-8).lastIndexOf(l),a=(a[g](0,j)+k+"cut="+r(a[g](j+1)))[g](0,i)),c.images?(m=new Image,q.ns_p||(ns_p=m),m.src=a):c.write("<","p","><",'img src="',a,'" height="1" width="1" alt="*"',"><","/p",">")}
udm_('http'+(document.location.href.charAt(4)=='s'?'s://sb':'://b')+'.scorecardresearch.com/b?c1=2&c2=6906409&ns_site=welcome-argentina&name=home.index_i');
// ]]>

</script>

<LINK href="javascripts/css_home_66.css" 
rel="stylesheet" type="text/css"> 
<SCRIPT language="JavaScript" src="javascripts/js_home-i_55.js" type="text/JavaScript"></SCRIPT>

<SCRIPT language="JavaScript" src="javascripts/feedback1.js" type="text/JavaScript"></SCRIPT>
 
<SCRIPT language="JavaScript" src="javascripts/feedback2.js" type="text/JavaScript"></SCRIPT>

<SCRIPT type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-127448-2']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</SCRIPT>
 
<SCRIPT>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-127448-12', 'welcomeargentina.com');
  ga('send', 'pageview');
</SCRIPT>
 
<SCRIPT src="javascripts/plusone.js" type="text/javascript"></SCRIPT>
 


<script type="text/javascript" src="script/base/js/validaform.js"></script>
<script type="text/javascript" src="videos/js/flashembed.min.js"></script>
<link rel="stylesheet" type="text/css" href="videos/css/common.css">

  
<script type="text/javascript" src="javascripts/swfobject.js"></script>
<script type="text/javascript" src="script/inicial/js/favoritos.js"></script>

<style type="text/css">
img, div { behavior: url(javascripts/iepngfix.htc) }

A:LINK {text-decoration : none; color : #663399} 
A:VISITED {text-decoration : none; color : #663399} 
A:HOVER {text-decoration : none; color : #663399;} 
A:ACTIVE {text-decoration : none; color : #663399} 

A.clase1:LINK {text-decoration : none; color:#FFFFFF} 
A.clase1:VISITED {text-decoration : none; color : #FFFFFF} 
A.clase1:HOVER {text-decoration : none; color : #FFCC00;} 
A.clase1:ACTIVE {text-decoration : none; color : #FFFFFF} 

#horiz-menu {
	width: 924px;
	z-index: 99999;
	height: 52px;
	position: relative;
	background-repeat:no-repeat;
	left:550px; top:3px;
	
}

.contactenos-form-textfield {
    background: url("images/contactenos-form-textfield.png") no-repeat scroll 0 0 transparent;
    border: 0 none;
    font-family: 'Dosis', sans-serif;
	font-size: 14px;
	font-style: normal;
	font-weight: normal;
	color:#666666;
    height: 28px;
    width: 100px;
	z-index:9999999;
}

.contactenos-form-select2 {
    background: url("images/contactenos-form-textfield.png") no-repeat scroll 0 0 transparent;
    border: 0 none;
    color: #666666;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 15px;
    height: 32px;
    padding: 2px 2px 2px 10px;
    width: 205px;
}

.contactenos-form-textfield2 {
    background: url("images/contactenos-form-textfield.png") no-repeat scroll 0 0 transparent;
    border: 0 none;
    color: #666666;
    font-size: 15px;
    height: 28px;
    padding: 2px 10px;
    width: 100px;
}

.textogrisb{
font:Arial, Helvetica, sans-serif; font-size:16px; color:#666666;

}

.textogrisb1{
font:Arial, Helvetica, sans-serif; font-size:19px; color:#8A4A37; font-weight:700;

}

.textogrisb2{
font:Arial, Helvetica, sans-serif; font-size:13px; color:#8A4A37; font-weight:700;

}

.textoprecio{
font:Arial, Helvetica, sans-serif; font-size:18px; color:#111111; font-weight:700;

}


.textonegrob{
font:Arial, Helvetica, sans-serif; font-size:19px; color:#000000;

}


#bljaIMGte{
position:relative left:-35px;
}

#bljaIMGte .bljaIMGtex 
{ 
width:131px;position:absolute;top:2px;left:54px;
}
.tituloinm { font-family: 'Metrophobic', Arial, serif; font-weight: 400; font-size:13px; color:#222222; }
body {
margin-top: 0mm;
<?php if($filinfemp["imgfonreq"]=="Si"){
if(mysql_num_rows($resimgfondo)>0){
$filimgfondo = mysql_fetch_assoc($resimgfondo);
?>
background-image: url(imgfondodiaria/<?php echo $filimgfondo["imgfondo"];?>);
<?php 
}else{
?>
background-image: url(images/<?php echo $filinfemp["imgfon"];?>);
<?php } ?>
background-position:center;
background-position:top;
<?php 
if($filinfemp["fondofijo"]=="Si"){
?>
background-attachment:fixed;
<?php 
}
if($filinfemp["imgfonx"]=="Si" && $filinfemp["imgfony"]=="No"){
?>
background-repeat:repeat-x;
<?php }	?>
<?php } ?>	
background-color: #<?php echo $filinfemp["colfon"];?>;
}</style>


<link href="css/cliente.css" rel="stylesheet" type="text/css" />
</head>
<body>
<table width="100%" height="177" border="0"  align="center" cellpadding="0" cellspacing="0">


  <tr>
    <td height="84" valign="top">
	<form method="post" name="form1" action="" id="form1" enctype="multipart/form-data" >
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="textonegro" >
        <!--DWLayoutTable--> 
		
		<tr>
		  <td  colspan="3" valign="middle"><div style="position:relative;top:10px"><a href="index.php" border="0"><img src="images/empresa.png" border="0"/></a></div>
		  <div  id="horiz-menu" align="left"   class="nav" style="width:954px; background-image:url(images/fondomenu.png); background-repeat:no-repeat; height:52px; position:relative; padding-left:40px">
		<?php
			 menu($idioma);
?>
		
		</div></td>
		  </tr>
		
		     <?php 
				   //si existe imagen diaria la pongo si no comparo contra la seccion
				   if(mysql_num_rows($resimgdiaria)>0){ //if 1
				   $filimgdiaria = mysql_fetch_assoc($resimgdiaria);
				   
				   if($filimgdiaria["tipimg"]<>3){
				   ?>
		            <tr>
		              <td height="78" colspan="3" valign="top"><?php 
			//averiguo extension de imagen
			// $ext = strrchr($filban["imgindex"],'.');
			//$ext = strtolower($ext); if ($ext == ".swf"){
			$datos = GetImageSize('imgsecciondiaria/'.$filimgdiaria["imgpag"].''); 
			$x = $datos[0]; 
			$y = $datos[1]; 
			if($filimgdiaria["tipimg"]==1){
			?>
		                <script type="text/javascript">
			var params = {menu: "false", wmode: "transparent", loop: "false" };
			var attributes = {};
			swfobject.embedSWF("imgsecciondiaria/<?php echo $filimgdiaria["imgpag"] ?>", "imgsecciondiaria", "<?php echo $x?>", "<?php echo $y?>", "9.0.0", "javascripts/expressInstall.swf","", params, attributes);
							</script>
		                <div  id="imgsecciondiaria" align="center"><a href="http://www.adobe.com/go/getflashplayer">requiere flash<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player"/><br></a>
		                  <?php } 
						else
						{  
							
							if ($filimgdiaria["manvin"]=="Si"){ 
							echo "<a href=http://".$filimgdiaria["url"]."  target=".$filimgdiaria["abre"]."><img src=\"imgsecciondiaria/".$filimgdiaria["imgpag"]."\" border=0 width=".$x." height=".$y." ></a>";
							}else{
							 echo "<img src=\"imgsecciondiaria/".$filimgdiaria["imgpag"]."\"  width=".$x." height=".$y." >"; 
							} 
						}

						?>
                      </div></td>
          </tr>
		  
		  <?php
		   } else {
		   
		   //es slider
		  ?>
		  <tr>
		              <td height="18" colspan="3" valign="top" bgcolor="#FFFFFF" >
	<link rel="stylesheet" href="javascripts/slider/styles/nivo-slider.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="javascripts/slider/styles/style.css" type="text/css" media="screen" />

	<script src="javascripts/slider/scripts/jquery.nivo.slider.pack.js" type="text/javascript"></script>
	
	<script type="text/javascript">

	$(window).load(function() {
		
		setTimeout(function(){
			$('#slider2').nivoSlider({ pauseTime:5000, pauseOnHover:false,effect:'<?php echo $filimg["nomtrascin"]?>',slices:'<?php echo $filimg["slices"]?>',animSpeed:'<?php echo $filimg["animspeed"]?>'});
		}, 1000);
		
	/*slices: maneja el recorrido de la transicion de la imagen*/	
	/*animSpeed: velocidad en que se muestra la animacion */	
		
		
	});
	</script>


	<div id="wrapper" >
      <div id="slider2" class="nivoSlider" align="center" >
	  <?php
	  $qryslider= "SELECT f.* FROM pagsiteimgdiariaslider AS f WHERE f.codpagimg = '".$filimgdiaria["codpagimg"]."' ORDER BY orden ASC"; 
	  $resslider = mysql_query($qryslider, $enlace);
	  while($filslider=mysql_fetch_assoc($resslider)){
	  			
				$datos = GetImageSize('imgsecciondiariaslider/'.$filslider["imgslider"].''); 
				$x = $datos[0]; 
				$y = $datos[1]; 
	
				if ($filslider["manvin"]=="Si"){ 
				echo "<a href=http://".$filslider["url"]."  target=".$filslider["abre"]." alt='' title='".$filslider["intslider"]."'><img src=\"imgsecciondiariaslider/".$filslider["imgslider"]."\" border=0 width='100%' height=".$y." ></a>";
				}else{
				 echo "<a><img src='imgsecciondiariaslider/".$filslider["imgslider"]."' id='fondo' alt='' title='".$filslider["intslider"]."' ></a>"; 
				} 
	  };

	  ?>
    </div>
</div>	</td> 
          </tr>
								
		  <?php
		  }
		  ?>
		            <?php
				   }else{
				   
				   if($filimg["tipimg"]<>3) { //if2 
				   
				   		if($filimg["tipimg"]<>4){ //if3?> <tr>
		              <td height="78" colspan="3" valign="top"><?php 
	//averiguo extension de imagen
	// $ext = strrchr($filban["imgindex"],'.');
	//$ext = strtolower($ext); if ($ext == ".swf"){
	$datos = GetImageSize('imgseccion/'.$filimg["imgpag"].''); 
	$x = $datos[0]; 
	$y = $datos[1]; 
	if($filimg["tipimg"]==1){
	?>
		                <script type="text/javascript">
	var params = {menu: "false", wmode: "transparent", loop: "false" };
	var attributes = {};
	swfobject.embedSWF("imgseccion/<?php echo $filimg["imgpag"] ?>", "imgseccion", "<?php echo $x?>", "<?php echo $y?>", "9.0.0", "javascripts/expressInstall.swf","", params, attributes);
	                    </script>
		                <div  id="imgseccion" align="center"><a href="http://www.adobe.com/go/getflashplayer">requiere flash<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player"/><br></a><?php } else{  if ($filimg["manvin"]==1){ echo "<a href=http://".$filimg["url"]."  target=".$filimg["abre"]."><img src=\"imgseccion/".$filimg["imgpag"]."\" border=0 width=".$x." height=".$y." ></a>";}else{ echo "<img src=\"imgseccion/".$filimg["imgpag"]."\"  width=".$x." height=".$y." >"; } }?>
</div></td>
                        </tr>
		            <?php
								}else{
								
								?>
								<tr>
		            <div style="954px">  <td height="38" colspan="3" valign="top"   bgcolor="#FFFFFF" style="position:relative; top:-21px" align="center" width="100%">
	<link rel="stylesheet" href="javascripts/slider/styles/nivo-slider.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="javascripts/slider/styles/style.css" type="text/css" media="screen" />

	<script src="javascripts/slider/scripts/jquery.nivo.slider.pack.js" type="text/javascript"></script>
	
	<script type="text/javascript">

	$(window).load(function() {
		
		setTimeout(function(){
			$('#slider2').nivoSlider({ pauseTime:5000, pauseOnHover:false,effect:'<?php echo $filimg["nomtrascin"]?>',slices:'<?php echo $filimg["slices"]?>',animSpeed:'<?php echo $filimg["animspeed"]?>' });
		}, 1000);
		
	});
	</script>


	<div id="wrapper1">
      <div id="slider2" class="nivoSlider"   >
	  <?php
	  $qryslider= "SELECT f.* FROM pagsiteintslider AS f WHERE f.codpag = '$link' ORDER BY orden ASC"; 
	  $resslider = mysql_query($qryslider, $enlace);
	  while($filslider=mysql_fetch_assoc($resslider)){
	  			
				$datos = GetImageSize('imgseccionslider/'.$filslider["imgslider"].''); 
				$x = $datos[0]; 
				$y = $datos[1]; 
	
				if ($filslider["manvin"]=="Si"){ 
				echo "<a href=http://".$filslider["url"]."  target=".$filslider["abre"]." alt='' title='".$filslider["intslider"]."'><img src=\"imgseccionslider/".$filslider["imgslider"]."\" border=0 width='100%' height=".$y." ></a>";
				}else{
				 echo "<a ><img src='imgseccionslider/".$filslider["imgslider"]."' id='fondo' alt='' title='".$filslider["intslider"]."' width='100%' ></a>"; 
				} 
	  };

	  ?>
    </div>
</div>	</td></div> 
                        </tr>
								<?php
								
								}//fin si3
					
						 } //fin si 2
					  
					  }//fin si 1
					  
					  ?>
					   
					  <tr>
		      <td width="17" height="22"  ></td>
			  <div style="width:954px; height:100%" align="center">
			  
              <td width="100%" valign="top" bgcolor="" align="center"     ><br>               
			  
			  <div style="background-image:url(images/fondobusqueda.png); background-repeat:no-repeat; width:954px; height:248px" align="center">
			  
			  <div style="position:relative; padding-top:60px; padding-left:30px">
			  
			  <table cellpadding="0" cellspacing="0" align="left" width="954" >
			    <!--DWLayoutTable-->
			  
			  <tr><td width="247" class="textogrisb">Ubicacion</td>
			  <td width="336" class="textogrisb">Rango de Precios</td>
			  <td width="333">&nbsp;</td>
			  </tr>
			  <tr><td class="textogrisb">  <Select    name="cbo1zonasi"  class="contactenos-form-select2" id="cbo1zonasi" title= "Zona">
                <option value="0" >Elige</option>
                <?
					
					$qryzona= "SELECT zn.codzona, zn.nomzona FROM zona AS zn ORDER BY zn.nomzona ";
					$reszona = mysql_query($qryzona, $enlace);
					while ($filzona = mysql_fetch_array($reszona))
					echo "<option value=\"".$filzona["codzona"]."\">".$filzona["nomzona"]."</option>\n";
					mysql_free_result($reszona);
				?>
              </select></td><td class="textogrisb">Entre $
			    <input name="txt2nombresi" type="text" class="contactenos-form-textfield2" id="txt2nombresi" maxlength="40">
                                y
                                  $
                              <input name="txt2nombresi2" type="text" class="contactenos-form-textfield2" id="txt2nombresi2" maxlength="40"></td>
							  
							  <td><button class="textonegro"   name="filtrar" type="submit" value="filtrar" style="margin: 0px; background-color: transparent; border: none;cursor: pointer"></button></td>
	                  </tr>
			  <tr>
			    <td height="18" valign="top" class="textogrisb"> Tipo de Inmueble</td>
			    <td valign="top" class="textogrisb">Zona</td>
				<td rowspan="3" align="center"  valign="bottom" style="padding-right:20px;background-image:url(images/sigue.png); background-repeat:no-repeat; background-position:top; height:60px"><img src="images/facebook.png" width="50" border="0" />&nbsp;<img src="images/twetter.png" width="50" border="0" />&nbsp;<img src="images/youtube.png" width="50" border="0" />&nbsp;<img src="images/google.png" width="50" border="0" /></td>
			    </tr>
			  <tr>
			    <td height="32" valign="top" class="textogrisb"><select  name="cbo1codinmueblesi" id="cbo1codinmueblesi" class="contactenos-form-select2">
			      <option value="0">Elige</option>
			      <?
					$qryinmueble= "SELECT inm.* FROM inmuebletipo AS inm ORDER BY inm.nomtipinmueble ";
					$resinmueble = mysql_query($qryinmueble, $enlace);
					while ($filinmueble = mysql_fetch_array($resinmueble ))
					echo "<option value=\"".$filinmueble["codtipinmueble"]."\">".$filinmueble["nomtipinmueble"]."</option>\n";
					mysql_free_result($resinmueble );
				?>
			      </select></td>
			      <td valign="top" class="textogrisb"><select name="cbo2codareasi" class="contactenos-form-select2" id="cbo2codareasi" title="area de contacto">
                                <option value="0">Elige</option>
                                <?
	$qryarea = "SELECT ac.codarea, acd.nomarea  FROM areacon ac, areacondet acd  WHERE ac.estado = 'Activa' AND ac.codarea = acd.codarea AND acd.codidi = $idioma ORDER BY acd.nomarea";
	$resarea = mysql_query($qryarea, $enlace);
	while ($filarea = mysql_fetch_array($resarea))
		echo "<option value=\"".$filarea["codarea"]."\">".$filarea["nomarea"]."</option>\n";
		mysql_free_result($resarea);
	?>
                        </select></td>
			      </tr>
			  
			  
			  <tr>
			    <td height="28"></td>
			    <td></td>
			    </tr>
			  </table>
			  </div>
			  
			  </div>
			  
			   <?php echo html_entity_decode( $filimg["intpag"] );
			  				  
			  ?>			  		       <br>
			  
			 <div align="right" style="padding-right:240px"> <img src="images/publica2.png" border="0" title="Publicar"/></div>
			  
			  
			  <?php 	
	
	 	if ($totalRows_registros > 0){
								echo" <table  border='0' cellpadding='0' cellspacing='0'  >";
								echo "<tr><div style='padding-left:190px' align='left'   class='textogrisb'><img src='images/detacado.png' border='0' /></div><br>";
								echo "<td colspan = '5' align='right' style='padding-right:10px'  >";
								$prev_registros = "&laquo; Anterior";
				$next_registros = " Siguiente &raquo;";
				$separator = " | ";
				$max_links = 10;
				$pages_navigation_registros = paginador($pageNum_registros,$totalPages_registros,$prev_registros,$next_registros,$separator,$max_links,true); 
				
				print $pages_navigation_registros[0]; 
				?> <?php print $pages_navigation_registros[1];  print $pages_navigation_registros[2];
								echo "</td>";
								echo "</tr>";
								
								
								echo" <tr><td valign='top' width='226'>";
								echo "<table align='center' class='textogrispub ; servicios2' width='226' border='0' cellpadding='0' cellspacing='0' >";
						
				 $num= $startRow_registros ;
				 $numero = 0 ; 
				   do { 
		   
			  if($numero == 3)
				{
				$numero = 0;
 				echo"<tr>" ;
				echo"<td height=\"15\"></td>";
				echo"</tr>" ;
				}
			  		 
			  
				echo" <td align='center' width='226'  valign='top' class='pointer' style='padding:5px' title='Leer m&aacute;s ...' 
				onClick=window.open(index.htm) >";
				echo" <table width='226'  cellpadding='0' cellspacing='0' border='0' style='border-color:#CCCCCC' >";
				echo "<tr><td align='center' class='tituloinm'><strong>".$row_registros["nominmueble"]."</strong><div align='right'>Visitas:&nbsp;".$row_registros["clicks"]."";
echo "</div></td></tr>";
				echo" <!--DWLayoutTable-->";
				echo "<br>";
				echo" <tr>";
				
				if($row_registros["imginmueble"]<>"logocli.jpg"){
				echo" <td  width = '180' valign='top'  align='center' ><a href='script/inmuebles/inmuebles.php?codinm=".$row_registros["codinmueble"]."' target='_blank'><img src='administractor/inmuebles/".$row_registros["imginmueble"]."' title='Ampliar informaci&oacute;n'   border='0' width='240'  ></a></td>";
				}else{
					echo" <td  valign='top'  align='center'  ><img src='administractor/inmuebles/logocli.jpg' title='Ampliar informaci&oacute;n' width = '180'  border='0' ></td>";
				}
				echo" <td height='60' valign='top' style='padding-right:14px;padding-left:10px'   >";
				echo "</td>";
				echo "</tr>";
				echo "<tr><td height='5'></td></tr>";
				
				echo "<tr><td class='tituloinm'><div align='right'><span class='textogrisb2'>Codigo:</span> <strong>".$row_registros["codigo"]."</strong></div></td></tr>";
				echo "<tr><td height='div'></td></tr>";
				
				echo "<tr><td class='tituloinm' align='left'><strong>Para:</strong>&nbsp; ".$row_registros["paraq"]."</td></tr>";

echo "<tr><td class='tituloinm'><div align='left'><strong>Tipo:</strong>&nbsp;&nbsp;".$row_registros["nomtipinmueble"]."</div></td></tr>";


echo "<tr><td class='tituloinm'><div align='left'><strong>Ubicacion:</strong> ".$row_registros["nomciu"]."</div></td></tr>";

echo "<tr><td class='tituloinm'><div align='left'><span class='textogrisb2'><strong>PRECIO:</strong></span>&nbsp;<span class='textoprecio'>$&nbsp;".number_format ( $row_registros["valor"] , 0 , ',' , '.' )."</span>";


echo "</div></td></tr>";

echo "<tr><td class='tituloinm'></td></tr>";



				echo "</table></td>";    

			
		$numero++; 
		$num++;
		} while ($row_registros = mysql_fetch_assoc($consulta));

		echo" </table>";
		echo" </td>";
		echo" <td></td>";
		echo" </tr></table><br><br>";
						
		  } 
	
			?>
			  
			  
			  </td></div>
	      <td width="17"  ></td>
		  </tr>
					  <tr>
					    <td height="14"  ></td>
					    <td     ></td>
					    <td  ></td>
	      </tr>
					  
					  <tr>
					    <td height="64"  ></td>
					    <td valign="top"  ><!--DWLayoutEmptyCell-->&nbsp;</td>
					    <td  ></td>
	      </tr>
					  
					  
					  
					  
					  
					  
					  
				


 <tr>
		      <td height="22" colspan="3" valign="top"  >
			  
<DIV class="tabs twitter" style="background-image:url(images/twitter_bg.png); background-repeat:no-repeat;"><A class="twitr" onClick="_gaq.push(['_trackEvent', 'Twitter', 'Compartir', '/index_i.html']);" 
href="http://www.establecerinmobiliaria.com.co/includes/redireccion_share.html?servicio=twtr&amp;uri=/index_i.html&amp;tit=Welcome%20Argentina%20hotel%20&amp;%20travel%20guide%3A%202014%20Winter%20vacations%20in%20Argentina" 
target="_blank"><IMG width="76" height="16" title="Share on Twitter" alt="Share on Twitter" 
src="images/twitter_compartir_i.gif"></A><A 
onclick="_gaq.push(['_trackEvent', 'Twitter', 'Seguinos', '/index_i.html']);" 
href="http://twitter.com/EstablecerInmob" target="_blank"><IMG width="76" height="16" 
title="Establecer Inmobiliaria Twitter" alt="Folablecer Inmobiliarialow Welcome Argentina on Twitter" 
src="images/twitter_seguinos_i.gif"></A></DIV>
<DIV class="tabs facebook"  style="background-image:url(images/facebook_bg.png); background-repeat:no-repeat;"><A class="facbk" onClick="_gaq.push(['_trackEvent', 'Facebook', 'Compartir', '/index_i.html']);" 
href="http://www.establecerinmobiliaria.com.co/includes/redireccion_share.html?servicio=fb&amp;uri=/index_i.html&amp;tit=Welcome%20Argentina%20hotel%20&amp;%20travel%20guide%3A%202014%20Winter%20vacations%20in%20Argentina" 
target="_blank"><IMG width="76" height="16" title="Share on Facebook" alt="Share on Facebook" 
src="images/facebook_compartir_i.gif"></A><A 
onclick="_gaq.push(['_trackEvent', 'Facebook', 'Me gusta', '/index_i.html']);" 
href="http://www.facebook.com/pages/Establecer-Inmobiliaria/394724840665230?fref=ts" target="_blank"><IMG width="76" 
height="16" title="Welcome Argentina on facebook: Like" alt="Welcome Argentina on facebook: Like" 
src="images/facebook_megusta_i.gif"></A></DIV>
<DIV class="tabs youtube"  style="background-image:url(images/youtube_bg.png); background-repeat:no-repeat;"><A onClick="_gaq.push(['_trackEvent', 'Youtube', 'Suscribite', '/index_i.html']);" 
href="http://www.youtube.com/subscription_center?add_user=welcomeargentina" 
target="_blank"><IMG width="76" height="16" title="Subscribe to our YouTube channel" 
alt="Subscribe to our YouTube channel" src="images/youtube_suscribite_i.gif"></A><A 
onclick="_gaq.push(['_trackEvent', 'Youtube', 'Ver videos', '/index_i.html']);" 
href="http://www.youtube.com/user/welcomeargentina" target="_blank"><IMG width="76" 
height="16" title="Welcome Argentina Videos on YouTube" alt="Welcome Argentina Videos on YouTube" 
src="images/youtube_vervideos_i.gif"></A></DIV>
<DIV class="tabs googleplus_i"  style="background-image:url(images/googleplus_bg.png); background-repeat:no-repeat;">
<DIV class="plusone"><g:plusone size="medium" 
annotation="none"></g:plusone></DIV>
<DIV class="addcircle"><A style="text-decoration: none;" href="https://plus.google.com/+welcomeargentina" 
target="_blank" rel="publisher"><IMG title="Follow us in Google+" style="border: 0px currentColor; width: 32px; height: 20px;" 
alt="Follow us in Google+" src="images/gpluscircle.png"></A></DIV></DIV>
<SCRIPT language="JavaScript1.3" src="javascripts/ct.js"></SCRIPT>
			  
			  <img src="images/piepagina.png" width="100%" height="20" /></td>
		  </tr>					 
      </table>
	  </form>
	
    </td>
  </tr>
</table>

</body>
</html>

