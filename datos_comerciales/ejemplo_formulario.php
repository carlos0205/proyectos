<?php
header('Content-Type: text/html; charset=ISO-8859-1'); 

$current_site  = $_SERVER["SERVER_NAME"]; 
$url_link      = $current_site."/elpais/"; 

$entorno_sitio = ( ( preg_match('/^www(.*)/i', $current_site) ) ? "produccion" : ( ( preg_match("/^contenidos(.*)/i", $current_site) ) ? "contenidos" : ( ( preg_match("/^staging(.*)/i", $current_site) ) ? "staging" :"desarrollo") ));

define('SERVER_ESPECIAL', $entorno_sitio); // produccion

require 'conexion.php';
$fecha = date('Y-m-d');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FORMULARIO</title>
<link href="../css/estilo.css" rel="stylesheet">
<script src="../js/jquery.js"></script>
<script src="../js/myjava.js"></script>
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet">
<link href="../bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.js"></script>
</head>
<body>
    <header>NOTAS INTERNAS</header>
    <section>
    <table border="0" align="center">
    	<tr>
        	<td width="335"><input type="text" placeholder="Busca un producto por: Nombre o Tipo" id="bs-prod"/></td>
            <td><input type="date" id="bd-desde"/></td>
            <td>Hasta&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><input type="date" id="bd-hasta"/></td>
            <td width="100"><button id="nuevo-producto" class="btn btn-primary">Nuevo</button></td>
            <td width="200"><a target="_blank" href="javascript:reportePDF();" class="btn btn-danger">Exportar Busqueda a PDF</a></td>
        </tr>
    </table>
    </section>

    <div class="registros" id="agrega-registros"></div>
    <center>
        <ul class="pagination" id="pagination"></ul>
    </center>
    <!-- MODAL PARA EL REGISTRO DE PRODUCTOS-->
    <div class="modal fade" id="registra-producto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h4 class="modal-title" id="myModalLabel"><b>Registra o Edita un Producto</b></h4>
            </div>
            <form id="formulario" class="formulario" onsubmit="return agregaRegistro();">
            <div class="modal-body">
				<table border="0" width="100%">
               		 <tr>
                        <td colspan="2"><input type="text" required="required" readonly="readonly" id="id-prod" name="id-prod" readonly="readonly" style="visibility:hidden; height:5px;"/></td>
                    </tr>
                     <tr>
                    	
                    </tr>
                    <tr>
                    	<td>Precio Unitario: </td>
                        <td><input type="number" required="required" name="precio-uni" id="precio-uni"/></td>
                    </tr>
                    <tr>
                    	<td>Precio Distribuidor: </td>
                        <td><input type="number"  required="required" name="precio-dis" id="precio-dis"/></td>
                    </tr>
                    <tr>
                    	<td colspan="2">
                        	<div id="mensaje"></div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="modal-footer">
            	<input type="submit" value="Registrar" class="btn btn-success" id="reg"/>
                <input type="submit" value="Editar" class="btn btn-warning"  id="edi"/>
            </div>
            </form>
          </div>
        </div>
      </div>
      

</body>
</html>