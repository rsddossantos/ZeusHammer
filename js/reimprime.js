function clicou(){
document.getElementById('gif').style.display="block";
}
function alert($msg){	
	$('#alert').modal('show');
	$('#myModalLabelalert').html($msg);
	$('.alert').remove();
	$('.btn.btn-info').remove();
	$('form').remove();
	$('#modal-btn-ok').on('click', function(){		
		$('#alert').modal('hide');
		location.href='reimprime.php5?&data='+data+'&loja='+loja+'';
	});		
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