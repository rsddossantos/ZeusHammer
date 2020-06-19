function clicou(){
document.getElementById('gif').style.display="block";
}
$(document).ready(function(){
	$("#enviar").click(function(event){
		if ($("#data").val()== null || $("#data").val() =='' ||
			$("#loja").val()=='0'){
				document.getElementById('gif').style.display="none";
				$('#controle').css('display','block')
				event.preventDefault();
		}
	});
});