<?php
include_once("classes/class_bd.php5");
include_once("consulta_status.php5");
if( isset($_GET['loja']) && empty ($_GET['loja'])==false  &&
    isset($_GET['serie'])  && empty ($_GET['serie'])==false &&
    isset($_GET['nota']) && empty($_GET['nota'])==false ) {
    $loja=addslashes($_GET['loja']);
    $serie=addslashes($_GET['serie']);
    $nota=addslashes($_GET['nota']);
}else{
    echo "<script type='text/javascript'>alert('FALHA NA CAPTURA DOS DADOS!');</script>";
}
$conect= new ClassBD();
$conect->conectBanco();
$sql="UPDATE TAB_CONTROLE_NFE SET cod_situacao_envio_nfe='RP',cod_erro_nfe=394,cod_erro_1 = null,cod_erro_2 = null , cod_erro_3 = null, cod_erro_4 = null, controle_robo = null 
							WHERE ID_NFE IN (SELECT ID_NFE FROM TAB_NOTA_HEADER WHERE NUMNOTA=$nota AND CODLOJA=$loja AND SERIE_NF=$serie)";
$parse=oci_parse($conect->conn,$sql);
ociexecute($parse);
$rows=oci_num_rows($parse);
if ($rows>0){
    echo "<script type='text/javascript'>alert('NOTA MARCADA PARA REPROCESSAMENTO COM SUCESSO!',$loja,$serie,$nota);</script>";
}else{
    echo "<script type='text/javascript'>alert('COMANDO NÃO EXECUTADO! TENTE NOVAMENTE!',$loja,$serie,$nota);</script>";
}


?>
