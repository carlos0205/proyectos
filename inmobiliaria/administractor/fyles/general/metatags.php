<?php

//averiguo metatags
$qrymetatag = "SELECT * FROM metatags";
$resmetatag = mysql_query($qrymetatag, $enlace);
$filmetatag = mysql_fetch_assoc($resmetatag);


if($filmetatag["estado"]=="Activo"){
echo'<META NAME="Author" CONTENT="'.$filmetatag["autor"].'">
<META NAME="Subject" CONTENT="'.$filmetatag["tema"].'">
<META NAME="Description" CONTENT="'.$filmetatag["descripcion"].'">
<META NAME="Classification" CONTENT="'.$filmetatag["clasificacion"].'">
<META NAME="Keywords" CONTENT="'.$filmetatag["palabras"].'">
<META NAME="Geography" CONTENT="'.$filmetatag["localidad"].'">
<META NAME="Language" CONTENT="'.$filmetatag["idioma"].'">
<META NAME="Copyright" CONTENT="'.$filmetatag["copyright"].'">
<META NAME="Designer" CONTENT="'.$filmetatag["disennador"].'">
<META NAME="Publisher" CONTENT="'.$filmetatag["publicado"].'">
<META NAME="Revisit" CONTENT="'.$filmetatag["revisitar"].'">
<META NAME="Distribution" CONTENT="'.$filmetatag["distribucion"].'">
<META NAME="Robots" CONTENT="'.$filmetatag["robots"].'">
<META NAME="city" CONTENT="'.$filmetatag["ciudad"].'">
<META NAME="coutry" CONTENT="'.$filmetatag["pais"].'">
<meta http-equiv="Pragma" content="'.$filmetatag["estadocache"].'">
<meta http-equiv="Cache-Control" content="'.$filmetatag["controlcache"].'">
<meta http-equiv="Expires" content="'.$filmetatag["expira"].'">';
}

if($filmetatag["manejagoogle"]=="Si"){

?><script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo $filmetatag["codigogoogle"]?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?php
}
?>