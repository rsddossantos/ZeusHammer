function clicou(){
		document.getElementById('gif').style.display="block";
	}
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	$("#enviar").click(function(event){
		if ($("#string").val()== null || $("#string").val() =='' ||
			$("#usuario").val()== null || $("#usuario").val() =='' ||
			$("#senha").val()== null || $("#senha").val() ==''){
				document.getElementById('gif').style.display="none";
				$('#controle').css('display','block');
				$('#controle').css('margin-top','15px');
				$("#action").val('');
				event.preventDefault();
		}
	});	
	$("#testar").click(function(event){
		if ($("#string").val()== null || $("#string").val() =='' ||
			$("#usuario").val()== null || $("#usuario").val() =='' ||
			$("#senha").val()== null || $("#senha").val() ==''){
				document.getElementById('gif').style.display="none";
				$('#controle').css('display','block');
				$('#controle').css('margin-top','15px');
				$("#action").val('');
				event.preventDefault();
		}else{
			$("#action").val('testou');	
		}	
	});
	
});
