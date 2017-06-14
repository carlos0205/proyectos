 var popup = null;
		function abrirVentana(idi,codter)
		{
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
			anchoPopup = 620;
			altoPopup = 500;

			// Calculamos las coordenadas de colocación del Popup
			laXPopup = centroAncho - parseInt((anchoPopup/2))
			laYPopup = centroAlto - parseInt((altoPopup/2))
			
			
			pagina = "vacapl.php?codter="+codter
			
			popup = window.open(pagina,"Imagenes","scrollbars=yes,status=no,width=" + anchoPopup + ", height=" + altoPopup + ",left = " + laXPopup + ",top = " + laYPopup);
}
 //<![CDATA[

 // If you don't want to put unstandard properties in your stylesheet, here's yet
 // another means of activating the script. This assumes that you have at least one
 // stylesheet included already. Remove the /* and */ lines to activate.

 /*
 if (document.all && document.styleSheets && document.styleSheets[0] &&
  document.styleSheets[0].addRule)
 {
  // Feel free to add rules for specific tags only, you just have to call it several times.
  document.styleSheets[0].addRule('*', 'behavior: url(iepngfix.htc)');
 }
 */

 //]]>