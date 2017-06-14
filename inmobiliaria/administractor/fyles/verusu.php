<?	
	session_start();

	include("general/sesion.php"); //incluimos en todas la paginas debe ir
	require 'general/conexion.php';

	//online();	
	$enlace=enlace();
	
	//elimino sesiones viejas//consulto tiempo de sesion
	$qrytime = "SELECT tiempo FROM sesionest";
	$restime = mysql_query($qrytime, $enlace);
	$filtime = mysql_fetch_assoc($restime);
	
	$pasado = time()-$filtime["tiempo"];
    $sql = "DELETE FROM sesiones WHERE tiempo < $pasado"; 
    $result = mysql_query($sql); 
	///////////////7

	$usu = $_POST["txtusu"];
	$con = $_POST["txtcon"];
	$contrasena = md5 ($con); 
	
	if($_POST["seltip"]=="Admin"){
	
		/*Conformación y ejecución de la consulta*/
		$qryusuadm = "SELECT u.*, g.nomgru FROM usuadm AS u
		INNER JOIN gruusu AS g
		ON u.codgru = g.codgru 
		WHERE logusu='$usu' AND pasusu='$contrasena' ";
		$resusuadm = mysql_query($qryusuadm, $enlace);
		/*Número de registros que arrojó la consulta*/
		$numfilusuadm = mysql_num_rows($resusuadm);
		
		if ($numfilusuadm > 0)
		{	/*Recorrido de cada campo de la consulta*/
			$filusuadm = mysql_fetch_assoc($resusuadm);
			
			if ($filusuadm["estusu"]=="Activo"){
				//verifico que no haya iniciado sesion
				$codusu = $filusuadm["codusuadm"];
				
				$qryseseli = "DELETE FROM sesiones WHERE codusu=$codusu AND invitado = 'administractor' ";
				$resseseli = mysql_query($qryseseli, $enlace);
				
				$tiempo = time();
				
				$sessionid = session_id();
				$sql = "INSERT INTO sesiones (sessionid, codusu, tiempo, invitado) VALUES ('$sessionid', '".$filusuadm["codusuadm"]."', '$tiempo', 'administractor' )"; 
				mysql_query($sql,$enlace);
	
				$_SESSION["ultimoacceso"]=date("Y-n-j H:i:s");
				$fecha = date("Y-n-j H:i:s");
					
				$_SESSION["enlineaadm"]= $filusuadm["codusuadm"];
				$_SESSION["usuario"]= $filusuadm["logusu"];
				$_SESSION["grupo"] =$filusuadm["codgru"];
				$_SESSION["logueado"] =$filusuadm["nomusu"];
				
				auditoria($_SESSION["enlineaadm"],'','','1');
				
				$qryvis = "UPDATE usuadm SET ultvis = '$fecha' WHERE codusuadm = '$codusu'";
				$resvis = mysql_query($qryvis, $enlace);
				
				echo '<script language = JavaScript>
				location = "index1.php";	
				</script>';
			}else{
				echo '<script language = JavaScript>
				alert("El usuario se encuentra bloqueado");
				location = "index.html";	
				</script>';
			}
		}else{	
			echo '<script language = JavaScript>
			location = "index.html";
			</script>';
			session_destroy();
		}
	}else{
	
		/*Conformación y ejecución de la consulta*/
		$qryusudoc = "SELECT * FROM senausuarios WHERE logusu='$usu' AND pasusu='$contrasena' AND tipo='Docente' ";
		$resusudoc = mysql_query($qryusudoc, $enlace);
		/*Número de registros que arrojó la consulta*/
		$numfilusudoc = mysql_num_rows($resusudoc);
		
		if ($numfilusudoc > 0)
		{	/*Recorrido de cada campo de la consulta*/
			$filusudoc = mysql_fetch_assoc($resusudoc);
			
			if ($filusudoc["estusu"]=="Activo"){
			//verifico que no haya iniciado sesion
				$codusu = $filusudoc["codusu"];
				
				$qryseseli = "DELETE FROM sesiones WHERE codusu=$codusu AND invitado = 'Docente' ";
				$resseseli = mysql_query($qryseseli, $enlace);
				
				$tiempo = time();
				
				$sessionid = session_id();
				$sql = "INSERT INTO sesiones (sessionid, codusu, tiempo, invitado) VALUES ('$sessionid', '$codusu', '$tiempo', 'Docente' )"; 
				mysql_query($sql,$enlace);
				
					$_SESSION["ultimoacceso"]=date("Y-n-j H:i:s");
					$fecha = date("Y-n-j H:i:s");
					
					$_SESSION["enlineaadm"]= $filusudoc["codusu"];
					$_SESSION["docente"]= $filusudoc["codusu"];
					$_SESSION["usuario"]= $filusudoc["logusu"];
					$_SESSION["grupo"] = 3;
					$_SESSION["logueado"] =$filusuadm["nomusu"];
					
					auditoria($_SESSION["enlineaadm"],'','','1');

					echo '<script language = JavaScript>
					location = "index1.php";	
					</script>';
			
			}else{
				echo '<script language = JavaScript>
				alert("El usuario se encuentra bloqueado");
				location = "index.html";	
				</script>';
			}
		}
		else
		{	
			echo '<script language = JavaScript>
			location = "index.html";
			</script>';
			session_destroy();
		}
	}
?>
</body>
</html>