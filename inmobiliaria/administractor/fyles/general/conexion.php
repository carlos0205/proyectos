<?php

function enlace(){
              
			 /*  if (!($enlace=mysql_pconnect("localhost","establecer_2014","establecer$20140977")))
               {
                       echo "Error(874):No se puede conectar con el servidor Mysql";
                       exit();
               }
               if (!mysql_select_db("establecer",$enlace))
               {
                       echo "Error(432): Seleccionando la base de datos";
                       exit();
               }*/
			   
               if (!($enlace=mysql_pconnect("localhost","root","root")))
               {
                       echo "Error(874):No se puede conectar con el servidor Mysql";
                       exit();
               }
               if (!mysql_select_db("inmobiliaria",$enlace))
               {
                       echo "Error(432): Seleccionando la base de datos";
                       exit();
               }
               return $enlace;
       }
	   
function desconectar(){
	mysql_close();
}


//auditoria de cambios en el sistema
function auditoria($usuario, $tabla, $registro, $accion){

$enlace = enlace();
$fecha = date("Y-n-j H:i:s");

$qryaud = "INSERT INTO tblusuariosauditoria VALUES('0', '$usuario','$fecha', '$tabla', '$registro', '$accion' )";
$resaud = mysql_query($qryaud, $enlace);

}


//validacion de usuario	
function validacliente($usu, $contrasena, $idioma, $seccion){
				
	$enlace = enlace();
	/*Conformación y ejecución de la consulta*/
	$qryusucli = "SELECT * FROM usutercli WHERE logusu='$usu' AND pasusu='$contrasena' ";
	$resusucli = mysql_query($qryusucli, $enlace);
	/*Número de registros que arrojó la consulta*/
	$numfilusucli = mysql_num_rows($resusucli);
	
	if ($numfilusucli > 0)
	{	/*Recorrido de cada campo de la consulta*/
		$filusucli = mysql_fetch_assoc($resusucli);
		
		if ($filusucli["estusu"]=='Activo'){
				$codusu = $filusucli["codter"];
			    if($seccion == 1){
					$_SESSION["enlinea"]= $filusucli["codter"];
					$qryseseli = "DELETE FROM sesiones WHERE codusu=$codusu AND invitado = 'Cliente'";
					$resseseli = mysql_query($qryseseli, $enlace);
					
				}else{
					$_SESSION["enlineahoj"]= $filusucli["codter"];
					$qryseseli = "DELETE FROM sesiones WHERE codusu=$codusu AND invitado = 'Hojavida'";
					$resseseli = mysql_query($qryseseli, $enlace);
				}
				
				$_SESSION["ultimoaccesocli"]=date("Y-n-j H:i:s");
				$fecha = date("Y-n-j H:i:s");
				
				//actualizo fecha de ultima visita
				$qryvis = "UPDATE usutercli SET ultvis = '$fecha' WHERE codusucli = '$codusu'";
				$resvis = mysql_query($qryvis, $enlace);
				
				//inserto visita
				$qryvis = "INSERT INTO vister values ('0', '$fecha', '$codusu')";
				$resvis = mysql_query($qryvis, $enlace);
				?>
				<script language = JavaScript>
				location = "index.php?cod=1";	
				</script>';
				<?

		}else{
		?>
			<script language = "JavaScript" type="text/javascript">
			var idioma = <?php echo $idioma?>;
			switch(idioma){
				case 1:
				alert("El usuario se encuentra bloqueado");
				break;
				
				case 2:
				alert ("The usuer is looked");
				break;
			}
			</script>
		<?
		}
	}else{	
		?>
		<script language = "JavaScript" type="text/javascript">
		location = "index.php?idi=<?php echo $idioma?>";	
		</script>';
		<?
		$sesiones = array("enlineaadm", "usuario", "grupo", "ultimoacceso", "empresa", "pais", "docente");
		
		do{
			if (!in_array(key($_SESSION),$sesiones)){
				session_unregister(key($_SESSION));
			}
		} while(next($_SESSION));
	}
}


