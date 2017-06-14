<script language="javascript" type="text/javascript">


function valida_emailidi(n,n1,campo)
{
	var b=/^[^@\s]+@[^@\.\s]+(\.[^@\.\s]+)+$/      
        //devuelve verdadero si validacion OK, y falso en caso contrario
        if (b.test(n)==false)
		{
			alert(n1+' <?php echo $filetiqueta["formatoinvalido"]?>');
			eval("document.form1."+campo+".focus()");
			return false
			}
}
function valida_textoidi(n,n1,campo)
{
		if (n==""){
			alert(n1+'  <?php echo $filetiqueta["novacio"]?>')			
			eval("document.form1."+campo+".focus()");
			return false
		}		
}

////////////////
function validaenviaidi(){

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
					if(valida_emailidi(eval("document.form1."+campo+".value"),'  <?php echo $filetiqueta["elcampo"] ?> " '+eval("document.form1."+campo+".title")+' "', campo)==false ){return false}; 
					
				}else{
					
					if(valida_textoidi(eval("document.form1."+campo+".value"),' <?php echo $filetiqueta["elcampo"] ?> " '+eval("document.form1."+campo+".title")+' "', campo)==false ){return false};
					
				}
			
			break;
			
			case "cbo":
			
				if(eval("document.form1."+campo+".value") == 0){
					alert(" <?php echo $filetiqueta["porfavorseleccione"] ?> ' "+eval("document.form1."+campo+".title")+" '");
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
					alert("<?php echo $filetiqueta["porfavorseleccione"] ?> ' "+eval("document.form1."+campo+".title")+" '");
					return false;
				}
					
			break;
			
			case "chk":
			
			break;
			
			case "hid":
			
				if(eval("document.form1."+campo+".value") == 0){
						alert("<?php echo $filetiqueta["porfavorseleccione"] ?> ' "+eval("document.form1."+campo+".title")+" '");
						return false;
					}
			break;
			
			case "img":
				if(valida_textoidi(eval("document.form1."+campo+".value"),'<?php echo $filetiqueta["elcampo"] ?> " '+eval("document.form1."+campo+".title")+' "', campo)==false ){return false};
			break;
			
			}
			
			
		}
	
	}

}


</script>
