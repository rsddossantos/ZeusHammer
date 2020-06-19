<?php
include_once("classes/class_bd.php5");
include_once("ncminexistente.php5");
$conect= new ClassBD();


if (isset($_GET['ncm']) && empty($_GET['ncm']) == false) {
    $ncm=addslashes($_GET['ncm']);
	$loja=addslashes($_GET['loja']);
	$nota=addslashes($_GET['nota']);
	$serie=addslashes($_GET['serie']);
	$produto=addslashes($_GET['produto']);
	
	$query="UPDATE TAB_NOTA_ITEM SET COD_NCM=$ncm WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie AND CODPRODUTO=$produto";
	$query2="UPDATE TAB_CONTROLE_NFE SET cod_situacao_envio_nfe='RP',cod_erro_nfe=394,cod_erro_1 = '778',cod_erro_2 = null , cod_erro_3 = null, cod_erro_4 = null, controle_robo = null 
			WHERE ID_NFE IN (SELECT ID_NFE FROM TAB_NOTA_HEADER WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie)";
	$conect->conectBanco();
	$parse=oci_parse($conect->conn,$query);
	ociexecute($parse);
	$rows=oci_num_rows($parse);
	if ($rows>0){
		$parse2=oci_parse($conect->conn,$query2);
		ociexecute($parse2);
		echo "<script type='text/javascript'>
		var status='OK';
		$('table').hide();
		var nota=".$nota.";var serie=".$serie.";var loja=".$loja.";
		alert('Nota alterada com sucesso, $rows item(s) no total!<br/> Acompanhe o reprocessamento da nota que será inserida com rejeição 394!');</script>";
	}else{
		echo "<script type='text/javascript'>
		var status='NOK';
		$('table').hide();
		var nota=".$nota.";var serie=".$serie.";var loja=".$loja.";
		alert('Erro no banco, tente novamente!');</script>";
	}	
	
} else {
	$loja=addslashes($_GET['loja']);
	$nota=addslashes($_GET['nota']);
	$serie=addslashes($_GET['serie']);
	echo "<script type='text/javascript'>
	var status='NOK';
	$('table').hide();
	var nota=".$nota.";var serie=".$serie.";var loja=".$loja.";
	alert('Preencha o campo NCM antes de enviar!');</script>";
}

?>
