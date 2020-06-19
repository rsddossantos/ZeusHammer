function clicou(){	
document.getElementById('gif').style.display="block";
}
$(document).ready(function(){
	$("#enviar").click(function(event){
		if ($("#id_nfe").val()== null || $("#id_nfe").val() ==''){
			document.getElementById('gif').style.display="none";
			$('#controle').css('display','block')
			event.preventDefault();
		}
	});
});
function update(){
	confirm('Deseja gerar arquivos de todos os dados contidos na tabela TEMP_NOTAS_XML?');	
}	
function confirm($msg){	
	var modalConfirm = function(callback){
		$("#confirm").modal('show');
		$("#myModalLabel").html($msg);
		$("#modal-btn-si").on("click", function(){
			callback(true);
			$("#confirm").modal('hide');
		});
		$("#modal-btn-no").on("click", function(){
			callback(false);
			$("#confirm").modal('hide');
		});
	};
	modalConfirm(function(confirm){
		if(confirm){
			if (document.getElementById('defaultChecked').checked){
				var tipo=1;
			} else {
				var tipo=2;
			}
			location.href='geraarquivoxml_update.php5?tipo='+tipo+'';
		}
	});		
}
function alert($msg){	
	$("#alert").modal('show');
	$("#myModalLabelalert").html($msg);
	$("#modal-btn-ok").on("click", function(){		
		$("#alert").modal('hide');
		location.href='geraarquivoxml.php5';
	});			
}
