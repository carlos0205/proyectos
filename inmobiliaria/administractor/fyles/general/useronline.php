<?php
function online(){ 
	$enlace = enlace();
    if (!isset($_SESSION['enlinea']) && !isset($_SESSION['enlineahoj']) && !isset($_SESSION['enlineaest']) && !isset($_SESSION['enlineaemp'])){ 
        $usuario = $_SERVER['REMOTE_ADDR']; 
		$sessionid = session_id();
        $invitado = 'Invitado'; 
    }else{ 
		
		if(isset($_SESSION['enlinea'])){
        $usuario = $_SESSION['enlinea']; 
		$sessionid = session_id();
        $invitado = 'Cliente'; 
		}
		if(isset($_SESSION['enlineahoj'])){
        $usuario = $_SESSION['enlineahoj']; 
		$sessionid = session_id();
        $invitado = 'Hojavida'; 
		}
		if(isset($_SESSION['enlineaest'])){
        $usuario = $_SESSION['enlineaest']; 
		$sessionid = session_id();
        $invitado = 'Estudiante'; 
		}
		if(isset($_SESSION['enlineaemp'])){
        $usuario = $_SESSION['enlineaemp']; 
		$sessionid = session_id();
        $invitado = 'Empleado'; 
		}
    } 
	
	//consulto tiempo de sesion
	$qrytime = "SELECT tiempo FROM sesionest";
	$restime = mysql_query($qrytime, $enlace);
	$filtime = mysql_fetch_assoc($restime);

    $pasado = time()-$filtime["tiempo"];//900 segundos. Cambiar por el plazo que se quiera dar al usuario para realizar alguna acción (recargar por ejemplo). 
    $sql = "DELETE FROM sesiones WHERE tiempo < $pasado"; 
    $result = mysql_query($sql); 
    $sql = "SELECT tiempo FROM sesiones WHERE sessionid='$sessionid' AND codusu='$usuario' AND invitado='$invitado'"; 
    $result = mysql_query($sql,$enlace) or die ("Error en función online (leer) :".mysql_error()); 
    $tiempo = time(); 
    if (mysql_num_rows($result) > 0){ 
        $sql = "UPDATE sesiones SET sessionid='$sessionid', codusu='$usuario', tiempo='$tiempo', invitado='$invitado' WHERE sessionid='$sessionid' AND codusu = '$usuario' AND invitado='$invitado'"; 
    }else{ 
        $sql = "INSERT INTO sesiones (sessionid, codusu, tiempo, invitado) VALUES ('$sessionid','$usuario', '$tiempo', '$invitado' )"; 
    } 
    mysql_query($sql,$enlace) or die("Error en función online (actualizar) :".mysql_error); 
}  
?>