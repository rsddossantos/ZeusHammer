<?php
include_once("classes/class_bd.php5");
include_once("totalcbenef.php5");
$conect= new ClassBD();
$conect->conectBanco();
$sql="select id_nfe from tab_controle_nfe where cod_erro_nfe=930";
$sql2='begin
for c_reject in
(select *
from tab_nota_header where id_nfe in
(select id_nfe from tab_controle_nfe
where cod_erro_nfe=930))
LOOP
update tab_mercadoria set codigo_beneficio_fiscal=\'PR810021\',flg_regera_xml=\'1\'
where cod_loja=c_reject.codloja and cod_mercadoria in 
(select codproduto from tab_nota_item 
where codloja=c_reject.codloja and numnota=c_reject.numnota and serie_nf=c_reject.serie_nf and situacaotributaria in (\'40\',\'040\',\'240\',\'20\',\'020\',\'220\',\'050\',\'250\',\'051\',\'251\',\'041\',\'241\',\'070\',\'270\',\'090\',\'290\',\'030\',\'230\'));
commit;
end loop;
end;';
$sql3="update tab_controle_nfe 
set cod_erro_nfe=394,id_lote_nfe=null,cod_situacao_envio_nfe='RP',cod_erro_1='930',cod_erro_2=null,cod_erro_3=null,cod_erro_4=null,controle_robo=null,dth_alteracao=sysdate,idt_usuario_alteracao='ZANTHUS'
where cod_erro_nfe=930";

$parse=oci_parse($conect->conn,$sql);
ociexecute($parse);
$rows=oci_fetch_all($parse,$notas);
$ids=implode(',', $notas[ID_NFE]);

if ( $parse == TRUE ){
    $parse2=oci_parse($conect->conn,$sql2);
    ociexecute($parse2);
    if ( $parse2 == TRUE ) {
        $parse3=oci_parse($conect->conn,$sql3);
        ociexecute($parse3);
        $result=oci_num_rows($parse3);
        if ($result > 0) {
            $conect->geraLog($result);
            echo "<script type='text/javascript'>
                alert('NOTAS CORRIGIDAS E MARCADAS PARA REPROCESSAMENTO COM SUCESSO!<br/><br/>Clique <a style=\"cursor: pointer;\" onclick=\"window.open(\'consultaporids.php5?ids=".$ids."\',\'_blank\',\'width=1200,height=470\')\">AQUI</a> caso queira acompanhar!');
              </script>";
        } else {
            echo "<script type='text/javascript'>alert('NÃO EXISTEM MAIS NOTAS MODELO 55 COM REJEIÇÃO 930!');</script>";
        }
    } else {
        echo "<script type='text/javascript'>alert('COMANDO NÃO EXECUTADO!');</script>";
    }
}else{
    echo "<script type='text/javascript'>alert('COMANDO NÃO EXECUTADO!');</script>";
}

?>


