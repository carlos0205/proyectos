// JavaScript Document
window.addEvent('domready',function(){ 
	
	//=====================================
	//==== SERVICIOS
	
	var fndFx = [];
	var underoverFx = [];
	
	$$('.servicios2 img').each(function(el,i) {
		el.addEvent('click',function(e){
			//alert(el.parentNode.parentNode);
			//document.location.href=el.parentNode.parentNode; 
		});
		underoverFx[i] = new Fx.Morph(el, {duration:250, wait:false,transition:Fx.Transitions.Expo.easeOut}).set({'margin-top':5})
	});
	$$('.servicios2 .item2').each(function(el,i) { 
		fndFx[i] = new Fx.Morph(el, {duration:250, wait:false,transition:Fx.Transitions.Expo.easeOut}).set({'background-color': '#EFEFDE'})
	});
	
	$$('.servicios2 .item2').each(function(el,i) {
		el.addEvents({
			'mouseover':
				function(e){
					underoverFx[i].start({'margin-top':0});
					fndFx[i].start({'background-color': '#EFEFDE'});
				},
			'mouseleave':
				function(e){ 
					underoverFx[i].start({'margin-top':5});
					fndFx[i].start({'background-color': '#EFEFDE'});
				}
		});
	}); 
	
	
})