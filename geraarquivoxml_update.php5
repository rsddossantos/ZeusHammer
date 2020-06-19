<?php
/*
 * @author: Rodrigo Silveira 
 * @version: 1.0
 *
 * GERAÇÃO DE ARQUIVO XML
 
*************************************************** 
 CRIADO TABELA TEMPORARIA PARA ARMAZENAR AS NOTAS
 TEMP_NOTAS_XML
/*
CREATE TABLE TEMP_NOTAS_XML
(ID_NFE CHAR(44) NOT NULL,
DSC_XML_NFE CLOB,
DSC_XML_RETORNO_NFE CLOB)
  
INSERT INTO TEMP_NOTAS_XML(ID_NFE,DSC_XML_NFE,DSC_XML_RETORNO_NFE)
(select id_nfe,DSC_XML_NFE,DSC_XML_RETORNO_NFE from tab_controle_nfe
where id_nfe in 
())
***************************************************
*/


include_once("classes/class_bd.php5");
include_once("geraarquivoxml.php5");

if(isset($_REQUEST['tipo']) && empty($_REQUEST['tipo'])==false) {
    $tipo=$_REQUEST['tipo'];
}
$conect= new ClassBD();
$conect->conectBanco();
$query="select id_nfe,dsc_xml_nfe,dsc_xml_retorno_nfe from TEMP_NOTAS_XML";
$parse=oci_parse($conect->conn,$query);
ociexecute($parse);
$rows=oci_fetch_all($parse,$campos);
if ($tipo==1) {
    for ($x = 0; $x < $rows; $x++) {
        file_put_contents('arquivos/' . $campos[ID_NFE][$x] . '.xml', $campos[DSC_XML_NFE][$x]);
    }
}else {
    for ($x = 0; $x < $rows; $x++) {
        file_put_contents('arquivos/' . $campos[ID_NFE][$x] . '.xml', $campos[DSC_XML_NFE][$x].PHP_EOL.$campos[DSC_XML_RETORNO_NFE][$x]);
    }
}
$conect->geraLog();
echo "<script type='text/javascript'>alert('Arquivos gerados com sucesso! Verifique a pasta <kbd>arquivos/</kbd>');</script>";




		

  

