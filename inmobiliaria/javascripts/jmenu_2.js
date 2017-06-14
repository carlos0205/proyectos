Fx.Height = Fx.Style.extend({initialize: function(el, options){$(el).setStyle('overflow', 'hidden');this.parent(el, 'height', options);},
toggle: function(){var style = this.element.getStyle('height').toInt();
return (style > 0) ? this.start(style, 0) : this.start(0, this.element.scrollHeight);},
show: function(){return this.set(this.element.scrollHeight);}});
Fx.Width = Fx.Style.extend({initialize: function(el, options){this.element = $(el);this.element.setStyle('overflow', 'hidden');this.iniWidth = this.element.getStyle('width').toInt();this.parent(this.element, 'width', options);},
toggle: function(){var style = this.element.getStyle('width').toInt(); return (style > 0) ? this.start(style, 0) : this.start(0, this.iniWidth);},
show: function(){return this.set(this.iniWidth);}});
Fx.Opacity = Fx.Style.extend({initialize: function(el, options){this.now = 1;this.parent(el, 'opacity', options);},
toggle: function(){return (this.now > 0) ? this.start(1, 0) : this.start(0, 1);},
show: function(){return this.set(1);}});

/**
	MooMenu 
**/

window.addEvent('domready', function(){

	var main = $("horiz-menu");
	
	levels = new Array();
	effects2 = new Array();
	
	main.getChildren().each(function(el,i){
		levels[i] = new Array();
		effects2[i] = new Array();
		
		el.getElementsBySelector("ul").each(function(elm,j){
			levels[i][j] = elm.getParent();
			effects2[i][j] = new Fx.Height(elm,{duration: 250});
			effects2[i][j].set(0);
		});
	});
	
	
	levels.each(function(e,k){
		e.each(function(a,l){
			a.addEvent("mouseenter",function(){
				a.getChildren()[1].setStyle("overflow","hidden");
				effects2[k][l].toggle();
				(function(){a.getChildren()[1].setStyle("overflow","")}).delay(500);
			});
			
			a.addEvent("mouseleave",function(){
				a.getChildren()[1].setStyle("overflow","hidden");
				effects2[k][l].stop();
				effects2[k][l].set(0);
			});
		});
	});
});