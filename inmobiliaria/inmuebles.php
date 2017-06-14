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

     <!-- bxslider -->
    <link href="css_1/jquery.bxslider.css" rel="stylesheet">

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
                        <p>Luxury Real Estate Specialists Worldwide</p>
                    </div>

                </div>
                <!-- /.top-left -->
                <div class="col-xs-12 col-md-6 top_rgt">
                    <div class="sig_in">
                        <p><i class="fa fa-user"></i>
                            <a href="http://templates.crelegant.com/wedoor/property_details.html#login_box" class="log_btn" data-toggle="modal"> Login </a> or <a class="reg_btn" href="http://templates.crelegant.com/wedoor/property_details.html#reg_box" data-toggle="modal"> Registrese </a> </p>
                    </div>
                    <div class="submit_prop">
                        <h3 class="subm_btn"><a href="http://templates.crelegant.com/wedoor/property_details.html#prop_box" data-toggle="modal">
                <i class="fa fa-bars"></i>
                    <span> Submit Property </span></a>
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

    <!-- Header bradcrumb -->
    <header class="bread_crumb">
        
        <div class="pg_links">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p class="lnk_pag"><a href="http://templates.crelegant.com/wedoor/index.html"> Home </a> </p>
                        <p class="lnk_pag"> / </p>
                        <p class="lnk_pag"> Property Details </p>
                    </div>
                    <div class="col-md-6 text-right">
                        <p class="lnk_pag"><a href="http://templates.crelegant.com/wedoor/index.html"> Go Back to Home </a> </p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="spacer-60"></div>
    <div class="container">
        <div class="row">
            <!-- Proerty Details Section -->
            <section id="prop_detal" class="col-md-8">
                <div class="row">
                    <div class="panel panel-default">
                        <!-- Proerty Slider Images -->
                        <div class="panel-image">
                            <div class="bx-wrapper" style="max-width: 100%;"><div class="bx-viewport" style="width: 100%; overflow: hidden; position: relative; height: 433px;"><ul id="prop_slid" style="width: 715%; position: relative; transition-duration: 0s; transform: translate3d(-810px, 0px, 0px);"><li style="float: left; list-style: none; position: relative; width: 770px;" class="bx-clone"><img class="img-responsive" src="prop_5.jpg" alt="Property Slide Image">
                                </li>
                                <li style="float: left; list-style: none; position: relative; width: 770px;"><img class="img-responsive" src="prop_1.jpg" alt="Property Slide Image">
                                </li>
                                <li style="float: left; list-style: none; position: relative; width: 770px;"><img class="img-responsive" src="prop_2.jpg" alt="Property Slide Image">
                                </li>
                                <li style="float: left; list-style: none; position: relative; width: 770px;"><img class="img-responsive" src="prop_3.jpg" alt="Property Slide Image">
                                </li>
                                <li style="float: left; list-style: none; position: relative; width: 770px;"><img class="img-responsive" src="prop_4.jpg" alt="Property Slide Image">
                                </li>
                                <li style="float: left; list-style: none; position: relative; width: 770px;"><img class="img-responsive" src="prop_5.jpg" alt="Property Slide Image">
                                </li>
                            <li style="float: left; list-style: none; position: relative; width: 770px;" class="bx-clone"><img class="img-responsive" src="prop_1.jpg" alt="Property Slide Image">
                                </li></ul></div><div class="bx-controls bx-has-controls-direction"><div class="bx-controls-direction"><a class="bx-prev" href="http://templates.crelegant.com/wedoor/property_details.html">Prev</a><a class="bx-next" href="http://templates.crelegant.com/wedoor/property_details.html">Next</a></div></div></div>
                            <!-- Proerty Slider Thumbnails -->
                            <div class="col-md-12 rel_img">
                                <ul id="slid_nav">
                                    <li>
                                        <a data-slide-index="0" href="http://templates.crelegant.com/wedoor/property_details.html" class="active"><img class="img-responsive img-hover" src="prop_1s.jpg" alt="">
                                        </a>
                                    </li>
                                    <li>
                                        <a data-slide-index="1" href="http://templates.crelegant.com/wedoor/property_details.html"><img class="img-responsive img-hover" src="prop_2s.jpg" alt="">
                                        </a>
                                    </li>
                                    <li>
                                        <a data-slide-index="2" href="http://templates.crelegant.com/wedoor/property_details.html"><img class="img-responsive img-hover" src="prop_3s.jpg" alt="">
                                        </a>
                                    </li>
                                    <li>
                                        <a data-slide-index="3" href="http://templates.crelegant.com/wedoor/property_details.html"><img class="img-responsive img-hover" src="prop_4s.jpg" alt="">
                                        </a>
                                    </li>
                                    <li>
                                        <a data-slide-index="4" href="http://templates.crelegant.com/wedoor/property_details.html"><img class="img-responsive img-hover" src="prop_5s.jpg" alt="">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="panel-body">
                            <div class="prop_feat">
                                <p class="area"><i class="fa fa-home"></i> 3000 Sq Ft</p>
                                <p class="bedrom"><i class="fa fa-bed"></i> 3 Bedrooms</p>
                                <p class="bedrom"><i class="fa fa-car"></i> 1 Garage</p>
                                <p class="bedrom"><i class="fa fa-star-o"></i> Add to Favorites </p>
                                <p class="bedrom"><i class="fa fa-print"></i> Print This Details </p>

                                <div class="share_btn">
                                    <a href="http://templates.crelegant.com/wedoor/property_details.html#"> <i class="fa fa-share-alt"></i>
                                    </a>
                                    <div class="soc_btn">
                                        <ul>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#"> <i class="fa fa-facebook"></i> </a>
                                            </li>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#"> <i class="fa fa-twitter"></i> </a>
                                            </li>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#"> <i class="fa fa-google-plus"></i> </a>
                                            </li>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#"> <i class="fa fa-linkedin"></i> </a>
                                            </li>

                                        </ul>
                                    </div>

                                </div>
                            </div>

                            <h3 class="sec_titl">
                         Amillarah Private Islands                </h3>

                            <div class="col_labls larg_labl">

                                <p class="or_labl">For Sale</p>
                                <p class="blu_labl"> $470,00</p>

                            </div>

                            <p class="sec_desc">
                                Whatever diy Odd Future. Lomo cornhole pickled viral, Godard trust fund McSweeney's mlkshk seitan blog PBR&amp;B occupy health goth four loko. Intelligentsia raw denim tousled quinoa. Listicle cred chillwave flannel, migas next level sriracha Shoreditch. Pop-up Williamsburg PBR&amp;B, aesthetic YOLO kogi butcher Austin chia yr XOXO cliche. Normcore pug Blue Bottle 3 wolf moon gentrify.
                            </p>

                            <!-- Proerty Additional Info -->
                            <div class="prop_addinfo">
                                <h2 class="add_titl">
                                Additional Details
                            </h2>

                                <div class="info_sec first">
                                    <div class="col-md-5">
                                        <ul>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                                    <i class="fa fa-long-arrow-right"></i>
                                                    <p class="infos"> Price: <span> $250.100 </span> </p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                                    <i class="fa fa-long-arrow-right"></i>
                                                    <p class="infos"> For Sale/Rent: <span> Sale </span> </p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                                    <i class="fa fa-long-arrow-right"></i>
                                                    <p class="infos"> Cross Streets: <span> City of New York </span> </p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                                    <i class="fa fa-long-arrow-right"></i>
                                                    <p class="infos"> Area: <span> 3000 Sq Ft </span> </p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                                    <i class="fa fa-long-arrow-right"></i>
                                                    <p class="infos"> Garages: <span> 1 </span> </p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                                    <i class="fa fa-long-arrow-right"></i>
                                                    <p class="infos">Bedrooms: <span> 3 </span> </p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                                    <i class="fa fa-long-arrow-right"></i>
                                                    <p class="infos">Bathrooms: <span> 2 </span> </p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                                    <i class="fa fa-long-arrow-right"></i>
                                                    <p class="infos">Acres: <span> 0.19 </span> </p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4">
                                        <ul>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                                    <i class="fa fa-long-arrow-right"></i>
                                                    <p class="infos">Heat: <span> Forced Air </span> </p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                                    <i class="fa fa-long-arrow-right"></i>
                                                    <p class="infos">Dimensions: <span> 80x100 </span> </p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                                    <i class="fa fa-long-arrow-right"></i>
                                                    <p class="infos">Size Source: <span> Assessor </span> </p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                                    <i class="fa fa-long-arrow-right"></i>
                                                    <p class="infos">AC: <span> Center </span> </p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <div class="spacer-30"></div>

                <!-- Agent Info -->
                <div class="row">
                    <div class="agen_info">

                        <div class="col-md-4">
                            <a href="http://templates.crelegant.com/wedoor/agents_single.html"><img class="img-responsive img-hover" src="agen_1.jpg" alt="">
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="panel panel-default">

                                <div class="panel-body">
                                    <div class="row agen_desc">
                                        <div class="col-sm-8">
                                            <h3 class="sec_titl">
                            <a href="http://templates.crelegant.com/wedoor/agents_single.html"> Scott Berends </a>                                   </h3>
                                            <p class="sec_desc">
                                                Buying Agents
                                            </p>

                                        </div>
                                        <div class="col-sm-4">
                                            <div class="soc_icon">
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#"> <i class="fa fa-twitter"></i> </a>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#"> <i class="fa fa-facebook"></i> </a>
                                                <a href="http://templates.crelegant.com/wedoor/property_details.html#"> <i class="fa fa-linkedin"></i> </a>
                                            </div>

                                        </div>
                                    </div>
                                    <p class="sec_desc">
                                        Single origin coffee crucifix tousled freegan lo-fi wayfare flexitaria Marfa deepbanh mi church-key direct trad street American Apparel Pinterest pop-up banh mi you probably.
                                    </p>
                                    <div class="panel_bottom">
                                        <div class="agen_feat">
                                            <p class="area">
                                                <a href="tel:910-213-7890"> <i class="fa fa-phone"></i> 910-213-7890 </a>
                                            </p>
                                            <p class="bedrom">
                                                <a href="mailto:scott@berends.com?Subject=Agent%20enquiry"> <i class="fa fa-envelope"></i> scott@berends.com </a>
                                            </p>
                                            <p class="bedrom"><a href="skype:-scottberends1-?chat"><i class="fa fa-skype"></i> scottberends1 </a> </p>
                                        </div>



                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <div class="spacer-30"></div>

                <!-- Proerty Map -->

                <div class="row">
                    <div class="titl_sec">
                        <div class="col-lg-12">

                            <h3 class="main_titl text-left">
                    Property Map
                </h3>

                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-md-12">
                        <div class="prop_map">
                            <iframe src="embed.html" width="600" height="350"></iframe>
                        </div>

                    </div>
                </div>
                <!-- /.row -->


            </section>

            <!-- Sidebar Section -->
            <section id="sidebar" class="col-md-4">
                <!-- Search Form -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="srch_frm">
                            <h3>Real Estate Search</h3>
                            <form name="sentMessage" id="contactForm" novalidate="">
                                <div class="control-group form-group">
                                    <div class="controls">
                                        <label>Keyword </label>
                                        <input type="text" class="form-control" id="keyword" required="" data-validation-required-message="Please enter a keyword." placeholder="Any keyword">
                                        <p class="help-block"></p>
                                    </div>
                                </div>
                                <div class="control-group form-group">
                                    <div class="controls">
                                        <label>Location </label>
                                        <select name="State" class="form-control" required="" data-validation-required-message="Please select a state.">
                                            <option value="" selected="selected">Any Location</option>
                                            <option value="AL">Alabama</option>
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
                                        <label>Type </label>
                                        <select name="Type" class="form-control" required="" data-validation-required-message="Please select a type.">
                                            <option value="" selected="selected">Industrial</option>
                                            <option value="2">Commercial</option>
                                            <option value="3">Household</option>
                                        </select>
                                    </div>
                                    <div class="controls col-md-6">
                                        <label>Actions </label>
                                        <select name="Actions" class="form-control" required="" data-validation-required-message="Please select a Actions.">
                                            <option value="" selected="selected">For Rent</option>
                                            <option value="2">For Sale</option>
                                        </select>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                                <div class="control-group form-group">
                                    <div class="controls col-md-6 first">
                                        <label>Min. Price </label>
                                        <select name="min-price" class="form-control" required="" data-validation-required-message="Please select a Min. Price.">
                                            <option value="" selected="selected">$50</option>
                                            <option value="2">$100</option>
                                            <option value="3">$200</option>
                                            <option value="3">$300</option>
                                            <option value="3">$400</option>
                                            <option value="3">$500</option>
                                            <option value="3">$700</option>
                                            <option value="3">$800</option>
                                            <option value="3">$900</option>
                                            <option value="3">$1000</option>
                                            <option value="3">$1500</option>
                                            <option value="3">$2000</option>
                                            <option value="3">$2500</option>
                                        </select>
                                    </div>
                                    <div class="controls col-md-6">
                                        <label>Max. Price </label>
                                        <select name="max-price" class="form-control" required="" data-validation-required-message="Please select a Max. Price.">
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
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <div class="spacer-30"></div>
                <!-- Categories -->
                <div class="row">
                    <div class="titl_sec">
                        <div class="col-lg-12">

                            <h3 class="main_titl text-left">
                    Categories
                </h3>

                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="categ_info">

                        <div class="info_sec first">
                            <div class="col-md-6">
                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                    <i class="fa fa-long-arrow-right"></i>
                                    <p class="infos">Articles (3) </p>
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                    <i class="fa fa-long-arrow-right"></i>
                                    <p class="infos">Real Estate (8) </p>
                                </a>
                            </div>
                        </div>

                        <div class="info_sec">
                            <div class="col-md-6">
                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                    <i class="fa fa-long-arrow-right"></i>
                                    <p class="infos">Branding (4) </p>
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                    <i class="fa fa-long-arrow-right"></i>
                                    <p class="infos">WordPress (2) </p>
                                </a>
                            </div>
                        </div>

                        <div class="info_sec">
                            <div class="col-md-6">
                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                    <i class="fa fa-long-arrow-right"></i>
                                    <p class="infos">Architecture (12) </p>
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="http://templates.crelegant.com/wedoor/property_details.html#">
                                    <i class="fa fa-long-arrow-right"></i>
                                    <p class="infos">Resposnive (6) </p>
                                </a>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- /.row -->


                <div class="spacer-30"></div>
                <!-- Featured Properties -->
                <div class="row">
                    <div class="titl_sec">
                        <div class="col-lg-12">

                            <h3 class="main_titl text-left">
                                Featured Properties
                            </h3>

                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="side_feat">
                        <div class="panel panel-default">
                            <div class="panel-image col-md-3">
                                <a href="http://templates.crelegant.com/wedoor/property_details.html"> <img class="img-responsive img-hover" src="feat_prop_1.jpg" alt=""> </a>
                            </div>

                            <div class="panel-body col-md-9">
                                <h3 class="sec_titl">
                                    <a href="http://templates.crelegant.com/wedoor/property_details.html"> Nulla sed dolor vestibu porttitor erat ultricies </a>                         
                                </h3>

                                <div class="prop_feat">
                                    <p class="area"><i class="fa fa-home"></i> 3000 Sq Ft</p>
                                    <p class="bedrom"><i class="fa fa-car"></i> 1 Garage</p>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-image col-md-3">
                                <a href="http://templates.crelegant.com/wedoor/property_details.html"> <img class="img-responsive img-hover" src="feat_prop_2.jpg" alt=""> </a>

                            </div>

                            <div class="panel-body col-md-9">
                                <h3 class="sec_titl">
                                    <a href="http://templates.crelegant.com/wedoor/property_details.html">Nulla sed dolor vestibu porttitor erat ultricies </a>                        
                                </h3>

                                <div class="prop_feat">
                                    <p class="area"><i class="fa fa-home"></i> 3000 Sq Ft</p>
                                    <p class="bedrom"><i class="fa fa-car"></i> 1 Garage</p>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-image col-md-3">
                                <a href="http://templates.crelegant.com/wedoor/property_details.html"> <img class="img-responsive img-hover" src="feat_prop_3.jpg" alt=""> </a>

                            </div>

                            <div class="panel-body col-md-9">
                                <h3 class="sec_titl">
                                    <a href="http://templates.crelegant.com/wedoor/property_details.html"> Nulla sed dolor vestibu porttitor erat ultricies  </a>                       
                                </h3>

                                <div class="prop_feat">
                                    <p class="area"><i class="fa fa-home"></i> 3000 Sq Ft</p>
                                    <p class="bedrom"><i class="fa fa-car"></i> 1 Garage</p>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                <!-- /.row -->

                <div class="spacer-30"></div>
                <!-- Tags -->
                <div class="row">
                    <div class="titl_sec">
                        <div class="col-lg-12">

                            <h3 class="main_titl text-left">
                    Tags
                </h3>

                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="tags_sec">

                        <div class="tags_box first">
                            <a href="http://templates.crelegant.com/wedoor/property_details.html#"> Modern </a>
                        </div>

                        <div class="tags_box">
                            <a href="http://templates.crelegant.com/wedoor/property_details.html#"> Amazing</a>
                        </div>

                        <div class="tags_box">
                            <a href="http://templates.crelegant.com/wedoor/property_details.html#"> Responsive</a>
                        </div>

                        <div class="tags_box first">
                            <a href="http://templates.crelegant.com/wedoor/property_details.html#"> Development</a>
                        </div>

                        <div class="tags_box">
                            <a href="http://templates.crelegant.com/wedoor/property_details.html#"> Rent</a>
                        </div>

                        <div class="tags_box">
                            <a href="http://templates.crelegant.com/wedoor/property_details.html#"> Properties</a>
                        </div>

                        <div class="tags_box first">
                            <a href="http://templates.crelegant.com/wedoor/property_details.html#"> Themeforest</a>
                        </div>

                        <div class="tags_box">
                            <a href="http://templates.crelegant.com/wedoor/property_details.html#"> Development</a>
                        </div>

                        <div class="tags_box">
                            <a href="http://templates.crelegant.com/wedoor/property_details.html#"> HTML  </a>
                        </div>


                    </div>


                </div>
                <!-- /.row -->

                <div class="spacer-30"></div>

            </section>

            <div class="spacer-60"></div>

        </div>
    </div>
    <!-- Footer -->
    <footer>
        <!-- Footer Top -->
        <div class="footer_top">
            <div class="container">
                <div class="row">
                    <!-- About Section -->
                    <div class="col-md-3 abt_sec">
                        <h2 class="foot_title">
                   About Wedoor
                </h2>
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
                                    Check out this great <a href="http://templates.crelegant.com/wedoor/property_details.html#">#themeforest</a> item 'Responsive Photography WordPress <a href="http://templates.crelegant.com/wedoor/property_details.html#">http://drbl.in/871942</a>
                                </p>

                                <p class="datd"> 6 April 2015 </p>
                                <div class="clearfix"></div>
                            </li>


                            <li class="spacer-20"></li>

                            <li><i class="fa fa-twitter"></i>

                                <p class="twee">
                                    <a href="http://templates.crelegant.com/wedoor/property_details.html#"> #MadeBySeries </a> Made By: Chris Coyier, Founder <a href="http://templates.crelegant.com/wedoor/property_details.html#">  http://ow.ly/LeAKf </a>
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
                                <p>371 Linden Avenue Longwood, FL 32750 </p>
                            </li>
                            <li><i class="fa fa-phone"></i>
                                <p> <a href="tel:407-546-2034"> Phone: 407-546-2034 </a> </p>
                            </li>
                            <li><i class="fa fa-envelope"></i>
                                <p> <a href="mailto:connect@crelegant.com?Subject=template%20enquiry"> Email: connect@crelegant.com </a> </p>
                            </li>
                        </ul>

                    </div>
                    <!-- Useful Links -->
                    <div class="col-md-3">
                        <h2 class="foot_title">
                            Useful Links
                        </h2>
                        <ul class="foot_nav">
                            <li> <a href="http://templates.crelegant.com/wedoor/index.html">Home Search</a> </li>
                            <li> <a href="http://templates.crelegant.com/wedoor/property_listing.html">Properties Inspection</a> </li>
                            <li> <a href="http://templates.crelegant.com/wedoor/agents.html">Agents Consult</a> </li>
                            <li> <a href="http://templates.crelegant.com/wedoor/blog.html">Latest News</a> </li>
                            <li> <a href="http://templates.crelegant.com/wedoor/contact.html">Get in touch</a> </li>
                        </ul>

                    </div>

                </div>
                <!-- /.row -->
            </div>
            <!-- /.container -->

        </div>
        <!-- Copyright -->
        <div class="footer_copy_right">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 text-left">
                        <p>© Copyright 2014. All Rights Reserved by <a href="http://templates.crelegant.com/wedoor/property_details.html#"> WeDoor </a>
                        </p>
                    </div>
                    <div class="col-sm-6 text-right">
                        <p>Template developed by <a href="http://themeforest.net/user/crelegant"> The Crelegant Team </a> </p>
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
                        <form name="sentMessage" id="loginForm" novalidate="">
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
                                    <a class="" href="http://templates.crelegant.com/wedoor/property_details.html#"> Olvido su contraseña?  </a>
                                </div>
                                <div class="clearfix"></div>

                                <button type="submit" class="btn btn-primary">Login</button>
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
                        <h2 class="frm_titl">  </h2>
                        <form name="sentMessage" id="RegisForm" novalidate="">
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

                                <button type="submit" class="btn btn-primary">Registrese</button>
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
                        <form name="sentMessage" id="listform" novalidate="">
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
                                    <select name="Type" class="form-control" required="" data-validation-required-message="Please select a type.">
                                        <option value="0" selected="selected">Select the listing type</option>
                                        <option value="1">Industrial</option>
                                        <option value="2">Commercial</option>
                                        <option value="3">Household</option>
                                    </select>
                                </div>
                                <div class="controls col-md-6">
                                    <select name="Actions" class="form-control" required="" data-validation-required-message="Please select a Actions.">
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
                                    <textarea rows="10" cols="60" class="form-control" id="message" required="" data-validation-required-message="Please enter your Property description" maxlength="999" style="resize:none" placeholder="Property Description"></textarea>
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
    <script src="jquery.js.descarga"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap.min.js.descarga"></script>

    <!-- BX Slider -->
    <script src="jquery.bxslider.min.js.descarga"></script>

    <!-- Script to Activate the Carousel -->
    <script>
        /* Product Slider Codes */
        $(document).ready(function () {
            'use strict';

            $('#prop_slid').bxSlider({
                pagerCustom: '#slid_nav'
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