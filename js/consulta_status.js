function alert($msg,$loja,$serie,$nota){
	$("#alert").modal('show');
	$("#myModalLabelalert").html($msg);
	$("#modal-btn-ok").on("click", function(){
		$("#alert").modal('hide');
		location.href="consulta_status.php5?nota="+$nota+"&loja="+$loja+"&serie="+$serie;
	});
}