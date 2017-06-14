capaActual = "ultnoticias";
function esActivo(elemento)	{
	return;
}

function selecciona(elemento)	{
	if (elemento.split("_")[1] == capaActual)	esActivo(elemento);
	else	{
		document.getElementById("pesta_" + capaActual).className = "pesta inactiva";
		document.getElementById("capa_" + capaActual).className = "capa invisible";
		capaActual = elemento.split("_")[1];
		document.getElementById("pesta_" + capaActual).className = "pesta activa";
		document.getElementById("capa_" + capaActual).className = "capa visible";
	}
}

function alturaContenido()	{
	var altura = document.body.offsetHeight;
	var alturaPestas = document.getElementById("pestas").offsetTop;
	var tamPestas = document.getElementById("pestas").offsetHeight;
	
	document.getElementById("contenido").style.height = (altura - (alturaPestas + tamPestas)) + "px";
}