//validacion de usuario	
function validaestudiante($usu, $contrasena, $idioma){
	
				
	$enlace = enlace();
	
	$qrytime = "SELECT tiempo FROM sesionest";
	$restime = mysql_query($qrytime, $enlace);
	$filtime = mysql_fetch_assoc($restime);
	
	$pasado = time()-$filtime["tiempo"];
    $sql = "DELETE FROM sesiones WHERE tiempo < $pasado"; 
    $result = mysql_query($sql,$enlace); 
	
	/*Conformación y ejecución de la consulta*/
	$qryusuest = "SELECT * FROM senausuarios WHERE logusu='$usu' AND pasusu='$contrasena' ";
	$resusuest = mysql_query($qryusuest, $enlace);
	/*Número de registros que arrojó la consulta*/
	$numfilusuest = mysql_num_rows($resusuest);
	
	if ($numfilusuest > 0)
	{	/*Recorrido de cada campo de la consulta*/
		$filusuest = mysql_fetch_assoc($resusuest);
		
		if ($filusuest["estusu"]=="Activo"){
		//verifico que no haya iniciado sesion
				$codusu = $filusuest["codusu"];
				
				$qryseseli = "DELETE FROM sesiones WHERE codusu=$codusu AND invitado = 'Estudiante'";
				$resseseli = mysql_query($qryseseli, $enlace);
			
				$_SESSION["enlineaest"]= $filusuest["codusu"];	
				$_SESSION["nomest"]=$filusuest["nomusu"];	
				$_SESSION["ultimoaccesocli"]=date("Y-n-j H:i:s");
				$fecha = date("Y-n-j H:i:s");
				
				//actualizo fecha de ultima visita
				$qryvis = "UPDATE senausuarios SET ultvis = '$fecha' WHERE codusu = '$codusu'";
				$resvis = mysql_query($qryvis, $enlace);

				?>
				<script language = JavaScript>
				location = "index.php";	
				</script>';
				<?

		}else{
		?>
			<script language = "JavaScript" type="text/javascript">
			var idioma = <?php echo $idioma?>;
			switch(idioma){
				case 1:
				alert("El usuario se encuentra bloqueado");
				break;
				
				case 2:
				alert ("The usuer is looked");
				break;
			}
			</script>
		<?
		}
	}else{	
		?>
		<script language = "JavaScript" type="text/javascript">
		window.location = "index.php";	
		</script>';
		<?
		
		$sesiones = array("enlineaadm", "usuario", "grupo", "ultimoacceso", "empresa", "pais", "docente");
		
		do{
			if (!in_array(key($_SESSION),$sesiones)){
				session_unregister(key($_SESSION));
			}
		} while(next($_SESSION));
	}
}


//validacion de usuario	
function validaempleado($usu, $contrasena, $idioma){
	
				
	$enlace = enlace();
	
	$qrytime = "SELECT tiempo FROM sesionest";
	$restime = mysql_query($qrytime, $enlace);
	$filtime = mysql_fetch_assoc($restime);
	
	$pasado = time()-$filtime["tiempo"];
    $sql = "DELETE FROM sesiones WHERE tiempo < $pasado"; 
    $result = mysql_query($sql,$enlace); 
	
	/*Conformación y ejecución de la consulta*/
	$qryusemp = "SELECT * FROM empleados WHERE logusu='$usu' AND pasusu='$contrasena' ";
	$resusemp = mysql_query($qryusemp, $enlace);
	/*Número de registros que arrojó la consulta*/
	$numfilusemp = mysql_num_rows($resusemp);
	
	if ($numfilusemp > 0)
	{	/*Recorrido de cada campo de la consulta*/
		$filusemp = mysql_fetch_assoc($resusemp);
		
		if ($filusemp["estusu"]=="Activo"){
		//verifico que no haya iniciado sesion

				$codusu = $filusemp["codusuemp"];
				
				$qryseseli = "DELETE FROM sesiones WHERE codusu=$codusu AND invitado = 'Empleado'";
				$resseseli = mysql_query($qryseseli, $enlace);
			
				$_SESSION["enlineaemp"]= $filusemp["codusuemp"];	
				$_SESSION["nomest"]=$filusemp["nomusu"];
				//$_SESSION["curso"]=$filusemp["codcurso"];		
				$_SESSION["ultimoaccesocli"]=date("Y-n-j H:i:s");
				$fecha = date("Y-n-j H:i:s");
				
				//actualizo fecha de ultima visita
				$qryvis = "UPDATE empleados SET ultvis = '$fecha' WHERE codusuemp = '$codusu'";
				$resvis = mysql_query($qryvis, $enlace);

				?>
				<script language = JavaScript>
				location = "empleadosenlinea.php";	
				</script>';
				<?
		
			
		}else{
		?>
			<script language = "JavaScript" type="text/javascript">
			var idioma = <?php echo $idioma?>;
			switch(idioma){
				case 1:
				alert("El usuario se encuentra bloqueado");
				break;
				
				case 2:
				alert ("The usuer is looked");
				break;
			}
			</script>
		<?
		}
	}else{	
		?>
		<script language = "JavaScript" type="text/javascript">
		window.location = "index.php";	
		</script>';
		<?
		
		$sesiones = array("enlineaadm", "usuario", "grupo", "ultimoacceso", "empresa", "pais", "docente", "logueado");
		
		do{
			if (!in_array(key($_SESSION),$sesiones)){
				session_unregister(key($_SESSION));
			}
		} while(next($_SESSION));
	}
}
?>