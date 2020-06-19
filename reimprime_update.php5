<?php
include_once("classes/class_bd.php5");
include_once("reimprime.php5");
$conect= new ClassBD();
$id=0;
$loja=0;
$tipo='';
$status='';
$reimpresso='';
$data='';

if (isset($_GET['id']) && empty($_GET['id']) == false &&
	isset($_GET['loja']) && empty($_GET['loja']) == false &&
	isset($_GET['tipo']) && empty($_GET['tipo']) == false &&
	isset($_GET['status']) && empty($_GET['status']) == false &&
	isset($_GET['reimpresso']) && empty($_GET['reimpresso']) == false &&
	isset($_GET['data']) && empty($_GET['data']) == false) {
    $id=addslashes($_GET['id']);
	$loja=addslashes($_GET['loja']);
	$tipo=addslashes($_GET['tipo']);
	$status=addslashes($_GET['status']);
	$reimpresso=addslashes($_GET['reimpresso']);
	$data=addslashes($_GET['data']);
		
	if ($tipo=='REEMBOLSO'){
		if ($status=='R' && $reimpresso=='S'){
			$query_re="update tab_reembolso_cliente_final set flg_status_vale='P',flg_impresso='N' where cod_registro=$id and cod_loja=$loja";
			$conect->conectBanco();
			$parse_re=oci_parse($conect->conn,$query_re);
			ociexecute($parse_re);
			$data=date('Y-m-d', strtotime($data));
			echo "<script>
				var loja=".$loja."; var data=escape('".$data."');
				alert('Alteração realizada com sucesso!! Registros Alterados: ".oci_num_rows($parse_re)."');
				</script>";
			$conect->geraLog();	
			oci_free_statement($parse_re);
		} else {
			$data=date('Y-m-d', strtotime($data));
			echo "<script type='text/javascript'>
				var loja=".$loja."; var data=escape('".$data."');
				alert('VOUCHER FINALIZADO,CANCELADO OU AINDA NÃO FOI REIMPRESSO!');
				</script>";
		}
	} else {
		if ($status=='R' && $reimpresso=='S'){
			$query_tro="update tab_troca_vale set flg_status_vale='P',flg_impresso='N' where cod_troca=$id and cod_loja=$loja";
			$conect->conectBanco();
			$parse_tro=oci_parse($conect->conn,$query_tro);
			ociexecute($parse_tro);
			$data=date('Y-m-d', strtotime($data));
			echo "<script>
				var loja=".$loja."; var data=escape('".$data."');
				alert('Alteração realizada com sucesso!! Registros Alterados: ".oci_num_rows($parse_tro)."');
				</script>";
			$conect->geraLog();
			oci_free_statement($parse_tro);	
		} else {
			$data=date('Y-m-d', strtotime($data));
			echo "<script type='text/javascript'>
				var loja=".$loja."; var data=escape('".$data."');
				alert('VOUCHER FINALIZADO,CANCELADO OU AINDA NÃO FOI REIMPRESSO!');
				</script>";
			
		}
	}
} else {
	
	echo "<script type='text/javascript'>
	var loja=".$loja."; var data=escape('".$data."');
	alert('COMANDO NÃO EXECUTADO!');</script>";
}

?>
