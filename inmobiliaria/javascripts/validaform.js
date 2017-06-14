// JavaScript Document
var isIE = document.all?true:false;
var isNS = document.layers?true:false;

function onlyDigits(e,decReq) {

var key = (isIE) ? window.event.keyCode : e.which;
var obj = (isIE) ? event.srcElement : e.target;

var isNum = (key > 47 && key < 58 ||  key == 8 ||  key == 0) ? true:false;

var dotOK = (key==46 && decReq=='decOK' && (obj.value.indexOf(".")<0 || obj.value.length==0)) ? true:false;

if(!isNum && !dotOK && !isIE ){
	e.preventDefault();
	window.event.keyCode=0;
}
window.event.keyCode = (!isNum && !dotOK && isIE) ? 0:key;

//e.which = (!isNum && !dotOK && isNS) ? 0:key;


return (isNum || dotOK);
}

//////////////////////////////////////////

function valida_numerico(n,n1, campo)
{
	if (n==""){
			alert(n1+' no puede estar vacio')
			eval("document.form1."+campo+".focus()");
			return false
		}
	else if (isNaN(n)==true)
	{
		alert(n1+' es de tipo numérico')
		eval("document.form1."+campo+".focus()");
		return false
	}
}
function valida_email(n,n1,campo)
{
	var b=/^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/      
        //devuelve verdadero si validacion OK, y falso en caso contrario
        if (b.test(n)==false)
		{
			alert(n1+' tiene un formato no válido');
			eval("document.form1."+campo+".focus()");
			return false
			}
}
function valida_texto(n,n1,campo)
{
		if (n==""){
			alert(n1+' no puede estar vacio')			
			eval("document.form1."+campo+".focus()");
			return false
		}		
}

function valida_fecha(n,n1){
	var Fecha= new String(n)	// Crea un string
	var RealFecha= new Date()	// Para sacar la fecha de hoy
	// Cadena Dia
	var Dia= new String(Fecha.substring(Fecha.lastIndexOf("-")+1,Fecha.length))
	// Cadena Mes
	var Mes= new String(Fecha.substring(Fecha.indexOf("-")+1,Fecha.lastIndexOf("-")))
	// Cadena Año
	var Ano= new String(Fecha.substring(0,Fecha.indexOf("-")))

	// Valido el año
	if (isNaN(Ano) || Ano.length<4 || parseFloat(Ano)<1900){
        	alert(n1+' Año inválido')
		return false
	}
	// Valido el Mes
	if (isNaN(Mes) || parseFloat(Mes)<1 || parseFloat(Mes)>12){
		alert(n1+' Mes inválido')
		return false
	}
	// Valido el Dia
	if (isNaN(Dia) || parseInt(Dia)<1 || parseInt(Dia)>31){
		alert(n1+' Día inválido')
		return false
	}
	if (Mes==4 || Mes==6 || Mes==9 || Mes==11 || Mes==2) {
		if (Mes==2 && Dia > 28 || Dia>30) {
			alert(n1+' Día inválido')
			return false
		}
	}
}

////////////////
function validaenvia(){

var elementos = document.form1.elements.length;
//valida 1= no , 2=si
	for(i=0;i<elementos;i++){
	var campo = document.form1.elements[i].id;
		tipocampo = campo.substr(0,3)
		valida = campo.substr(3,1);
	
		//averiguo si es campo que se debe verificar
		if(valida == 2){
			//averiguo tipo de campo a verificar
			switch(tipocampo){
			case "txt":
				
				esemail = campo.substr(4,3);	
			
				if(esemail == "ema"){
					if(valida_email(eval("document.form1."+campo+".value"),'El campo " '+eval("document.form1."+campo+".title")+' "', campo)==false ){return false}; 
					
				}else{
					
					if(valida_texto(eval("document.form1."+campo+".value"),'El campo " '+eval("document.form1."+campo+".title")+' "', campo)==false ){return false};
					
				}
			
			break;
			
			case "cbo":
			
				if(eval("document.form1."+campo+".value") == 0){
					alert("Por favor seleccione ' "+eval("document.form1."+campo+".title")+" '");
					eval("document.form1."+campo+".focus()");
					return false;
				}
			break;
			
			case "opt":
				alert(campo);
				selecciono=0;
				for(i=0; i <=eval("document.form1."+campo+".length"); i++){
					if(eval("document.form1.campo["+i+"].checked"));
					{
					 selecciono++;
					}
				}
	
				if(selecciono==0){
					alert("Por favor seleccione ' "+eval("document.form1."+campo+".title")+" '");
					return false;
				}
					
			break;
			
			case "chk":
			
			break;
			
			case "hid":
			
				if(eval("document.form1."+campo+".value") == 0){
						alert("Por favor seleccione ' "+eval("document.form1."+campo+".title")+" '");
						return false;
					}
			break;
			}
			
		}
	
	}

}

function edita(script,codigo){
	window.location=''+script+'edi.php?cod='+codigo+''
}

function consulta(script,codigo,accion){
	window.location=''+script+'ver.php?cod='+codigo+'&acc='+accion+''
}

function crea(script,codigo){
	window.location=''+script+'?cod='+codigo+''
}

var popup = null;
function erroreseliminacion(seccion){
	// Si el popup ya existe lo cerramos
	if(popup!=null)
		popup.close();

		// Capturamos las dimensiones de la pantalla para centrar el popup
		altoPantalla = parseInt(screen.availHeight);
		anchoPantalla = parseInt(screen.availWidth);
			
		// Calculamos el centro de la pantalla
		centroAncho = parseInt((anchoPantalla/2))
		centroAlto = parseInt((altoPantalla/2))
	
		// dimensiones del popup
		anchoPopup = 1000;
		altoPopup = 600;

		// Calculamos las coordenadas de colocación del Popup
		laXPopup = centroAncho - parseInt((anchoPopup/2))
		laYPopup = centroAlto - parseInt((altoPopup/2))
			
		// Definimos que página vamos a ver
		pagina = "erroreseliminacion.php?seccion="+seccion+"";
			
		popup = window.open(pagina,"Imagenes","scrollbars=no,status=no,width=" + anchoPopup + ", height=" + altoPopup + ",left = " + laXPopup + ",top = " + laYPopup);
	}