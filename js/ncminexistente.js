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
		if (status=='NOK'){
				location.href='ncminexistente.php5?&nota='+nota+'&loja='+loja+'&serie='+serie+'';
		}else{ 
			if (status=='OK'){
				location.href='consulta_status.php5?&nota='+nota+'&loja='+loja+'&serie='+serie+'';
			}	
		}
	});		
}
$(document).ready(function(){
    $("#enviar").click(function(event){
        if ($("#serie").val()== null || $("#serie").val() =='' ||
            $("#nota").val()== null || $("#nota").val() =='' ||
            $("#loja").val()=='0'){
            document.getElementById('gif').style.display="none";
            $('#controle').css('display','block')
            event.preventDefault();
        }
    });
});
