<?php 
	session_start();

	header("Content-type: application/x-msdownload"); 
	header("Content-Disposition: attachment; filename=extraction.xls");
	header("Pragma: no-cache"); 
	header("Expires: 0"); 
	
	require 'conexion.php';
	$enlace=enlace();
	
	$select = $_SESSION["consulta"];                 
	$export = mysql_query($select, $enlace); 
	$fields = mysql_num_fields($export); 
	
	$linea="";
	$data="";
	$siguiente="";
	for ($i = 0; $i < $fields; $i++) 
	{ 
		$header = mysql_field_name($export, $i) . "\t";  //trae el nombre del campo
		$siguiente.=$header;
	} 
		
		$linea .= $siguiente;
	 
	while($row = mysql_fetch_row($export)) { 
		$line = ''; 
		foreach($row as $value) 
		{                                             
			if ((!isset($value)) OR ($value == "")) 
			{ 
				$value = "\t"; //  \t asigna tabulacion
			} 
			else 
			{ 
				$value = str_replace('"', '""', $value); 
				$value = '"' . $value . '"' . "\t"; 
			} 
			$line .= $value; 
		} 
		
		$data .= trim($line) . "\n";// \n salto de linea
		} 
		
	$data = str_replace("\r","",$data); 
	
	if ($data == "")
	{ 
		$data = "\n(0) Records Found!\n";                         
	} 
	
	print "$linea\n$data"; 

?> 
