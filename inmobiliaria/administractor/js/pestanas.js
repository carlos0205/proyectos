//Desarrollado por Jesus Liñán
//webmaster@ribosomatic.com
//ribosomatic.com
//Puedes hacer lo que quieras con el código
//pero visita la web cuando te acuerdes
function objetoAjax(){
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
  		}
	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
function MostrarPagina(pagina,boton){
	/////////////////
	var con, preloader;
	cont = document.getElementById('contenido');
	preloader = document.getElementById('preloader');
	
	ajax=objetoAjax();
	ajax.open("POST", pagina, true);
    ajax.onreadystatechange = function(){
			if(ajax.readyState==1){
						ocultacontenido();
                        preloader.innerHTML = "Cargando...";
                        //modificamos el estilo de la div, mostrando una imagen de fondo
                        //preloader.style.background = "url('loading.gif') no-repeat"; 
                }else if(ajax.readyState==4){
                        if(ajax.status==200){
                                //mostramos los datos dentro de la div
                                contenido.innerHTML = ajax.responseText; 
								 muestracontenido();
								 
                        }else if(ajax.status==404){
							   
								ocultacontenido();
                                preloader.innerHTML = "La página no existe";
                        }else{
                                //mostramos el posible error
								ocultacontenido();
                                preloader.innerHTML = "Error:".ajax.status; 
                        }
                }
	}
function muestracontenido(){
	cont.style.display="block";
	preloader.style.display="none";
}

function ocultacontenido(){
	 cont.style.display="none";
	 preloader.style.display="block";
}
	/////////////////
	
	ajax.send(null);
	
	//----------- configuraciones previas -------------//
	
	//definir los titulos de los botones
	titulo=new Array();
	titulo[0]="Informacion Personal";
	titulo[1]="Estudios Realizados";
	titulo[2]="Cursos realizados";
	titulo[3]="Experiencia Laboral";
	titulo[4]="Referecnias personales";

	
	//definir numero de botones
	nrobtn=5;
	
	//definir prefijo de botones
	//(esto con el objetivo de no tener
	//problemas al momento de validar
	//nuestra página.)
	pref="boton_";
	
	//-------------------- fin ------------------------//

	//quita el estilo a todos los botones
	for(i=1;i<=nrobtn;i++){
		tit=titulo[i-1];
		btn=document.getElementById(pref+i);
		btn.innerHTML="<span style=\"border-top:1px #F5F5F5 solid; border-left:1px #F5F5F5 solid; border-right:1px #F5F5F5 solid;	border-bottom:1px #F5F5F5 solid; margin-left:5px; padding-left:2px;padding-right:2px; padding-top:1px; padding-bottom:1px; text-decoration:none; 	background-color:#F5F5F5; color=000000\">"+tit+"</span>";
	}
	//le da estilo al boton actual
	btnA = document.getElementById(pref+boton);
	tit=titulo[boton-1];
	btnA.innerHTML="<span style=\"border-top:1px #339933 solid;	border-left:1px #339933 solid; border-right:1px #339933 solid;	margin-left:5px; padding-left:2px;padding-right:2px; padding-top:1px; padding-bottom:5px; text-decoration:none; 	background-color:#339933; color=000000\">"+tit+"</span>";
}