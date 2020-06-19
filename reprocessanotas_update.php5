<?php
	include_once("classes/class_bd.php5");
	include_once("reprocessanotas.php5");
	$conect= new ClassBD();
	$conect->conectBanco();
	$sql='begin reproc_notas2; end;';
	$parse=oci_parse($conect->conn,$sql);
	ociexecute($parse);
	if ($parse==TRUE){
		$conect->geraLog();
		echo "<script type='text/javascript'>alert('NOTAS MARCADAS PARA REPROCESSAMENTO COM SUCESSO!');</script>";	
	}else{
		echo "<script type='text/javascript'>alert('COMANDO NÃO EXECUTADO!');</script>";
		$e=oci_error();
		echo "Retorno do Banco: ".$e['message'];
		die();
	}

/*
PROCEDURE reproc_notas2

create or replace procedure reproc_notas2
is
qtde int;
begin
     select count(*) into qtde from ZEUS.TAB_CONTROLE_NFE WHERE COD_ERRO_NFE IS NULL 
     AND DTH_INCLUSAO BETWEEN SYSDATE -35 AND SYSDATE -2/24;
     if qtde >0 then
        update ZEUS.TAB_CONTROLE_NFE set cod_situacao_envio_nfe='RP',cod_erro_nfe=635,cod_erro_1=null,cod_erro_2=null,cod_erro_3=null,cod_erro_4=null,controle_robo=null
        WHERE COD_ERRO_NFE IS NULL AND DTH_INCLUSAO BETWEEN SYSDATE -35 AND SYSDATE -2/24 and 
        tipo_modelo_nf=65 and cod_tipo_emissao_nfe in (1,9);
        update ZEUS.TAB_CONTROLE_NFE set cod_situacao_envio_nfe='PE',id_lote_nfe=null
        WHERE COD_ERRO_NFE IS NULL AND DTH_INCLUSAO BETWEEN SYSDATE -35 AND SYSDATE -2/24 and 
        tipo_modelo_nf=55 and cod_tipo_emissao_nfe in (1);
     end if;
commit;	 
end;

*/	

	
?>


