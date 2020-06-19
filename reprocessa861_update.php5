<?php
include_once("classes/class_bd.php5");
include_once("totalfcpdif.php5");
$conect= new ClassBD();
$conect->conectBanco();
$sql="select id_nfe from tab_controle_nfe where cod_erro_nfe=861";
$sql2='begin
for c_reject in
(select i.codloja,i.numnota,i.serie_nf,i.numitem
from tab_nota_item i inner join tab_nota_header h on
i.codloja=h.codloja and
i.numnota=h.numnota and
i.serie_nf=h.serie_nf and
i.tipo_modelo_nf=h.tipo_modelo_nf 
where i.situacaotributaria in (\'40\',\'040\',\'240\') and i.valor_fundo_pobreza>0 and h.id_nfe in
(select id_nfe from tab_controle_nfe 
where cod_erro_nfe=861))
LOOP
update tab_nota_item set valor_fundo_pobreza= \'0\', perc_fundo_pobreza =\'0\'
where codloja=c_reject.codloja and numnota=c_reject.numnota and serie_nf=c_reject.serie_nf and numitem=c_reject.numitem
and situacaotributaria in (\'40\',\'040\',\'240\') and valor_fundo_pobreza>0;
commit;
end loop;
end;';
$sql3="update tab_controle_nfe 
set cod_erro_nfe=394,cod_situacao_envio_nfe='RP',cod_erro_1='861',cod_erro_2=null,cod_erro_3=null,cod_erro_4=null,controle_robo=null,dth_alteracao=sysdate,idt_usuario_alteracao='ZANTHUS'
where cod_erro_nfe=861";

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
                //alert('NOTAS CORRIGIDAS E MARCADAS PARA REPROCESSAMENTO COM SUCESSO!<br/><br/>Clique <a href=\"consultaporids.php5?ids=".$ids."\" target=\"_blank\">AQUI</a> caso queira acompanhar!');
                alert('NOTAS CORRIGIDAS E MARCADAS PARA REPROCESSAMENTO COM SUCESSO!<br/><br/>Clique <a style=\"cursor: pointer;\" onclick=\"window.open(\'consultaporids.php5?ids=".$ids."\',\'_blank\',\'width=1200,height=470\')\">AQUI</a> caso queira acompanhar!');
              </script>";
        } else {
            echo "<script type='text/javascript'>alert('NÃO EXISTEM MAIS NOTAS COM REJEIÇÃO 861!');</script>";
        }
    } else {
        echo "<script type='text/javascript'>alert('COMANDO NÃO EXECUTADO!');</script>";
    }
}else{
    echo "<script type='text/javascript'>alert('COMANDO NÃO EXECUTADO!');</script>";
}

?>


