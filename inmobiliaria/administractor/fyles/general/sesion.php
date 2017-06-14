<?

//funcion para validar el tiempo de estancia de un usuario
	function sesion($seccion)
	{	
		$enlace = enlace();
		
		//consulto tiempo de sesion
		$qrytime = "SELECT tiempo FROM sesionest";
		$restime = mysql_query($qrytime, $enlace);
		$filtime = mysql_fetch_assoc($restime);
		
		$sesionactual = session_id();
		$qrysesion = "SELECT sessionid FROM sesiones WHERE sessionid = '$sesionactual'";		
		$ressession = mysql_query($qrysesion, $enlace);
		$numlineas = mysql_num_rows($ressession);
		// 1 administractor, 2 cliente

		if($seccion == 1){
			if(!isset($_SESSION["enlineaadm"])){ // si no existe en lineaadm
			echo '<script language = JavaScript>
					location = "index.html";
					</script>';
			}else{
				
				$tiempotranscurrido=(strtotime(date("Y-n-j H:i:s"))-strtotime(date($_SESSION["ultimoacceso"])));
				if ($tiempotranscurrido>$filtime["tiempo"] || $_SESSION["enlineaadm"]==NULL || $numlineas == 0 )
				{
						if ($tiempotranscurrido>$filtime["tiempo"]){	
							$codusu = $_SESSION["enlineaadm"];
							$qrysesion = "DELETE FROM sesiones WHERE codusu = $codusu AND invitado = 'administractor'";
							$ressesion = mysql_query($qrysesion, $enlace);
						}
					//session_destroy();
					session_unregister("ultimoacceso");
					session_unregister("enlineaadm");
					session_unregister("docente");
					session_unregister("empresa");
					echo '<script language = JavaScript>
						location = "index.html";
						</script>';
					exit;
					return false;
				}
				else
				{
					$tiempo = time(); 
					$usuarioadm = 'administractor';
					$_SESSION["ultimoacceso"]=date("Y-n-j H:i:s");
					
					 $sql = "UPDATE sesiones SET  codusu='".$_SESSION['enlineaadm']."', tiempo='$tiempo', invitado='$usuarioadm' WHERE codusu='".$_SESSION['enlineaadm']."' AND invitado='$usuarioadm'"; 
					 mysql_query($sql,$enlace);
					return true;
				}
			}//fin si enlinea
		}else{
		if(!isset($_SESSION["enlinea"])){ // si no existe enlinea
			echo '<script language = JavaScript>
					location = "index.php";
					</script>';
			}else{
			
				$tiempotranscurrido=(strtotime(date("Y-n-j H:i:s"))-strtotime(date($_SESSION["ultimoaccesocli"])));
				if ($tiempotranscurrido>$filtime["tiempo"] || $_SESSION["enlinea"]==NULL || $numlineas == 0)
				{
						if ($tiempotranscurrido>$filtime["tiempo"]){	
							$codusu = $_SESSION["enlinea"];
							$qrysesion = "DELETE FROM sesiones WHERE codusu = $codusu AND invitado <> 'administractor'";
							$ressesion = mysql_query($qrysesion, $enlace);
						}
					//session_destroy();
					session_unregister("ultimoaccesocli");
					session_unregister("enlinea");
					echo '<script language = JavaScript>
						location = "index.php";
						</script>';
					exit;
					return false;
				}
				else
				{
					$tiempo = time(); 
					$usuarioadm = 'administractor';
					$_SESSION["ultimoaccesocli"]=date("Y-n-j H:i:s");
					
					 $sql = "UPDATE sesiones SET  codusu='".$_SESSION['enlinea']."', tiempo='$tiempo' WHERE codusu='".$_SESSION['enline']."' AND invitado<>'$usuarioadm'"; 
					 mysql_query($sql,$enlace);
					return true;
				}
			}//fin si enlinea
		
		}
	}
	function destruyesesiones($excepto){
	$sesiones = array("enlineaadm", "usuario", "grupo", "ultimoacceso" ,"empresa", "docente", "consulta","logueado",$excepto);
	do{
		if (!in_array(key($_SESSION),$sesiones)){
			//echo key($_SESSION);
			//echo current($_SESSION);
			session_unregister(key($_SESSION));
		}
	} while(next($_SESSION));
	}
?>
