<?php

function permisos($usu,$prog)
       {
		$enlace=enlace();
		//Consulta para mirar si el usuario tiene permiso en un script
		//$consultaper = "SELECT gp.codprog as prog FROM gruusu gu, progweb pw, usuadm u, gruprog gp WHERE u.codgru = gu.codgru and gu.codgru = gp.codgru and gp.codprog = pw.codprog and u.logusu= '$usu' and pw.nomprog = '$prog'";
		
		$consultaper = "SELECT gp.codprog as prog FROM gruusu gu, progweb pw, usuadm u, gruprog gp 
		WHERE u.codgru = gu.codgru and gu.codgru = gp.codgru and gp.codprog = pw.codprog and u.logusu= '$usu' and pw.nomprog = '$prog'
		UNION
		SELECT gp.codprog as prog FROM gruusu gu, progweb pw, senausuarios u, gruprog gp 
		WHERE gu.codgru = 3 AND gu.codgru = gp.codgru and gp.codprog = pw.codprog and u.logusu= '$usu' and pw.nomprog = '$prog'
		";
		
		
		$resultadoper = mysql_query($consultaper, $enlace);
		$filaper = mysql_fetch_array($resultadoper);
		$numfilasper = mysql_num_rows($resultadoper);
		if ($numfilasper == 0)	
			{
			echo '<script language = JavaScript>
			alert ("No tiene permisos de acceso a este programa, contacte al administractor Web");
			location = "index1.php";
			</script>';
			$permiso = FALSE;
			return $permiso;
			}else{
			$permiso = TRUE;
			return $permiso;
			}

       }

?>