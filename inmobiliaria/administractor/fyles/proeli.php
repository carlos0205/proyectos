<?php
	session_start();
	include("general/conexion.php") ;
	include("general/sesion.php");
	sesion(1);

	
	// fucion validar permisos de acceso a programa
	require 'general/permisos.php';
	$prog = 'proeli.php';
	$usu = $_SESSION["usuario"];
	$permiso =  permisos($usu, $prog);
	if($permiso){
	
		$enlace = enlace();
			
		function array_recibe($url_codreg) { 
			$tmp = stripslashes($url_codreg); 
			$tmp = urldecode($tmp); 
			$tmp = unserialize($tmp); 
			return $tmp; 
		} 
		
		$codreg=$_GET['codreg']; 	
		$codreg=array_recibe($codreg); 
		
		
		//elimino productos y demas asociados al producto
		$qrypro = "SELECT codpro, imgpro FROM pro WHERE codpro IN (".implode(',',$codreg).")";
		$respro =mysql_query($qrypro,$enlace);	
	
		$contador = 0;
	
		while ($filpro = mysql_fetch_array($respro)){
			
			$codreg = $filpro["codpro"];
			
			//averiguo si producto pertenece a ofertas o colecciones
			$qrycol="SELECT codcol FROM colpro WHERE codpro = '$codreg'";
			$rescol=mysql_query($qrycol, $enlace);
			$numcol = mysql_num_rows($rescol);
			
			if( $numcol > 0){
				$contador ++;
			}else{
			
				//elimino colores de producto
				$qrycoleli = "DELETE FROM procol WHERE codpro = '$codreg' ";
				$rescoleli = mysql_query($qrycoleli, $enlace);
				
				//elimino manuales de producto
					$qryman = "SELECT docman FROM proman WHERE codpro= '$codreg' ";
					$resman = mysql_query($qryman, $enlace);
					$numman = mysql_num_rows($resman);
					if ($numman > 0){
						while($filman = mysql_fetch_assoc($resman)){
							$ruta="../productos/manuales/".$filman["docman"];
							unlink ($ruta);		
						}
					$qrymaneli = "DELETE FROM proman WHERE codpro = '$codreg' ";
					$resmaneli = mysql_query($qrymaneli, $enlace);	
					}
				
				//elimino pintas de producto
					$qrypin = "SELECT imgpropin, imgpropinmini FROM propin WHERE codpro= '$codreg' ";
					$respin = mysql_query($qrypin, $enlace);
					$numpin = mysql_num_rows($respin);
					if ($numpin > 0){
						while($filpin = mysql_fetch_assoc($respin)){
							$ruta="../productos/pintas/".$filpin["imgpropin"];	
							$ruta1="../productos/pintas/mini/".$filpin["imgpropin"];
							$ruta2="../productos/pintas/minisec/".$filpin["imgpropinmini"];
							unlink ($ruta);	
							unlink ($ruta1);	
							unlink ($ruta2);	
						}
					$qrypineli = "DELETE FROM propin WHERE codpro = '$codreg' ";
					$respineli = mysql_query($qrypineli, $enlace);	
					}
				
				//elimino vistas de producto
					$qryvis = "SELECT imgpro FROM provis WHERE codpro = '$codreg' ";
					$resvis = mysql_query($qryvis, $enlace);
					$numvis = mysql_num_rows($resvis);
					if ($numvis > 0){
						while($filvis = mysql_fetch_assoc($resvis)){
							$ruta="../productos/vistas/".$filvis["imgpro"];
							$ruta1 = "../productos/vistas/mini/".$filvis["imgpro"];
							unlink ($ruta);		
							unlink ($ruta1);	
						}
					$qryviseli = "DELETE FROM provis WHERE codreg = '$codreg' ";
					$resviseli = mysql_query($qryviseli, $enlace);	
					}
			
				//elimino imagen princial de producto
				$ruta1="../productos/".$filpro["imgpro"];
				unlink ($ruta1);
			
				$ruta2="../productos/mini/".$filpro["imgpro"];
				unlink ($ruta2);
				
				$qryproeli = "DELETE FROM pro WHERE codpro='$codreg'";
				$resproeli =mysql_query($qryproeli,$enlace);	
				
				$qryproeli = "DELETE FROM prodet WHERE codpro='$codreg'";
				$resproeli =mysql_query($qryproeli,$enlace);	
				
				auditoria($_SESSION["enlineaadm"],'Productos',$codreg,'4');
			
				}//fin pregunta
	
			} //fin while
			
		mysql_free_result($respro);
		
		if ($contador > 0){
		echo '<script language = JavaScript>
		alert("No se eliminaron algunos productos ya que se encuestran vinculados a colecciones ó pedidos y compras");
		location = "pro.php";
		</script>';
		}else{
		echo '<script language = JavaScript>
		location = "pro.php";
		</script>';
		}
		
}
	
?>
