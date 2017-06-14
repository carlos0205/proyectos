<?php 
include("administractor/fyles/general/conexion.php") ;
include("administractor/fyles/general/sesion.php");
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

function ciudades($dep){
    global $enlace;
    $respuesta = new xajaxResponse();
    
    $qrylis ="SELECT c.codciu, c.nomciu FROM ciudad AS c
WHERE c.coddep = $dep ";
    $reslis = mysql_query($qrylis, $enlace);
    $lista = "<select name='cbo1codciusi' id='cbo1codciusi'  class='contactenos-form-select2' onChange='xajax_barrios(this.value)' title='ciudades'>/n";
    $lista.= "<option value='0'>Elige</option>";
    while ($fillis = mysql_fetch_array($reslis)){
        $lista.= "<option value='".$fillis["codciu"]."'>".$fillis["nomciu"]."</option>/n";
    }
    $lista.= "</select>";
    
    $respuesta->assign("ciudades","innerHTML","<span class='textogrisb'>Ciudad</span><br>".$lista); 
    
    return $respuesta;
}

function barrios($ciu){
    global $enlace;
    $respuesta = new xajaxResponse();
    
    $qrylis ="SELECT b.codbar, b.nombar FROM barrio AS b
WHERE b.codciu = $ciu ";
    $reslis = mysql_query($qrylis, $enlace);
    $lista = "<select name='cbo1codbarsi' id='cbo1codbarsi'  class='textonegro'  title='barrios'>/n";
    $lista.= "<option value='0'>Elige</option>";
    while ($fillis = mysql_fetch_array($reslis)){
        $lista.= "<option value='".$fillis["codbar"]."'>".$fillis["nombar"]."</option>/n";
    }
    $lista.= "</select>";
    
    $respuesta->assign("barrios","innerHTML","Barrio<br>".$lista); 
    
    return $respuesta;
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
$xajax->registerFunction("ciudades");
$xajax->registerFunction("barrios");
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


    /*$tipoinmueble = $_POST["cbo1codinmueblesi"];
$pais = $_POST["cbo1cino"];
$departamento = $_POST["cbo1coddepsi"];
$ciudad = $_POST["cbo1codciusi"];
$Zona = $_POST["cbo1zonasi"];
$barrio = $_POST["txt2barriosi"];
$numerohabt = $_POST["txt2numerohabsi"];
$valorini = $_POST["txt2valorinisi2"];
$valorfin = $_POST["txt2valorfinsi2"];
$tiporesponsable = $_POST["cbo1tiporesponsablesi"];*/


if (isset($_POST['buscar']))
{
$paraq = $_POST["cbo2codparaqsi"];
$codigoinm = $_POST["txt2codigoinmueblesi"];
$precio1 = $_POST["txt2precio1si"];
$precio2 = $_POST["txt2precio2si"];
$tipo = $_POST["cbo1codinmueblesi"];
$ubicacion = $_POST["cbo2codareasi"];
$departamento = $_POST["cbo1coddepsi"];
$ciudad = $_POST["cbo1codciusi"];


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
    pa.paraq
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
    LEFT JOIN inmuebleparaq AS pa 
        ON inmuebles.codparaq = pa.codparaq
    
     WHERE inmuebles.codinmueble > 0 AND inmuebles.pubini='Si'   ";

    if($codigoinm<>''){
                        $query_registros .= " AND inmuebles.codigo = '$codigoinm'";
                    }

    
    /*if($precio1<>''){
                        $query_registros .= " AND inmuebles.valor <= '$precio1'";
                    }

    if($precio2<>''){
                        $query_registros .= " AND inmuebles.valor <= '$precio2'";
                    }
                    */
                     if($precio1<>'' && $precio2<>'' ){
                        $query_registros .= " AND inmuebles.valor between '$precio1' AND '$precio2'";
                    }


      if($tipo<>'0'){
                        $query_registros .= " AND inmuebles.codtipinmueble = '$tipo'";
                    }


    if($paraq<>'0'){
                        $query_registros .= " AND inmuebles.codparaq = '$paraq'";
                    }
                    
    if($ubicacion<>'0'){
                        $query_registros .= " AND inmuebles.codzona = '$ubicacion'";
                    }   
                    
    if($departamento<>'0'){
                        $query_registros .= " AND ciudad.coddep = '$departamento'";
                    }               
                    
    if($ciudad<>'0'){
                        $query_registros .= " AND inmuebles.codciu = '$ciudad'";
                    }                   

                    $query_registros .= "  ORDER BY deppro.nomdep, zona.nomzona ";
                    $_SESSION["qryfiltroproductos"] = $query_registros;

}

if(isset($_SESSION["qryfiltroproductos"])){
    $query_registros=$_SESSION["qryfiltroproductos"];

}else{

$query_registros= "SELECT
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
    pa.paraq
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
    LEFT JOIN inmuebleparaq AS pa 
        ON inmuebles.codparaq = pa.codparaq
    
     WHERE inmuebles.codinmueble > 0 AND inmuebles.pubini='Si' "; 
}


//destruyesesiones("qryfiltroproductos");

include("paginador/paginadorinferior.php");



/*

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
    pa.paraq
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
    LEFT JOIN inmuebleparaq AS pa 
        ON inmuebles.codparaq = pa.codparaq
    
     WHERE inmuebles.codinmueble > 0 AND inmuebles.pubini='Si' ";



 $query_registros .= "ORDER BY deppro.nomdep, zona.nomzona ";*/

//include("paginador/paginadorinferior.php") ;



?>
<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

   <title>ESTABLECER INMOBILIARIA S.A.S - Venta de finca raiz</title>
        <?php 
        //En el <head> indicamos al objeto xajax se encargue de generar el javascript necesario 
        $xajax->printJavascript(); 
        include("administractor/fyles/general/metatags.php") ;

        ?>

    <!-- Bootstrap Core CSS -->
    <link href="css_1/bootstrap.css" rel="stylesheet">

    <!-- Owl Carousel Assets -->
    <link href="css_1/owl.carousel.css" rel="stylesheet">
    <link href="css_1/owl.theme.css" rel="stylesheet">
    <link href="css_1/owl.transitions.css" rel="stylesheet">

    <!-- Flexslider CSS -->
    <link href="css_1/flexslider.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css_1/main_style.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="css_1/font-awesome.min.css" rel="stylesheet" type="text/css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Top Bar -->
    <section class="top_sec">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6 top_lft">
                    <div class="soc_ico">
                        <ul>
                            <li class="tweet">
                                <a href="https://twitter.com/EstablecerInmob" target="_blank">
                                    <i class="fa fa-twitter"></i>
                                </a>
                            </li>
                            <li class="fb">
                                <a href="https://www.facebook.com/establecerinmobiliaria/?ref=ts&fref=ts" target="_blank">
                                    <i class="fa fa-facebook"></i>
                                </a>
                            </li>
                            <li class="insta">
                                <a href="https://www.instagram.com/establecer.inmobiliariasas/" target="_blank">
                                    <i class="fa fa-instagram"></i>
                                </a>
                            </li>
                            <li class="ytube">
                                <a href="https://www.youtube.com/channel/UCqfIUrxOGs003a48dJ-zbQg" target="_blank">
                                    <i class="fa fa-youtube"></i>
                                </a>
                            </li>
                            <li class="rss">
                                <a href="https://plus.google.com/u/0/105434771678483605104/posts" target="_blank">
                                    <i class="fa fa-rss"></i>
                                </a>
                            </li>


                        </ul>

                    </div>
                    <div class="inf_txt">
                        <p>Establecer Inmobiliaria s.a.s</p>
                    </div>

                </div>
                <!-- /.top-left -->
                <div class="col-xs-12 col-md-6 top_rgt">
                    <div class="sig_in">
                        <p><i class="fa fa-user"></i>
                            <a href="http://templates.crelegant.com/wedoor/#login_box" class="log_btn" data-toggle="modal"> Login </a> o <a class="reg_btn" href="http://templates.crelegant.com/wedoor/#reg_box" data-toggle="modal"> Registrese </a> </p>
                    </div>
                    <div class="submit_prop">
                        <h3 class="subm_btn"><a href="http://templates.crelegant.com/wedoor/#prop_box" data-toggle="modal">
                <i class="fa fa-bars"></i>
                    <span> Enviar Propiedad </span></a>
                </h3>
                    </div>

                </div>
                <!-- /.top-right -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>

    <!-- Navigation -->
    <nav class="navbar" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Logo -->
                <a class="navbar-brand" href="index.php"><img src="./files/logo.png" alt="logo">
                </a>
            </div>
            <!-- Navigation -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a class="" href="index.php"> Inicio </a>
                    </li>
                    <li>
                        <a href="script/ventas.php">VENTAS </a>
                    </li>
                    <li>
                        <a href="script/alquiler.php">ALQUILER</a>
                    </li>
                    <li>
                        <a href="script/servicios.php">SERVICIOS</a>
                    </li>
                    <li>
                        <a href="script/proyectos.php">PROYECTOS</a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="script/cali.php"> Cali </a>
                            </li>
                            <li>
                                <a href="script/jamundi.php"> Jamundi </a>
                            </li>
                            <li>
                                <a href="script/palmira.php"> Palmira </a>
                            </li>
                            <li>
                                <a href="script/yumbo.php"> Yumbo </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="">OFICINAS</a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="active" href="oficinas/cali.php"> CALI </a>
                            </li>
                            <li>
                                <a href="oficinas/bogota.php"> BOGOTA </a>
                            </li>
                            <li>
                                <a href="oficinas/miami.php"> MIAMI </a>
                            </li>
                            <li>
                                <a href="oficinas/espana.php"> ESPA&Ntilde;A </a>
                            </li>
                        </ul>

                    </li>
                    <li>
                        <a class="" href="http://templates.crelegant.com/wedoor/contact.html">CONTACTO                                       </a>
                    </li>

                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Header Stat Banner -->
    <header id="banner" class="stat_bann">
        <div class="bannr_sec">
            <img src="./files/banner_5.jpg" alt="Banner">
            <h1 class="main_titl">
            Establecer Inmobiliaria s.a.s
        </h1>
            <h4 class="sub_titl">
            Administraci&oacute;n - Ventas - Aval&uacute;os
        </h4>

        </div>
    </header>

    <!-- Page Content -->
    <section id="srch_slide">

        <div class="container">

            <!-- Search & Slider -->
            <div class="row">
                <!-- Search Form -->
                <div class="col-md-4">
                    <div class="srch_frm">
                        <h3>Realiza tu búsqueda aquí</h3>
                        <form name="sentMessage" id="contactForm" novalidate>
                            <div class="control-group form-group">
                                <div class="controls">
                                    <label>¿Qué Buscas? </label>
                                    <input type="text" class="form-control" id="keyword" required="" data-validation-required-message="Please enter a keyword." placeholder="por palabra...">
                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="control-group form-group">
                                <div class="controls">
                                    <label>Ciudad </label>
                                    <select name="State" class="form-control" required data-validation-required-message="Please select a state.">
                                        <option value="" selected="selected">Ciudad</option>
                                        <option value="AL">Cali</option>
                                        <option value="AK">Alaska</option>
                                        <option value="AZ">Arizona</option>
                                        <option value="AR">Arkansas</option>
                                        <option value="CA">California</option>
                                        <option value="CO">Colorado</option>
                                        <option value="CT">Connecticut</option>
                                        <option value="DE">Delaware</option>
                                        <option value="DC">District Of Columbia</option>
                                        <option value="FL">Florida</option>
                                        <option value="GA">Georgia</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="ID">Idaho</option>
                                        <option value="IL">Illinois</option>
                                        <option value="IN">Indiana</option>
                                        <option value="IA">Iowa</option>
                                        <option value="KS">Kansas</option>
                                        <option value="KY">Kentucky</option>
                                        <option value="LA">Louisiana</option>
                                        <option value="ME">Maine</option>
                                        <option value="MD">Maryland</option>
                                        <option value="MA">Massachusetts</option>
                                        <option value="MI">Michigan</option>
                                        <option value="MN">Minnesota</option>
                                        <option value="MS">Mississippi</option>
                                        <option value="MO">Missouri</option>
                                        <option value="MT">Montana</option>
                                        <option value="NE">Nebraska</option>
                                        <option value="NV">Nevada</option>
                                        <option value="NH">New Hampshire</option>
                                        <option value="NJ">New Jersey</option>
                                        <option value="NM">New Mexico</option>
                                        <option value="NY">New York</option>
                                        <option value="NC">North Carolina</option>
                                        <option value="ND">North Dakota</option>
                                        <option value="OH">Ohio</option>
                                        <option value="OK">Oklahoma</option>
                                        <option value="OR">Oregon</option>
                                        <option value="PA">Pennsylvania</option>
                                        <option value="RI">Rhode Island</option>
                                        <option value="SC">South Carolina</option>
                                        <option value="SD">South Dakota</option>
                                        <option value="TN">Tennessee</option>
                                        <option value="TX">Texas</option>
                                        <option value="UT">Utah</option>
                                        <option value="VT">Vermont</option>
                                        <option value="VA">Virginia</option>
                                        <option value="WA">Washington</option>
                                        <option value="WV">West Virginia</option>
                                        <option value="WI">Wisconsin</option>
                                        <option value="WY">Wyoming</option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group form-group">
                                <div class="controls col-md-6 first">
                                    <label>Tipo de Inmueble</label>
                                    <select name="Type" class="form-control" required data-validation-required-message="Please select a type.">
                                        <option value="" selected="selected">Casas</option>
                                        <option value="2">Apartamentos</option>
                                        <option value="3">Apartaestudios</option>
                                    </select>
                                </div>
                                <div class="controls col-md-6">
                                    <label>Inmueble para: </label>
                                    <select name="Actions" class="form-control" required data-validation-required-message="Please select a Actions.">
                                        <option value="" selected="selected">Venta</option>
                                        <option value="2">Alquiler</option>
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="control-group form-group">
                                <div class="controls col-md-6 first">
                                    <label>Min. Precio </label>
                                    <select name="min-price" class="form-control" required data-validation-required-message="Please select a Min. Price.">
                                        <option value="" selected="selected">$50</option>
                                        <option value="2">$00</option>
                                        <option value="3">$200</option>
                                        <option value="3">$300</option>
                                        <option value="3">$400</option>
                                        <option value="3">$500</option>
                                        <option value="3">$700</option>
                                        <option value="3">$800</option>
                                        <option value="3">$900</option>
                                        <option value="3">$000</option>
                                        <option value="3">$500</option>
                                        <option value="3">$2000</option>
                                        <option value="3">$2500</option>
                                    </select>
                                </div>
                                <div class="controls col-md-6">
                                    <label>Max. Precio </label>
                                    <select name="max-price" class="form-control" required data-validation-required-message="Please select a Max. Price.">
                                        <option value="" selected="selected">$200</option>
                                        <option value="2">$300</option>
                                        <option value="3">$400</option>
                                        <option value="3">$500</option>
                                        <option value="3">$600</option>
                                        <option value="3">$700</option>
                                        <option value="3">$800</option>
                                        <option value="3">$900</option>
                                        <option value="3">$1000</option>
                                        <option value="3">$1500</option>
                                        <option value="3">$2000</option>
                                        <option value="3">$2500</option>
                                        <option value="3">$3000</option>
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div id="success"></div>
                            <!-- For success/fail messages -->
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </form>
                    </div>
                </div>
                <!-- Slider -->
                <div class="col-md-8 slide_sec">
                    <div id="slider" class="silde_img flexslider">
                        
                    <div class="flex-viewport" style="overflow: hidden; position: relative;"><ul class="slides" style="width: 1000%; -webkit-transition-duration: 0s; transition-duration: 0s; -webkit-transform: translate3d(0px, 0px, 0px); transform: translate3d(0px, 0px, 0px);">
                            <!-- Slide 1 -->
                            <li class="flex-active-slide" style="width: 758px; float: left; display: block;">
                                <img src="./files/slider_4.jpg" alt="Slider image" draggable="false">
                                <div class="slide-info">
                                    <p class="sli_price"> Más de $400 Millones </p>
                                    <p class="sli_titl"> CASA CIUDAD JARDIN </p>
                                    <p class="sli_desc"> CONDOMINIO VENDO CASA DE TRES NIVELES</p>
                                </div>
                            </li>
                            <!-- Slide 2 -->
                            <li style="width: 758px; float: left; display: block;">
                                <img src="./files/slider_2.jpg" alt="Slider image" draggable="false">
                                <div class="slide-info">
                                    <p class="sli_price"> $450.000.000 </p>
                                    <p class="sli_titl"> CASA SAN FERNANDO </p>
                                    <p class="sli_desc"> VENDO CASA ESQUINERA COMERCIAL ESPECIAL PARA CONSULTORIOS OFICINAS EXCELENTE UBICACIÓN</p>
                                </div>
                            </li>
                            <!-- Slide 3 -->
                            <li style="width: 758px; float: left; display: block;">
                                <img src="./files/slider_1.jpg" alt="Slider image" draggable="false">
                                <div class="slide-info">
                                    <p class="sli_price">  Más de $400 Millones </p>
                                    <p class="sli_titl"> FINCA EN ROZO </p>
                                    <p class="sli_desc"> FINCA IDEAL PARA EVENTOS: PASA DIA - HOSPEDAJE. MATRIMONIOS, CUMPLEAÑOS, REUNIONES EMPRESARIALES, HERMOSAS CABAÑAS CON BAÑO PIVADO, ZONAS VERDES, PISCINA, BAR, SALON DE JUEGOS, COCINA CON TODO EL EQUIPAMIENTO, PESEBRERAS. </p>
                                </div>
                            </li>
                            <!-- Slide 4 -->
                            <li style="width: 758px; float: left; display: block;">
                                <img src="./files/slider_7.jpg" alt="Slider image" draggable="false">
                                <div class="slide-info">
                                    <p class="sli_price"> Más de $400 Millones </p>
                                    <p class="sli_titl"> EDIFICIO EN JUNIN </p>
                                    <p class="sli_desc"> 4 PISOS, TERRAZA CON CIMIENTOS PARA 7 PISOS, EXCELENTES ACABADOS, PARA REMODELAR DE ACUERDO A LA NECESIDAD </p>
                                </div>
                            </li>
                            <!-- Slide 5 -->
                            <li style="width: 758px; float: left; display: block;">
                                <img src="./files/slider_8.jpg" alt="Slider image" draggable="false">
                                <div class="slide-info">
                                    <p class="sli_price"> Más de $400 Millones </p>
                                    <p class="sli_titl"> PARQUEADERO CENTRO </p>
                                    <p class="sli_desc"> EXCELENTE UBICACIÓN A UNA CUADRA DE LA  GOBERNACIÓN VEN-PERMUTO MOTIVO VIAJE PARQUEADERO PARA CARROS Y MOTOS LATERAL DERECHO RECIÉN TECHADO PARA MEJOR COMODIDAD Y SERVICIO AL CLIENTE ÁREA 510 MTS2</p>
                                </div>
                            </li>
                        </ul></div><ul class="flex-direction-nav"><li class="flex-nav-prev"><a class="flex-prev flex-disabled" href="http://templates.crelegant.com/wedoor/#" tabindex="-1">Previous</a></li><li class="flex-nav-next"><a class="flex-next" href="http://templates.crelegant.com/wedoor/#">Next</a></li></ul></div>
                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container -->

    </section>

    <div class="spacer-60"></div>

    <!-- Featured Properties Section -->
    <section id="feat_propty">
        <div class="container">
            <div class="row">
                <div class="titl_sec">
                    <div class="col-xs-6">

                        <h3 class="main_titl text-left">
                    Propiedades Destacadas
                </h3>

                    </div>
                    <div class="col-xs-6">

                        <h3 class="link_titl text-right">
                    <a href="script/inmuebles.php"> Ver Inmuebles</a>
                </h3>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- Property 1 -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/property_1.jpg" alt="">
                            <div class="img_hov_eff">
                                <a class="btn btn-default btn_trans" href="http:inmuebles.php"> Ver Inmueble </a>
                            </div>

                        </div>
                        <div class="sal_labl">
                            For Sale
                        </div>

                        <div class="panel-body">
                            <div class="prop_feat">
                                <p class="area"><i class="fa fa-home"></i> 3000 Sq Ft</p>
                                <p class="bedrom"><i class="fa fa-bed"></i> 3 Bedrooms</p>
                                <p class="bedrom"><i class="fa fa-car"></i> 1 Garage</p>
                            </div>
                            <h3 class="sec_titl">
                         Amillarah Private Islands                 </h3>

                            <p class="sec_desc">
                                Heirloom art party iPhone kogi American Apparel stumptown try-hard tousled organic...
                            </p>
                            <div class="panel_bottom">
                                <div class="col-md-6">
                                    <p class="price text-left"> $250,100</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="readmore text-right"> <a href="inmuebles.php"> Read More </a> </p>

                                </div>



                            </div>
                        </div>
                    </div>
                    <div class="share_btn">
                        <i class="fa fa-share-alt"></i>
                        <div class="soc_btn">
                            <ul>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-facebook"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-twitter"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-google-plus"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-linkedin"></i> </a>
                                </li>

                            </ul>
                        </div>
                    </div>


                </div>
                <!-- Property 1 -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/property_1.jpg" alt="">
                            <div class="img_hov_eff">
                                <a class="btn btn-default btn_trans" href="http:inmuebles.php"> Ver Inmueble </a>
                            </div>

                        </div>
                        <div class="sal_labl">
                            For Sale
                        </div>

                        <div class="panel-body">
                            <div class="prop_feat">
                                <p class="area"><i class="fa fa-home"></i> 3000 Sq Ft</p>
                                <p class="bedrom"><i class="fa fa-bed"></i> 3 Bedrooms</p>
                                <p class="bedrom"><i class="fa fa-car"></i> 1 Garage</p>
                            </div>
                            <h3 class="sec_titl">
                         Amillarah Private Islands                 </h3>

                            <p class="sec_desc">
                                Heirloom art party iPhone kogi American Apparel stumptown try-hard tousled organic...
                            </p>
                            <div class="panel_bottom">
                                <div class="col-md-6">
                                    <p class="price text-left"> $250,100</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="readmore text-right"> <a href="inmuebles.php"> Read More </a> </p>

                                </div>



                            </div>
                        </div>
                    </div>
                    <div class="share_btn">
                        <i class="fa fa-share-alt"></i>
                        <div class="soc_btn">
                            <ul>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-facebook"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-twitter"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-google-plus"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-linkedin"></i> </a>
                                </li>

                            </ul>
                        </div>
                    </div>


                </div>
                <!-- Property 1 -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/property_1.jpg" alt="">
                            <div class="img_hov_eff">
                                <a class="btn btn-default btn_trans" href="http:inmuebles.php"> Ver Inmueble </a>
                            </div>

                        </div>
                        <div class="sal_labl">
                            For Sale
                        </div>

                        <div class="panel-body">
                            <div class="prop_feat">
                                <p class="area"><i class="fa fa-home"></i> 3000 Sq Ft</p>
                                <p class="bedrom"><i class="fa fa-bed"></i> 3 Bedrooms</p>
                                <p class="bedrom"><i class="fa fa-car"></i> 1 Garage</p>
                            </div>
                            <h3 class="sec_titl">
                         Amillarah Private Islands                 </h3>

                            <p class="sec_desc">
                                Heirloom art party iPhone kogi American Apparel stumptown try-hard tousled organic...
                            </p>
                            <div class="panel_bottom">
                                <div class="col-md-6">
                                    <p class="price text-left"> $250,100</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="readmore text-right"> <a href="inmuebles.php"> Read More </a> </p>

                                </div>



                            </div>
                        </div>
                    </div>
                    <div class="share_btn">
                        <i class="fa fa-share-alt"></i>
                        <div class="soc_btn">
                            <ul>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-facebook"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-twitter"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-google-plus"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-linkedin"></i> </a>
                                </li>

                            </ul>
                        </div>
                    </div>


                </div>
                <!-- Property 1 -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/property_1.jpg" alt="">
                            <div class="img_hov_eff">
                                <a class="btn btn-default btn_trans" href="http:inmuebles.php"> Ver Inmueble </a>
                            </div>

                        </div>
                        <div class="sal_labl">
                            For Sale
                        </div>

                        <div class="panel-body">
                            <div class="prop_feat">
                                <p class="area"><i class="fa fa-home"></i> 3000 Sq Ft</p>
                                <p class="bedrom"><i class="fa fa-bed"></i> 3 Bedrooms</p>
                                <p class="bedrom"><i class="fa fa-car"></i> 1 Garage</p>
                            </div>
                            <h3 class="sec_titl">
                         Amillarah Private Islands                 </h3>

                            <p class="sec_desc">
                                Heirloom art party iPhone kogi American Apparel stumptown try-hard tousled organic...
                            </p>
                            <div class="panel_bottom">
                                <div class="col-md-6">
                                    <p class="price text-left"> $250,100</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="readmore text-right"> <a href="inmuebles.php"> Read More </a> </p>

                                </div>



                            </div>
                        </div>
                    </div>
                    <div class="share_btn">
                        <i class="fa fa-share-alt"></i>
                        <div class="soc_btn">
                            <ul>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-facebook"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-twitter"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-google-plus"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-linkedin"></i> </a>
                                </li>

                            </ul>
                        </div>
                    </div>


                </div>
                <!-- Property 1 -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/property_1.jpg" alt="">
                            <div class="img_hov_eff">
                                <a class="btn btn-default btn_trans" href="http:inmuebles.php"> Ver Inmueble </a>
                            </div>

                        </div>
                        <div class="sal_labl">
                            For Sale
                        </div>

                        <div class="panel-body">
                            <div class="prop_feat">
                                <p class="area"><i class="fa fa-home"></i> 3000 Sq Ft</p>
                                <p class="bedrom"><i class="fa fa-bed"></i> 3 Bedrooms</p>
                                <p class="bedrom"><i class="fa fa-car"></i> 1 Garage</p>
                            </div>
                            <h3 class="sec_titl">
                         Amillarah Private Islands                 </h3>

                            <p class="sec_desc">
                                Heirloom art party iPhone kogi American Apparel stumptown try-hard tousled organic...
                            </p>
                            <div class="panel_bottom">
                                <div class="col-md-6">
                                    <p class="price text-left"> $250,100</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="readmore text-right"> <a href="inmuebles.php"> Read More </a> </p>

                                </div>



                            </div>
                        </div>
                    </div>
                    <div class="share_btn">
                        <i class="fa fa-share-alt"></i>
                        <div class="soc_btn">
                            <ul>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-facebook"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-twitter"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-google-plus"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-linkedin"></i> </a>
                                </li>

                            </ul>
                        </div>
                    </div>


                </div>
                <!-- Property 1 -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/property_1.jpg" alt="">
                            <div class="img_hov_eff">
                                <a class="btn btn-default btn_trans" href="http:inmuebles.php"> Ver Inmueble </a>
                            </div>

                        </div>
                        <div class="sal_labl">
                            For Sale
                        </div>

                        <div class="panel-body">
                            <div class="prop_feat">
                                <p class="area"><i class="fa fa-home"></i> 3000 Sq Ft</p>
                                <p class="bedrom"><i class="fa fa-bed"></i> 3 Bedrooms</p>
                                <p class="bedrom"><i class="fa fa-car"></i> 1 Garage</p>
                            </div>
                            <h3 class="sec_titl">
                         Amillarah Private Islands                 </h3>

                            <p class="sec_desc">
                                Heirloom art party iPhone kogi American Apparel stumptown try-hard tousled organic...
                            </p>
                            <div class="panel_bottom">
                                <div class="col-md-6">
                                    <p class="price text-left"> $250,100</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="readmore text-right"> <a href="inmuebles.php"> Read More </a> </p>

                                </div>



                            </div>
                        </div>
                    </div>
                    <div class="share_btn">
                        <i class="fa fa-share-alt"></i>
                        <div class="soc_btn">
                            <ul>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-facebook"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-twitter"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-google-plus"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-linkedin"></i> </a>
                                </li>

                            </ul>
                        </div>
                    </div>


                </div>
                <!-- Property 1 -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/property_1.jpg" alt="">
                            <div class="img_hov_eff">
                                <a class="btn btn-default btn_trans" href="http:inmuebles.php"> Ver Inmueble </a>
                            </div>

                        </div>
                        <div class="sal_labl">
                            For Sale
                        </div>

                        <div class="panel-body">
                            <div class="prop_feat">
                                <p class="area"><i class="fa fa-home"></i> 3000 Sq Ft</p>
                                <p class="bedrom"><i class="fa fa-bed"></i> 3 Bedrooms</p>
                                <p class="bedrom"><i class="fa fa-car"></i> 1 Garage</p>
                            </div>
                            <h3 class="sec_titl">
                         Amillarah Private Islands                 </h3>

                            <p class="sec_desc">
                                Heirloom art party iPhone kogi American Apparel stumptown try-hard tousled organic...
                            </p>
                            <div class="panel_bottom">
                                <div class="col-md-6">
                                    <p class="price text-left"> $250,100</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="readmore text-right"> <a href="inmuebles.php"> Read More </a> </p>

                                </div>



                            </div>
                        </div>
                    </div>
                    <div class="share_btn">
                        <i class="fa fa-share-alt"></i>
                        <div class="soc_btn">
                            <ul>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-facebook"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-twitter"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-google-plus"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-linkedin"></i> </a>
                                </li>

                            </ul>
                        </div>
                    </div>


                </div>
                <!-- Property 2 -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/property_2.jpg" alt="">
                            <div class="img_hov_eff">
                                <a class="btn btn-default btn_trans" href="inmuebles.php"> Ver Inmueble </a>
                            </div>

                        </div>
                        <div class="sal_labl">
                            For Rent
                        </div>

                        <div class="panel-body">
                            <div class="prop_feat">
                                <p class="area"><i class="fa fa-home"></i> 3000 Sq Ft</p>
                                <p class="bedrom"><i class="fa fa-bed"></i> 3 Bedrooms</p>
                                <p class="bedrom"><i class="fa fa-car"></i> 1 Garage</p>
                            </div>
                            <h3 class="sec_titl">
                         Amillarah Private Islands                 </h3>

                            <p class="sec_desc">
                                Heirloom art party iPhone kogi American Apparel stumptown try-hard tousled organic...
                            </p>
                            <div class="panel_bottom">
                                <div class="col-md-6">
                                    <p class="price text-left"> $250,100</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="readmore text-right"> <a href="inmuebles.php"> Read More </a> </p>


                                </div>



                            </div>
                        </div>
                    </div>
                    <div class="share_btn">
                        <i class="fa fa-share-alt"></i>
                        <div class="soc_btn">
                            <ul>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-facebook"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-twitter"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-google-plus"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-linkedin"></i> </a>
                                </li>

                            </ul>
                        </div>
                    </div>


                </div>
                <!-- Property 3 -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/property_3.jpg" alt="">
                            <div class="img_hov_eff">
                                <a class="btn btn-default btn_trans" href="inmuebles.php"> Ver Inmueble </a>
                            </div>

                        </div>
                        <div class="sal_labl">
                            For Rent
                        </div>

                        <div class="panel-body">
                            <div class="prop_feat">
                                <p class="area"><i class="fa fa-home"></i> 3000 Sq Ft</p>
                                <p class="bedrom"><i class="fa fa-bed"></i> 3 Bedrooms</p>
                                <p class="bedrom"><i class="fa fa-car"></i> 1 Garage</p>
                            </div>
                            <h3 class="sec_titl">
                         Amillarah Private Islands                 </h3>

                            <p class="sec_desc">
                                Heirloom art party iPhone kogi American Apparel stumptown try-hard tousled organic...
                            </p>
                            <div class="panel_bottom">
                                <div class="col-md-6">
                                    <p class="price text-left"> $250,100</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="readmore text-right"> <a href="inmuebles.php"> Read More </a> </p>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="share_btn">
                        <i class="fa fa-share-alt"></i>
                        <div class="soc_btn">
                            <ul>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-facebook"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-twitter"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-google-plus"></i> </a>
                                </li>
                                <li>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-linkedin"></i> </a>
                                </li>

                            </ul>
                        </div>
                    </div>


                </div>

            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>

    <div class="spacer-60"></div>

    <!-- Talented Agents Section -->
    <section id="talen_agent">
        <div class="container">
            <div class="row">
                <div class="titl_sec">
                    <div class="col-xs-6">

                        <h3 class="main_titl text-left">
                    Nuestros Agentes
                </h3>

                    </div>
                    <div class="col-xs-6">

                        <h3 class="link_titl text-right">
                    <a href="http://templates.crelegant.com/wedoor/agents.html"> Ver Propiedades de Agentes</a>
                </h3>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- Agent 1 -->
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/agent_1.jpg" alt="">
                        </div>
                        <div class="panel-body">
                            <h3 class="sec_titl text-center">
                            <a href="script/agente.php"> JOHN DIAZ  </a>                                   </h3>

                            <p class="sec_desc text-center">
                                Buying Agents
                            </p>
                            <div class="panel_hidd">
                                <hr>
                                <p class="phon text-center"> <a href="tel:253-891-8159"> Phone: 253-891-8159 </a> </p>

                                <div class="soc_icon">
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-twitter"></i> </a>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-facebook"></i> </a>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-linkedin"></i> </a>

                                </div>



                            </div>
                        </div>
                    </div>
                </div>
                <!-- Agents 2 -->
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/agent_2.jpg" alt="">
                        </div>
                        <div class="panel-body">
                            <h3 class="sec_titl text-center">
                            <a href="http://templates.crelegant.com/wedoor/agents_single.html">  ALEJANDRO ALEJO </a>                                    </h3>

                            <p class="sec_desc text-center">
                                Buying Agents
                            </p>
                            <div class="panel_hidd">
                                <hr>
                                <p class="phon text-center"> <a href="tel:253-891-8159"> Phone: 253-891-8159 </a> </p>

                                <div class="soc_icon">
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-twitter"></i> </a>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-facebook"></i> </a>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-linkedin"></i> </a>

                                </div>



                            </div>
                        </div>
                    </div>
                </div>
                <!-- Agents 3 -->
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/agent_4.jpg" alt="">
                        </div>
                        <div class="panel-body">
                            <h3 class="sec_titl text-center">
                            <a href="http://templates.crelegant.com/wedoor/agents_single.html">   CARLOS MUÑOZ </a>                                    </h3>

                            <p class="sec_desc text-center">
                                Buying Agents
                            </p>
                            <div class="panel_hidd">
                                <hr>
                                <p class="phon text-center"> <a href="tel:253-891-8159"> Phone: 253-891-8159 </a> </p>

                                <div class="soc_icon">
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-twitter"></i> </a>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-facebook"></i> </a>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-linkedin"></i> </a>

                                </div>



                            </div>
                        </div>
                    </div>
                </div>
                <!-- Agents 4 -->
                <div class="col-md-3">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/agent_4.jpg" alt="">
                        </div>
                        <div class="panel-body">
                            <h3 class="sec_titl text-center">
                            <a href="http://templates.crelegant.com/wedoor/agents_single.html">   Matthew Stalder  </a>                                    </h3>

                            <p class="sec_desc text-center">
                                Buying Agents
                            </p>
                            <div class="panel_hidd">
                                <hr>
                                <p class="phon text-center"> <a href="tel:253-891-8159"> Phone: 253-891-8159 </a> </p>

                                <div class="soc_icon">
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-twitter"></i> </a>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-facebook"></i> </a>
                                    <a href="http://templates.crelegant.com/wedoor/#"> <i class="fa fa-linkedin"></i> </a>

                                </div>



                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>

    <div class="spacer-60"></div>

    <!-- Testimonial Section -->
  <section id="testim">
        <div class="container">
            <div class="row testim_sec m0 owl-carousel owl-theme" style="opacity: 1; display: block;">
                <!-- Testimonial 1 -->
                <div class="owl-wrapper-outer"><div class="owl-wrapper" style="width: 5820px; left: 0px; display: block;"><div class="owl-item" style="width: 485px;"><div class="testim_box">
                    <blockquote>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel nibh vitae sapien lacinia finibus. Etia faucibus lorem in dui laoreet, eget euismod tellus lacinia.
                    </blockquote>
                    <div class="auth_sec">
                        <img src="images/agents/testim_1.jpg" alt="">
                        <h6 class="auth_nam">
                            David Greer
                            <span class="auth_pos">
                            Ceo Marketing
                        </span>
                        </h6>
                    </div>
                </div></div><div class="owl-item" style="width: 485px;"><div class="testim_box">
                    <blockquote>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel nibh vitae sapien lacinia finibus. Etia faucibus lorem in dui laoreet, eget euismod tellus lacinia.
                    </blockquote>
                    <div class="auth_sec">
                        <img src="images/agents/comm_1.jpg" alt="">
                        <h6 class="auth_nam">
                            David Greer
                            <span class="auth_pos">
                            Ceo Marketing
                        </span>
                        </h6>
                    </div>
                </div></div><div class="owl-item" style="width: 485px;"><div class="testim_box">
                    <blockquote>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel nibh vitae sapien lacinia finibus. Etia faucibus lorem in dui laoreet, eget euismod tellus lacinia.
                    </blockquote>
                    <div class="auth_sec">
                        <img src="images/agents/testim_2.jpg" alt="">
                        <h6 class="auth_nam">
                            Sara Jones
                            <span class="auth_pos">
                            Ceo Marketing
                        </span>
                        </h6>
                    </div>
                </div></div><div class="owl-item" style="width: 485px;"><div class="testim_box">
                    <blockquote>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel nibh vitae sapien lacinia finibus. Etia faucibus lorem in dui laoreet, eget euismod tellus lacinia.
                    </blockquote>
                    <div class="auth_sec">
                        <img src="images/agents/comm_2.jpg" alt="">
                        <h6 class="auth_nam">
                            Rebecca Dee
                            <span class="auth_pos">
                            Ceo Marketing
                        </span>
                        </h6>
                    </div>
                </div></div><div class="owl-item" style="width: 485px;"><div class="testim_box">
                    <blockquote>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel nibh vitae sapien lacinia finibus. Etia faucibus lorem in dui laoreet, eget euismod tellus lacinia.
                    </blockquote>
                    <div class="auth_sec">
                        <img src="images/agents/testim_3.jpg" alt="">
                        <h6 class="auth_nam">
                            John Connor
                            <span class="auth_pos">
                            Ceo Marketing
                        </span>
                        </h6>
                    </div>
                </div></div><div class="owl-item" style="width: 485px;"><div class="testim_box">
                    <blockquote>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer vel nibh vitae sapien lacinia finibus. Etia faucibus lorem in dui laoreet, eget euismod tellus lacinia.
                    </blockquote>
                    <div class="auth_sec">
                        <img src="images/agents/comm_4.jpg" alt="">
                        <h6 class="auth_nam">
                            Jack Milton
                            <span class="auth_pos">
                            Ceo Marketing
                        </span>
                        </h6>
                    </div>
                </div></div></div></div>
                <!-- Testimonial 2 -->
                
                <!-- Testimonial 3 -->
                
                <!-- Testimonial 4 -->
                
                <!-- Testimonial 5 -->
                
                <!-- Testimonial 6 -->
                


            <div class="owl-controls clickable"><div class="owl-buttons"><div class="owl-prev"><i class="fa fa-chevron-left icon-white"></i></div><div class="owl-next"><i class="fa fa-chevron-right icon-white"></i></div></div></div></div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
    <div class="spacer-60"></div>

    <!-- Latest News Section -->
    <section id="lates_news">
        <div class="container">
            <div class="row">
                <div class="titl_sec">
                    <div class="col-xs-6">

                        <h3 class="main_titl text-left">
                    Ultimas Noticias
                </h3>

                    </div>
                    <div class="col-xs-6">

                        <h3 class="link_titl text-right">
                    <a href="script/blog.php"> Ver Blog </a>
                </h3>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- News 1 -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/news_1.jpg" alt="">

                        </div>

                        <div class="panel-body">
                            <div class="news_dtd">
                                <p> On August 01, 2013 by <a href="http://templates.crelegant.com/wedoor/#"> John Doe </a> </p>
                            </div>
                            <h3 class="sec_titl">
                Example Post With image format                 </h3>

                            <p class="sec_desc">
                                Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod...
                            </p>
                            <p class="readmore text-left"> <a href="http://templates.crelegant.com/wedoor/blog-single.html"> Read More </a> </p>

                        </div>
                    </div>
                </div>
                <!-- News 2 -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div id="slide_pan" class="panel-image owl-carousel owl-theme" style="opacity: 1; display: block;">
                            <div class="owl-wrapper-outer"><div class="owl-wrapper" style="width: 2220px; left: 0px; display: block; -webkit-transition: all 0ms ease; transition: all 0ms ease; -webkit-transform: translate3d(0px, 0px, 0px); transform: translate3d(0px, 0px, 0px);"><div class="owl-item" style="width: 370px;"><img class="img-responsive img-hover" src="./files/news_2.jpg" alt=""></div><div class="owl-item" style="width: 370px;"><img class="img-responsive img-hover" src="./files/news_4.jpg" alt=""></div><div class="owl-item" style="width: 370px;"><img class="img-responsive img-hover" src="./files/news_5.jpg" alt=""></div></div></div>
                            
                            

                        <div class="owl-controls clickable"><div class="owl-buttons"><div class="owl-prev"><i class="fa fa-chevron-left icon-white"></i></div><div class="owl-next"><i class="fa fa-chevron-right icon-white"></i></div></div></div></div>

                        <div class="panel-body">
                            <div class="news_dtd">
                                <p> On August 01, 2013 by <a href="http://templates.crelegant.com/wedoor/#"> John Doe </a> </p>
                            </div>
                            <h3 class="sec_titl">
                         post example with slider included                 </h3>

                            <p class="sec_desc">
                                Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod...
                            </p>
                            <p class="readmore text-left"> <a href="http://templates.crelegant.com/wedoor/blog-single.html"> Read More </a> </p>

                        </div>
                    </div>
                </div>
                <!-- News 3 -->
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-image">
                            <img class="img-responsive img-hover" src="./files/news_3.jpg" alt="">

                        </div>

                        <div class="panel-body">
                            <div class="news_dtd">
                                <p> On August 01, 2013 by <a href="http://templates.crelegant.com/wedoor/#"> John Doe </a> </p>
                            </div>
                            <h3 class="sec_titl">
                         Lorem Ipsum Dolor Sit Amet                </h3>

                            <p class="sec_desc">
                                Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod...
                            </p>
                            <p class="readmore text-left"> <a href="http://templates.crelegant.com/wedoor/blog-single.html"> Read More </a> </p>

                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>

    <div class="spacer-60"></div>

    <!-- Subscribe Section -->
    <section id="subscribe">
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-right subs_info">
                    <h5>
                    For Subscribers Only
                </h5>

                    <h2>
                   Save up to 50% off your next trip
                </h2>
                </div>
                <!-- Subscribe Form -->
                <div class="col-md-6 text-left subs_form">
                    <form name="sentMessage" id="contactForm2" novalidate>
                        <div class="control-group form-group">
                            <div class="controls">
                                <input type="email" class="form-control" id="email" required="" data-validation-required-message="Please enter your email address." placeholder="Put your email address">
                                <button type="submit" class="btn btn-primary">Subscribe</button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>

    <div class="spacer-60"></div>

    <!-- Our clients -->
    <section id="clients">
        <div class="container">
            <div id="clients_car" class="row owl-carousel owl-theme" style="opacity: 1; display: block;">
                <div class="owl-wrapper-outer"><div class="owl-wrapper" style="width: 3888px; left: 0px; display: block; -webkit-transition: all 0ms ease; transition: all 0ms ease; -webkit-transform: translate3d(0px, 0px, 0px); transform: translate3d(0px, 0px, 0px);"><div class="owl-item" style="width: 216px;"><h2 class="hide"> Our Clients </h2></div><div class="owl-item" style="width: 216px;"><div class="owl_col">
                    <div class="mid_img"> <img class="img-responsive customer-img" src="./files/cl_logo_1.png" alt="">
                    </div>
                </div></div><div class="owl-item" style="width: 216px;"><div class="owl_col">
                    <div class="mid_img"> <img class="img-responsive customer-img" src="./files/cl_logo_2.png" alt="">
                    </div>
                </div></div><div class="owl-item" style="width: 216px;"><div class="owl_col">
                    <div class="mid_img"> <img class="img-responsive customer-img" src="./files/cl_logo_3.png" alt="">
                    </div>
                </div></div><div class="owl-item" style="width: 216px;"><div class="owl_col">
                    <div class="mid_img"> <img class="img-responsive customer-img" src="./files/cl_logo_4.png" alt="">
                    </div>
                </div></div><div class="owl-item" style="width: 216px;"><div class="owl_col">
                    <div class="mid_img"> <img class="img-responsive customer-img" src="./files/cl_logo_5.png" alt="">
                    </div>
                </div></div><div class="owl-item" style="width: 216px;"><div class="owl_col">
                    <div class="mid_img"> <img class="img-responsive customer-img" src="./files/cl_logo_6.png" alt="">
                    </div>
                </div></div><div class="owl-item" style="width: 216px;"><div class="owl_col">
                    <div class="mid_img"> <img class="img-responsive customer-img" src="./files/cl_logo_7.png" alt="">
                    </div>
                </div></div><div class="owl-item" style="width: 216px;"><div class="owl_col">
                    <div class="mid_img"> <img class="img-responsive customer-img" src="./files/cl_logo_8.png" alt="">
                    </div>
                </div></div></div></div>
                
                
                
                
                
                
                
                
            <div class="owl-controls clickable"><div class="owl-buttons"><div class="owl-prev"><i class="fa fa-chevron-left icon-white"></i></div><div class="owl-next"><i class="fa fa-chevron-right icon-white"></i></div></div></div></div>
            <!-- /.row -->
        </div>

    </section>

    <div class="spacer-60"></div>

    <!-- Footer -->
    <footer>
        <!-- Footer Top -->
        <div class="footer_top">
            <div class="container">
                <div class="row">
                    <!-- About Section -->
                    <div class="col-md-3 abt_sec">
                        <h2 class="foot_title">
                   Acerca de Establecer</h2>
                        <p>
                            Ethical quinoa slow-carb squid, irony Pitchfork tousled hella art party PBR&amp;B cray dreamcatcher brunch.
                        </p>

                        <div class="spacer-20"></div>

                        <p>
                            Bicycle rights jean shorts organic, street art PBR occupy flexitarian pour-over master cleanse farm-to-table.

                        </p>

                    </div>
                    <!-- Latest Tweets -->
                    <div class="col-md-3">
                        <h2 class="foot_title">
                   Latest Tweets
                </h2>
                        <ul class="tweets">
                            <li> <i class="fa fa-twitter"></i>
                                <p class="twee">
                                    Check out this great <a href="http://templates.crelegant.com/wedoor/#">#themeforest</a> item 'Responsive Photography WordPress <a href="http://templates.crelegant.com/wedoor/#">http://drbl.in/871942</a>
                                </p>

                                <p class="datd"> 6 April 2015 </p>
                                <div class="clearfix"></div>
                            </li>


                            <li class="spacer-20"></li>

                            <li><i class="fa fa-twitter"></i>

                                <p class="twee">
                                    <a href="http://templates.crelegant.com/wedoor/#"> #MadeBySeries </a> Made By: Chris Coyier, Founder <a href="http://templates.crelegant.com/wedoor/#">  http://ow.ly/LeAKf </a>
                                </p>

                                <p class="datd"> 6 April 2015 </p>
                                <div class="clearfix"></div>
                            </li>
                        </ul>

                    </div>
                    <!-- Contact Info -->
                    <div class="col-md-3">
                        <h2 class="foot_title">
                   Contact Info
                </h2>
                        <ul class="cont_info">
                            <li><i class="fa fa-map-marker"></i>
                                <p>Calle 2 Oeste 24Bis-69 Cali, Colombia</p>
                            </li>
                            <li><i class="fa fa-phone"></i>
                                <p> <a href="tel:407-546-2034"> Telefono: (2)3863054 <br> Movil: 3186977158, 3175073300</a> </p>
                            </li>
                            <li><i class="fa fa-envelope"></i>
                                <p> <a href="mailto:connect@crelegant.com?Subject=template%20enquiry"> Email: gerencia@establecerinmobiliaria.com.co </a> </p>
                            </li>
                        </ul>

                    </div>
                    <!-- Useful Links -->
                    <div class="col-md-3">
                        <h2 class="foot_title">
                            Useful Links
                        </h2>
                        <ul class="foot_nav">
                            <li> <a href="index.php">Busqueda de Inicio</a> </li>
                            <li> <a href="script/inmuebles/inmuebles.php">Inmuebles</a> </li>
                            <li> <a href="script/agentes/agentes.php">Nuestros Agentes</a> </li>
                            <li> <a href="script/noticias/index.php">Ultimas Noticias</a> </li>
                            <li> <a href="script/contacto/index.php">Contacto Web</a> </li>
                        </ul>

                    </div>

                </div>
                <!-- /.row -->
            </div>
            <!-- /.container -->

        </div>
        <!-- Copyright -->
        <div class="footer_copy_right" align="center">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12" >
                        <p>© Copyright 2017. Todos los derechos son reservados <a href="http://establecerinmobiliaria.com.co/#Ancla"> ESTABLECER INMOBILIARIA S.A.S </a>
                        </p>
                    </div>
                   
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal HTML -->
    <div id="login_box" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <div class="log_form">
                        <h2 class="frm_titl"> Login Form </h2>
                        <form name="sentMessage" id="loginForm" novalidate>
                            <div class="control-group form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" id="u-name" required="" data-validation-required-message="Please enter your username." placeholder="Username">
                                    <p class="help-block"></p>
                                </div>

                                <div class="controls">
                                    <input type="password" class="form-control" id="password" required="" data-validation-required-message="Please enter your password." placeholder="Password">

                                    <p class="help-block"></p>
                                </div>
                                <div class="checkbox col-md-6">
                                    <label>
                                        <input type="checkbox"> Remember me
                                    </label>
                                </div>
                                <div class="forg_pass col-md-6 text-right">
                                    <a class="" href="http://templates.crelegant.com/wedoor/#"> Forgot your password?  </a>
                                </div>
                                <div class="clearfix"></div>

                                <button type="submit" class="btn btn-primary">Sign In</button>
                                <div id="success2"></div>
                                <!-- For success/fail messages -->
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="reg_box" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <div class="log_form">
                        <h2 class="frm_titl"> Create Account </h2>
                        <form name="sentMessage" id="RegisForm" novalidate>
                            <div class="control-group form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" id="username" required="" data-validation-required-message="Please enter your username." placeholder="Username">
                                    <p class="help-block"></p>
                                </div>

                                <div class="controls">
                                    <input type="email" class="form-control" id="e-mail" required="" data-validation-required-message="Please enter your email." placeholder="Email">
                                    <p class="help-block"></p>
                                </div>

                                <div class="controls">
                                    <input type="password" class="form-control" id="passd" required="" data-validation-required-message="Please enter your password." placeholder="Password">

                                    <p class="help-block"></p>
                                </div>
                                <div class="controls">
                                    <input type="password" class="form-control" id="re-passd" required="" data-validation-required-message="Please enter your password." placeholder="Retype Password">

                                    <p class="help-block"></p>
                                </div>

                                <button type="submit" class="btn btn-primary">Create Account</button>
                                <div id="success3"></div>
                                <!-- For success/fail messages -->
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="prop_box" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <div class="log_form">
                        <h2 class="frm_titl"> Property Listing </h2>
                        <form name="sentMessage" id="listform" novalidate>
                            <div class="control-group form-group">
                                <div class="controls">
                                    <input type="text" class="form-control" id="list_name" required="" data-validation-required-message="Please enter the listing name." placeholder="Listing Name">
                                    <p class="help-block"></p>
                                </div>

                                <div class="controls">
                                    <input type="text" class="form-control" id="addr" required="" data-validation-required-message="Please enter your Address." placeholder="Address">

                                    <p class="help-block"></p>
                                </div>
                            </div>
                            <div class="control-group form-group">
                                <div class="controls col-md-6 first">
                                    <select name="Type" class="form-control" required data-validation-required-message="Please select a type.">
                                        <option value="0" selected="selected">Select the listing type</option>
                                        <option value="1">Industrial</option>
                                        <option value="2">Commercial</option>
                                        <option value="3">Household</option>
                                    </select>
                                </div>
                                <div class="controls col-md-6">
                                    <select name="Actions" class="form-control" required data-validation-required-message="Please select a Actions.">
                                        <option value="0" selected="selected">Select the Action</option>
                                        <option value="1">For Rent</option>
                                        <option value="2">For Sale</option>
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="control-group form-group">
                                <div class="controls col-md-6 first">
                                    <input type="text" class="form-control" id="area" required="" data-validation-required-message="Please enter the Area." placeholder="Area(sq.ft)">
                                </div>
                                <div class="controls col-md-6">
                                    <input type="text" class="form-control" id="rate" required="" data-validation-required-message="Please enter your Rate." placeholder="Rate">
                                </div>
                                <div class="clearfix"></div>
                            </div>                            
                            <div class="control-group form-group">
                                <div class="controls col-md-6 first">
                                    <input type="text" class="form-control" id="agt_name" required="" data-validation-required-message="Please enter the Agent name." placeholder="Agent Name">
                                </div>
                                <div class="controls col-md-6">
                                    <input type="text" class="form-control" id="agt_number" required="" data-validation-required-message="Please enter the Agent Contact Number." placeholder="Agent Contact Number">
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="control-group form-group">
                                <div class="controls">
                                    <textarea rows="10" cols="60" class="form-control" id="message" required data-validation-required-message="Please enter your Property description" maxlength="999" style="resize:none" placeholder="Property Description"></textarea>
                                </div>
                            <button type="submit" class="btn btn-primary">Enviar Propiedad</button>
                            <div id="success4"></div>
                            <!-- For success/fail messages -->

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="./files/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="./files/bootstrap.min.js"></script>

    <!-- Owl Carousel JavaScript -->
    <script src="./files/owl.carousel.min.js"></script>

    <!-- Flexslider JavaScript -->
    <script src="./files/jquery.flexslider-min.js"></script>


    <!-- Script to Activate the Carousels -->
    <script type="text/javascript">
        $(document).ready(function () {
            'use strict';
            $("#clients").owlCarousel({
                items: 5,
                itemsDesktop: [1199, 5],
                itemsDesktopSmall: [979, 3],
                itemsTablet: [768, 2],
                itemsMobile: [479, 1],
                navigation: true,
                navigationText: [
      "<i class='fa fa-chevron-left icon-white'></i>",
      "<i class='fa fa-chevron-right icon-white'></i>"
      ],
                autoPlay: false,
                pagination: false
            });

            $("#slide_pan").owlCarousel({
                items: 1,
                itemsDesktop: [1199, 1],
                itemsDesktopSmall: [979, 1],
                itemsTablet: [768, 1],
                itemsMobile: [479, 1],
                navigation: true,
                navigationText: [
      "<i class='fa fa-chevron-left icon-white'></i>",
      "<i class='fa fa-chevron-right icon-white'></i>"
      ],
                autoPlay: false,
                pagination: false
            });

            $("#testim").owlCarousel({
                items: 2,
                itemsDesktop: [1199, 2],
                itemsDesktopSmall: [979, 2],
                itemsTablet: [768, 1],
                itemsMobile: [479, 1],
                navigation: true,
                navigationText: [
      "<i class='fa fa-chevron-left icon-white'></i>",
      "<i class='fa fa-chevron-right icon-white'></i>"
      ],
                autoPlay: false,
                pagination: false
            });
			
			
			 $("#testim").owlCarousel({
                items: 2,
                itemsDesktop: [1199, 2],
                itemsDesktopSmall: [979, 2],
                itemsTablet: [768, 1],
                itemsMobile: [479, 1],
                navigation: true,
                navigationText: [
      "<i class='fa fa-chevron-left icon-white'></i>",
      "<i class='fa fa-chevron-right icon-white'></i>"
      ],
                autoPlay: false,
                pagination: false
            });


            $('#slider').flexslider({
                animation: "slide",
                controlNav: false,
                animationLoop: false,
                slideshow: false
            });
        $('ul.drop_menu [data-toggle=dropdown]').on('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                $(this).parent().siblings().removeClass('open');
                $(this).parent().toggleClass('open');
            });
        });
		
    </script>




</body></html>