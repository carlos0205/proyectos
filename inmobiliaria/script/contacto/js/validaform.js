// JavaScript Document
var isIE = document.all?true:false;
var isNS = document.layers?true:false;

function onlyDigits(e,decReq) {

var key = (isIE) ? window.event.keyCode : e.which;
var obj = (isIE) ? event.srcElement : e.target;

var isNum = (key > 47 && key < 58 ||  key == 8 ||  key == 0) ? true:false;

var dotOK = (key==46 && decReq=='decOK' && (obj.value.indexOf(".")<0 || obj.value.length==0)) ? true:false;

if(!isNum && !dotOK ){
	e.preventDefault();
	//window.event.keyCode=0;
}
window.event.keyCode = (!isNum && !dotOK && isIE) ? 0:key;

//e.which = (!isNum && !dotOK && isNS) ? 0:key;


return (isNum || dotOK);
}
//////////////////////////////////////////

function valida_numerico(n,n1,idioma)
{
	if (n==""){
			switch(idioma){
				case 1:
				alert(n1+' no puede estar vacio')
				break;
				
				case 2:
				alert(n1+' can´t be null')
				break;
			}
			return false
		}
	else if (isNaN(n)==true)
	{
		switch(idioma){
		case 1:
		alert(n1+' es de tipo numérico')
		break;
		
		case 2:
		alert(n1+' only numeric')
		break;
		
		}
		return false
	}
}
function valida_email(n,n1,idioma)
{
	var b=/^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/      
        //devuelve verdadero si validacion OK, y falso en caso contrario
        if (b.test(n)==false)
		{
			switch(idioma){
			case 1:
			alert(n1+' tiene un formato no válido')
			break;
			
			case 2:
			alert(n1+' has invalid format')
			break;
			
			}
			return false
			}
}
function valida_texto(n,n1,idioma)
{
		if (n==""){
			switch(idioma){
				case 1:
				alert(n1+' no puede estar vacio')
				break;
				
				case 2:
				alert(n1+' can´t be null')
				break;
			}			
			//document.form1.txtnom.focus();
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
