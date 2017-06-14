<?php
function menu($idioma){
global $enlace;
	switch($idioma){
		case 1:
		      $menu='<li class="parent;pointer"><a href="../../index.php">INICIO&nbsp;&nbsp;&nbsp;&nbsp;</a></li>
	
	   
	   <li class="parent;pointer"><a href="../../compania.php"><span>&nbsp;&nbsp;COMPAÑIA &nbsp;&nbsp; </span></a></li>';		
		  				
	
	$menu.='	
		
		<li class="parent;pointer"><a href="../../ventas.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VENTAS &nbsp </a>
		</li>
		
		<li class="parent;pointer"><a href="../../alquiler.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ALQUILER &nbsp </a></li>
		
		<li class="parent;pointer"><a href="../../servicios.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; SERVICIOS &nbsp </a></li>
		
		<li class="parent;pointer"><a href="../../script/contacto/publicar.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; PUBLICAR &nbsp </a></li>
		
		<li class="parent;pointer"><a href="../../script/contacto/contact.php">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CONTACTO &nbsp </a></li>
				
       '   ;   
		 
		 break;
		 }
	 echo $menu;
   }
?>