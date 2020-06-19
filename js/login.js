function alert($msg){	
	$("#alert").modal('show');
	$("#myModalLabelalert").html($msg);
	$("#modal-btn-ok").on("click", function(){		
		$("#alert").modal('hide');
		location.href = "index.php5";
	});		
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
			location.href='html/config_bd_ini.php5';
		}
	});		
}